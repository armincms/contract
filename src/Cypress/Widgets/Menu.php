<?php

namespace Armincms\Contract\Cypress\Widgets;

use Armincms\Contract\Gutenberg\Templates\MenuItem;
use Armincms\Contract\Gutenberg\Templates\Navbar;
use Armincms\Contract\Nova\Menu as MenuResource;
use Laravel\Nova\Fields\Select;
use Zareismail\Cypress\Widget;  
use Zareismail\Cypress\Http\Requests\CypressRequest; 
use Zareismail\Gutenberg\HasTemplate;
use OptimistDigital\MenuBuilder\MenuBuilder;

class Menu extends Widget
{       
    use HasTemplate;

    /**
     * Bootstrap the resource for the given request.
     * 
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest $request 
     * @param  \Zareismail\Cypress\Layout $layout 
     * @return void                  
     */
    public function boot(CypressRequest $request, $layout)
    {    
    	$this->when($this->hasMeta('template'), function() use ($request, $layout) { 
	        $this->bootstrapTemplate($request, $layout);  
	        $menuClass = MenuBuilder::getMenuClass();
	        $menu = $menuClass::findOrFail($this->metaValue('menu'));

	        $this->withMeta([
	        	'menu' => $menu->formatForAPI(app()->getLocale()),
	        ]);
    	}, function() {
    		$this->renderable(false);
    	});
    }

    /**
     * Get the template id.
     * 
     * @return integer
     */
    public function getTemplateId(): int
    {
        return $this->metaValue('template.navbar');
    } 

    /**
     * Serialize the widget fro template.
     * 
     * @return array
     */
    public function serializeForTemplate(): array
    {  
        $html = collect($this->metaValue('menu.menuItems'))->reduce(function($htmls, $item) {
            return $htmls . $this->renderItem($item, 0);
        });

        return [
            'name' => $this->metaValue('menu.name'),
            'items'=> $html,
        ];
    }

    public function renderItem($item, $depth, $template = null)
    {   
        if ($templateId = intval(data_get($item, 'data.tempalte'))) { 
            $template = $this->findTemplate($templateId);
        } elseif ($templateId = intval($this->metaValue("template.row.{$depth}"))) {
            $template = $this->findTemplate($templateId);
        } 

        $childrens = collect($item['children'])->reduce(function($htmls, $item) use ($depth, $template) {
            return $htmls . $this->renderItem($item, $depth + 1, $template);            
        }); 

        $data = array_merge($item, [
            'url' => $item['value'] ?? '#!',
            'childrens' => $childrens, 
            'hasChildren' => ! empty($childrens),
            'depth' => $depth, 
        ]);

        return $template->gutenbergTemplate($data)->render();
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function fields($request)
    {
        return [
            Select::make(__('Target Menu'), 'config->menu')
                ->options(MenuResource::newModel()->get()->keyBy->getKey->map->name)
                ->displayUsingLabels()
                ->required()
                ->rules('required'),

            Select::make(__('Navbar Template'), 'config->template->navbar')
                ->options(static::availableTemplates(Navbar::class))
                ->displayUsingLabels()
                ->required()
                ->rules('required'),

            Select::make(__('Navbar Item Template'), 'config->template->row->0')
                ->options(static::availableTemplates(MenuItem::class))
                ->displayUsingLabels()
                ->required()
                ->rules('required'),

            Select::make(__('Depth [1] Template'), 'config->template->row->1')
                ->options(static::availableTemplates(MenuItem::class))
                ->displayUsingLabels()
                ->nullable(),

            Select::make(__('Depth [2] Template'), 'config->template->row->2')
                ->options(static::availableTemplates(MenuItem::class))
                ->displayUsingLabels()
                ->nullable(),

            Select::make(__('Depth [3] Template'), 'config->template->row->3')
                ->options(static::availableTemplates(MenuItem::class))
                ->displayUsingLabels()
                ->nullable(),

            Select::make(__('Depth [4] Template'), 'config->template->row->4')
                ->options(static::availableTemplates(MenuItem::class))
                ->displayUsingLabels()
                ->nullable(),
        ];
    }
}
