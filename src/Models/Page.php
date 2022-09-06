<?php

namespace Armincms\Contract\Models;

use Armincms\Papyrus\Models\PapyrusPage as Model;

class Page extends Model
{
    /**
     * Get the corresponding cypress fragment.
     *
     * @return
     */
    public function cypressFragment(): string
    {
        return \Armincms\Contract\Cypress\Fragments\Page::class;
    }

    /**
     * Get scoped resource name.
     *
     * @return string
     */
    public static function resourceName(): string
    {
        return \Armincms\Contract\Nova\Page::class;
    }
}
