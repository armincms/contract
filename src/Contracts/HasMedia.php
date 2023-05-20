<?php

namespace Armincms\Contract\Contracts;

interface HasMedia extends \Spatie\MediaLibrary\HasMedia
{
    /**
     * Get the available media collections.
     */
    public function getMediaCollections(): array;
}
