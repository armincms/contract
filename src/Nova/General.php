<?php

namespace Armincms\Contract\Nova;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Manogi\Tiptap\Tiptap;

class General extends Option
{
    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Select::make(__('Website Editor'), 'editor')->options([
                Trix::class => 'Trix Editor',
                Tiptap::class => 'Tiptap Editor',
            ])->default(Tiptap::class)->displayUsingLabels(),

            Boolean::make(__('Disable Otimize Images'), 'disable_image_optimize')
                ->default(false)
                ->help(_('Disable image optimization for shared host to prevent "proce_open" errors.')),
        ];
    }
}
