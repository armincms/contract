<?php

namespace Armincms\Contract\Nova\Actions;

use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;

class RebuildSlug extends Action
{
    /**
     * Perform the action on the given models.
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $models->each->forceFill(['slug' => null])->each->save();

        if ($fields->url) {
            $models->each->fillUri()->each->save();
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Boolean::make(__('Include url'), 'url')
                ->required()
                ->rules('required')
                ->default(false),
        ];
    }
}
