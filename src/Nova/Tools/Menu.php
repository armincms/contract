<?php

namespace Armincms\Contract\Nova\Tools;

use Illuminate\Http\Request;
use Outl1ne\MenuBuilder\MenuBuilder;

class Menu extends MenuBuilder
{
    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
    }

    public function menu(Request $request)
    {
    }
}
