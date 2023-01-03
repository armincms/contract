<?php

namespace Armincms\Contract\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\MultiSelect;
use Laravel\Nova\Fields\Number;
use Zareismail\Gutenberg\Nova\Fragment;
use Zareismail\Gutenberg\Nova\Layout as Resource;
use Zareismail\Gutenberg\Nova\Widget;

class Layout extends Resource
{
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return collect(parent::fields($request))->map(function ($field) {
            if (! $field instanceof BelongsToMany) {
                return $field;
            }

            return BelongsToMany::make(__('Configure Widgets'), 'widgets', Widget::class)->fields(fn () => [
                Number::make(__('Widget Order'), 'order')
                    ->default(time())
                    ->required()
                    ->rules('required'),

                MultiSelect::make(__('Widget display'), 'config->display_on')
                    ->options($displayOptions = static::displayOptions())
                    ->placeholder(__('Everywhere'))
                    ->displayUsingLabels()
                    ->nullable(),

                MultiSelect::make(__('Widget hide'), 'config->hide_on')
                    ->options($displayOptions)
                    ->placeholder(__('Nowhere'))
                    ->displayUsingLabels()
                    ->nullable(),
            ]);
        })->toArray();
    }

    /**
     * Get possible places for widget.
     *
     * @return array
     */
    public function displayOptions(): array
    {
        return Fragment::newModel()
            ->whereHas('layout', fn ($query) => $query->whereKey(request()->route('resourceId')))
            ->get()
            ->map(fn ($fragment) => app()->make($fragment->cypressFragment()))
            ->filter(fn ($fragment) => method_exists($fragment, 'displayOptions'))
            ->mapWithKeys(function ($fragment) {
                return collect($fragment->displayOptions())->map(fn ($label) => [
                    'label' => $label,
                    'group' => $fragment::fragment()->name,
                ]);
            })->toArray();
    }
}
