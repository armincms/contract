<?php

namespace Armincms\Contract\Cypress\Fragments;

use Armincms\Papyrus\Cypress\Fragments\PapyrusPage as Fragment;

class Page extends Fragment
{
    /**
     * Get the resource Model class.
     */
    public function model(): string
    {
        return \Armincms\Contract\Models\Page::class;
    }
}
