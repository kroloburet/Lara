<?php

namespace App\Http\Controllers\XHR\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\XHR\Admin\MenuManagerRequest;
use App\Models\Menu;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class MenuManagerController extends Controller
{
    protected Builder $builder;

    public function __construct()
    {
        $this->builder = Menu::withTrashed();
    }

    /**
     * Handle creation of a menu item.
     *
     * @param MenuManagerRequest $request The validated request containing menu data.
     * @return JsonResponse JSON response with updated menu fragments.
     */
    public function create(MenuManagerRequest $request): JsonResponse
    {
        $this->authorize('permits', ['menu', 'c']);

        $validated = $request->validated()['menu'];
        $order = $this->calculateOrder($validated['parent_id'] ?: null, $validated['order_position']);

        Menu::query()->create([
            'title' => $validated['title'],
            'parent_id' => $validated['parent_id'] ?: null,
            'url' => $validated['url'],
            'order' => $order,
            'locale' => $validated['locale'],
            'target' => $validated['target'] ?? null,
        ]);

        // Push log event
        $currentConsumer = auth()->user();
        $currentConsumer->pushLogEvent("Menu item [{$validated['title']}] created");

        return $this->refresh($validated['locale']);
    }

    /**
     * Handle update of a menu item.
     *
     * @param MenuManagerRequest $request The validated request containing menu data.
     * @return JsonResponse JSON response with updated menu fragments.
     */
    public function update(MenuManagerRequest $request): JsonResponse
    {
        $this->authorize('permits', ['menu', 'u']);

        $validated = $request->validated()['menu'];
        $item = $this->builder->find($validated['item_id']);
        $order = $this->calculateOrder($validated['parent_id'] ?: null, $validated['order_position']);

        $item->update([
            'title' => $validated['title'],
            'parent_id' => $validated['parent_id'] ?: null,
            'url' => $validated['url'],
            'order' => $order,
            'locale' => $validated['locale'],
            'target' => $validated['target'] ?? null,
        ]);

        // Push log event
        $currentConsumer = auth()->user();
        $currentConsumer->pushLogEvent("Menu item [{$validated['title']}] updated");

        return $this->refresh($validated['locale']);
    }

    /**
     * Handle deletion of a menu item.
     *
     * @param MenuManagerRequest $request The validated request containing menu data.
     * @return JsonResponse JSON response with updated menu fragments.
     */
    public function delete(MenuManagerRequest $request): JsonResponse
    {
        $this->authorize('permits', ['menu', 'd']);

        $validated = $request->validated()['menu'];
        $item = $this->builder->find($validated['item_id']);
        $title = $item->title;

        $item->allChildren()->forceDelete();
        $item->forceDelete();

        // Push log event
        $currentConsumer = auth()->user();
        $currentConsumer->pushLogEvent("Menu item [{$title}] deleted");

        return $this->refresh($validated['locale']);
    }

    /**
     * Handle of toggle view menu item.
     *
     * @param MenuManagerRequest $request
     * @return JsonResponse
     */
    public function toggle(
        MenuManagerRequest $request
    ): JsonResponse
    {
        $this->authorize('permits', ['menu', 'd']);

        $validated = $request->validated()['menu'];
        $item = $this->builder->find($validated['item_id']);
        $isPublished = true;

        if ($item->trashed()) {
            $item->allChildren()->restore();
            $item->restore();
        } else {
            $item->allChildren()->delete();
            $item->delete();
            $isPublished = false;
        }

        // Push log event
        $stage = $isPublished ? 'published' : 'unpublished';
        $currentConsumer = auth()->user();
        $currentConsumer->pushLogEvent("Menu item [{$item->title}] {$stage}");

        return $this->refresh($validated['locale']);
    }

    /**
     * Return updated fragments of the menu
     * of the transmitted language version
     *
     * @param string|null $locale
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function refresh(string $locale = null): JsonResponse
    {
        $locale = !$locale ? request('menu.locale') : $locale;
        $menu = getMenu($locale, true);

        return response()->json([
            'menuCreateFormInner' => view('components.admin.menu.menu-form-inner',
                compact('menu', 'locale'))->render(),
            'menuUpdateFormInner' => view('components.admin.menu.menu-form-inner',
                compact('menu', 'locale'))->render(),
            'menuTreeInner' => view('components.admin.menu.menu-tree-inner',
                compact('menu'))->render(),
        ]);
    }
    /**
     * Returns the parent ID options for the menu item form.
     *
     * @param MenuManagerRequest $request The validated request containing menu data.
     * @return JsonResponse JSON response with rendered parent ID options.
     */
    public function parentIdOptions(MenuManagerRequest $request): JsonResponse
    {
        $validated = $request->validated()['menu'];
        $updatableItemId = $validated['item_id'];
        $locale = $validated['locale'];
        $items = getMenu($locale, true);

        return response()->json([
            'parentIdOptions' => view('components.admin.menu.menu-item-parent-options',
                compact('items', 'updatableItemId'))->render(),
        ]);
    }

    /**
     * Returns the order position options for the menu item form.
     *
     * @param MenuManagerRequest $request The validated request containing menu data.
     * @return JsonResponse JSON response with rendered order position options.
     */
    public function orderPositionOptions(MenuManagerRequest $request): JsonResponse
    {
        $validated = $request->validated()['menu'];
        $updatableItemId = $validated['item_id'];
        $parentItems = $this->builder
            ->where('parent_id', $validated['parent_id'])
            ->where('locale', $validated['locale'])
            ->orderBy('order')
            ->get();
        $currentItem = $this->builder->find($updatableItemId);
        $lastItem = $parentItems->where('id', '!=', $updatableItemId)->last(); // The last item except the current

        return response()->json([
            'orderPositionOptions' => view('components.admin.menu.menu-item-order-options', [
                'parentItems' => $parentItems,
                'updatableItemId' => $updatableItemId,
                'currentItem' => $currentItem,
                'lastItemId' => $lastItem ? $lastItem->id : null,
            ])->render(),
        ]);
    }

    /**
     * Calculates the order value for a menu item based on the position.
     *
     * @param ?int $parentId The ID of the new parent menu item, if any.
     * @param string|int $orderPosition The desired position ('first' or ID of the item to insert after).
     * @return int The calculated order value.
     */
    protected function calculateOrder(?int $parentId, string|int $orderPosition): int
    {
        if ($orderPosition === 'first') {
            $minOrder = Menu::query()->where('parent_id', $parentId)->min('order') ?: 0;
            return $minOrder > 0 ? $minOrder : 1; // Return 1 if no items
        }

        $afterItem = Menu::query()->find($orderPosition);

        if (!$afterItem) {
            return Menu::query()->where('parent_id', $parentId)->max('order') + 1 ?: 1;
        }

        return $afterItem->order + 1;
    }
}
