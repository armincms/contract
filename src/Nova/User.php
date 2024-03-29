<?php

namespace Armincms\Contract\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\PasswordConfirmation;
use Laravel\Nova\Fields\Tag;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;
use Zareismail\NovaPolicy\Nova\Permission;

class User extends Resource
{
    use Fields;
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
    public static $model = \Armincms\Contract\Models\User::class;

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
        'email', 'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Text::make(__('Username'), 'name')
                ->required()
                ->rules('required', 'unique:users,name,{{resourceId}}'),

            Text::make(__('Email'), 'email')
                ->required()
                ->rules('email', 'unique:users,email,{{resourceId}}')
                ->showWhenPeeking(),

            Tag::make(__('Roles'), 'roles', Role::class)
                ->showCreateRelationButton()
                ->searchable(false)
                ->required(),

            Tag::make(__('Permissions'), 'permissions', Permission::class)
                ->hideFromIndex()
                ->searchable(false),

            Password::make(__('Password'), 'password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:6')
                ->updateRules('nullable', 'string', 'min:6', 'confirmed'),

            PasswordConfirmation::make(__('Password Confirmation'), 'password_confirmation'),

            Panel::make(__('Profile'), [
                Text::make(__('Firstname'), 'metadata::firstname')
                    ->hideFromIndex()
                    ->showWhenPeeking(),

                Text::make(__('Lastname'), 'metadata::lastname')
                    ->hideFromIndex()
                    ->showWhenPeeking(),

                Text::make(__('Mobile Number'), 'metadata::mobile')
                    ->hideFromIndex()
                    ->showWhenPeeking(),

                Text::make(__('Phone Number'), 'metadata::phone')
                    ->hideFromIndex()
                    ->showWhenPeeking(),

                $this->medialibrary(__('User Avatar'), 'avatar'),
            ]),
        ];
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
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return trim($this->fullname()) ?: parent::title() ?: $this->email;
    }
}
