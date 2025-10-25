<?php

namespace App\View\Components;

use App\Models\Abstract\Material;
use App\Models\Category;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BreadcrumbsComponent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Material $material
    ){}

    private function getChain(Category|null $category): string
    {
        if (! $category) return '';

        $title = optional($category->contents->where('locale', app()->getLocale())->first())->title;
        $route = routeToMaterial($category);

        $chain = $this->getChain($category->category);

        if ($title && $route) {
            $markIfBlocked = markIfBlocked($category);
            $markIfBlockedClass = $markIfBlocked ? 'class="' . $markIfBlocked . '"' : '';
            $chain .= '<li><a href="' . $route . '"' . $markIfBlockedClass . '>' . $title . '</a></li>' . PHP_EOL;
        }

        return $chain;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $typeHref = route('materials',['type' => $this->material->type]);
        $chain = '<li><a href="/">' . __('base.Home') . '</a></li>' . PHP_EOL;
        $chain .= '<li><a href="' . $typeHref . '">' . __("material.{$this->material->type}.they.upper") . '</a></li>' . PHP_EOL;
        $chain .= $this->getChain($this->material->category);
        $chain .= "<li>{$this->material->content()->title}</li>" . PHP_EOL;
        return view('components.breadcrumbs-component', compact('chain'));
    }
}
