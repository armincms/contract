<?php

namespace Armincms\Contract\Cypress\Widgets;
 
use Laravel\Nova\Fields\Code; 
use Zareismail\Cypress\Http\Requests\CypressRequest; 
use Zareismail\Cypress\Widget;   

class Html extends Widget
{        
    /**
     * Bootstrap the resource for the given request.
     * 
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest $request 
     * @param  \Zareismail\Cypress\Layout $layout 
     * @return void                  
     */
    public function boot(CypressRequest $request, $layout)
    {     
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
            Code::make(__('HTML string'), 'config->html') 
                ->required()
                ->rules('required'), 
        ];
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    { 
        return $this->metaValue('html');
    }
}
