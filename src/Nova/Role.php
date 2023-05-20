<?php

namespace Armincms\Contract\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\MorphedByMany;
use Laravel\Nova\Http\Requests\NovaRequest;
use Zareismail\NovaPolicy\Nova\Role as Resource;

class Role extends Resource
{
    use Localization;

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'ACL';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Contract\Models\PolicyRole::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return array_merge(parent::fields($request), [
            MorphedByMany::make(__('Admins'), 'admins', Admin::class),
            MorphedByMany::make(__('Users'), 'users', User::class),
        ]);
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->withCount('users', 'admins');
    }

    /**
     * Determine if the current user can delete the given resource.
     *
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        return parent::authorizedToDelete($request) && ($this->count_users + $this->count_admins = 0
        );
    }
}
