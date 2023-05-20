<?php

namespace Armincms\Contract\Nova;

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
        ];
    }
}
