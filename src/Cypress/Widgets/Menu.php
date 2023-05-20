<?php

namespace Armincms\Contract\Cypress\Widgets;

use Armincms\Contract\Gutenberg\Templates\MenuItem;
use Armincms\Contract\Gutenberg\Widgets\BootstrapsTemplate;
use Armincms\Contract\Gutenberg\Widgets\ResolvesDisplay;
use Armincms\Contract\Nova\Menu as MenuResource;
use Laravel\Nova\Fields\Select;
use Outl1ne\MenuBuilder\MenuBuilder;
use Zareismail\Cypress\Http\Requests\CypressRequest;
use Zareismail\Gutenberg\Gutenberg;
use Zareismail\Gutenberg\GutenbergWidget;

class Menu extends GutenbergWidget
{
    use BootstrapsTemplate;
    use ResolvesDisplay {
        displayResource as parentDisplayResource;
    }

    /**
     * Bootstrap the resource for the given request.
     *
     * @param  \Zareismail\Cypress\Layout  $layout
     * @return void
     */
    public function boot(CypressRequest $request, $layout)
    {
        parent::boot($request, $layout);

        $menuClass = MenuBuilder::getMenuClass();
        $menu = $menuClass::findOrFail($this->metaValue('menu'));

        $this->withMeta([
            'menu' => $menu->formatForAPI(app()->getLocale()),
        ]);

        collect()->range(0, 4)->each(function ($depth) use ($request, $layout) {
            if ($templateKey = $this->metaValue("row.{$depth}")) {
                $template = $this->bootstrapTemplate($request, $layout, $templateKey);

                $this->displayResourceUsing(function ($attributes) use ($template) {
                    return $template->gutenbergTemplate($attributes)->render();
                }, $depth);
            }
        });
    }

    /**
     * Serialize the widget fro template.
     */
    public function serializeForDisplay(): array
    {
        $html = collect($this->metaValue('menu.menuItems'))->reduce(function ($htmls, $item) {
            return $htmls.$this->renderItem($item, 0);
        });

        return [
            'name' => $this->metaValue('menu.name'),
            'items' => $html,
        ];
    }

    /**
     * Renders menu items.
     *
     * @param  string  $item
     * @param  string  $depth
     * @return string
     */
    public function renderItem($item, $depth)
    {
        $childrens = collect($item['children'])->reduce(function ($htmls, $item) use ($depth) {
            return $htmls.$this->renderItem($item, $depth + 1);
        });

        $attributes = array_merge($item, [
            'url' => $item['value'] ?? '#!',
            'childrens' => $childrens,
            'hasChildren' => ! empty($childrens),
            'depth' => $depth,
            'active' => trim(request()->path(), '/') == trim(data_get($item, 'value'), '/') ||
                request()->url() == data_get($item, 'value'),
        ]);

        return $this->displayResource($attributes, $depth);
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function fields($request)
    {
        $menuItemTemplates = Gutenberg::cachedTemplates()
            ->forHandler(MenuItem::class)
            ->keyBy->getKey()
            ->map->name;

        return [
            Select::make(__('Target Menu'), 'config->menu')
                ->options(MenuResource::newModel()->get()->keyBy->getKey()->map->name)
                ->displayUsingLabels()
                ->required()
                ->rules('required'),

            Select::make(__('Navbar Item Template'), 'config->row->0')
                ->options($menuItemTemplates)
                ->displayUsingLabels()
                ->required()
                ->rules('required'),

            Select::make(__('Depth [1] Template'), 'config->row->1')
                ->options($menuItemTemplates)
                ->displayUsingLabels()
                ->nullable(),

            Select::make(__('Depth [2] Template'), 'config->row->2')
                ->options($menuItemTemplates)
                ->displayUsingLabels()
                ->nullable(),

            Select::make(__('Depth [3] Template'), 'config->row->3')
                ->options($menuItemTemplates)
                ->displayUsingLabels()
                ->nullable(),

            Select::make(__('Depth [4] Template'), 'config->row->4')
                ->options($menuItemTemplates)
                ->displayUsingLabels()
                ->nullable(),
        ];
    }

    /**
     * Query related display templates.
     *
     * @return string
     */
    public static function relatableTemplates($request, $query)
    {
        return $query->handledBy(
            \Armincms\Contract\Gutenberg\Templates\Navbar::class
        );
    }

    /**
     * Display resource for the given attributes.
     *
     * @param  string  $resource
     * @return string
     */
    public function displayResource(array $attributes, string $resource = null)
    {
        $html = $this->parentDisplayResource($attributes, $resource);

        return is_numeric($resource) && $resource && ! $html
            ? $this->parentDisplayResource($attributes, $resource - 1)
            : $html;
    }
}
