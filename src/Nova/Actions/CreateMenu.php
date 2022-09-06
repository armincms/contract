<?php

namespace Armincms\Contract\Nova\Actions;

use Armincms\Contract\Nova\Menu;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;

class CreateMenu extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $menu = Menu::newModel()->forceFill([
            'name' => $fields->get('name'),
            'slug' => '*',
        ]);

        $menu->save();

        return [
            'push' => [
                'name' => 'edit',
                'params' => [
                    'resourceName' => Menu::uriKey(),
                    'resourceId' => $menu->getKey(),
                ],
            ],
        ];
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Text::make(__('Menu Name'), 'name')
                ->required()
                ->rules('required'),
        ];
    }
}
