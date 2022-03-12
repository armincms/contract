<?php

namespace Armincms\Contract\Gutenberg\Widgets; 

use Zareismail\Gutenberg\Gutenberg;

trait BootstrapsTemplate
{     
    /**
     * Bootstrap template for the given id.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Zareismail\Gutenberg\GutenbergLayout $layout
     * @param  integer  $templateKey
     * @return \Zareismail\Gutenberg\GutenbergTemplate
     */
    public function bootstrapTemplate($request, $layout, $templateKey)
    {   
        return tap($this->resolveTemplate($templateKey), function($template) use ($request, $layout) { 
            $template->plugins->boot($request, $layout);
        }); 
    }

    /**
     * resolve template instance for the given id.
     * 
     * @param  integer $templateKey
     * @return $this
     */
    public function resolveTemplate($templateKey)
    {  
        return tap(Gutenberg::cachedTemplates()->find($templateKey), function($template) {
            abort_if(
                is_null($template),
                422, 
                "Template not found to display widget: ". class_basename($this),
            ); 
        });
    }
}
