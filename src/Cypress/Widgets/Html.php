<?php

namespace Armincms\Contract\Cypress\Widgets;

use Laravel\Nova\Fields\Code;
use Zareismail\Gutenberg\GutenbergWidget;

class Html extends GutenbergWidget
{
    /**
     * The logical group associated with the template.
     *
     * @var string
     */
    public static $group = 'Html';

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
