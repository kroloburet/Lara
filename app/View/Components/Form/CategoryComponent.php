<?php

namespace App\View\Components\Form;

use App\Models\Abstract\Material;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class CategoryComponent extends Component
{
    private $isCategory = false;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public Material|null $material = null
    ){
        $this->isCategory = $this->material?->type === 'category';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $categories = materialBuilder('category')
            ->select('id', 'category_id')
            ->with('contents')
            ->get();

        $optionsTree = $this->getOptionsTree($categories);

        return view('components.form.category-component', compact('optionsTree'));
    }

    public function getOptionsTree(
        Collection $categories,
        string $category_id = null,
        int $level = 0
    ): string|null
    {
        if ($categories->isEmpty()) {
            return null;
        }

        $options = '';

        foreach ($categories as $category) {
            if ($category->category_id == $category_id) {

                $temp = '<option value="' . $category->id . '" '
                    . ($this->material?->category_id == $category->id ? 'selected' : null) . ' '
                    . ($this->isCategory && $this->material->id == $category->id ? 'disabled' : null) . '>'
                    . str_repeat('&mdash;', $level) . ' ' . $category->content()->title
                    . '</option>' . PHP_EOL;

                $subLevel = $this->getOptionsTree($categories, $category->id, $level + 1);

                if ($subLevel) {
                    $temp .= $subLevel;
                }

                $options .= $temp;
            }
        }

        return $options;
    }
}
