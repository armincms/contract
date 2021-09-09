<?php

namespace Armincms\Contract\Contracts;
 
interface HasMedia extends \Spatie\MediaLibrary\HasMedia  
{  
    /**
     * Get the available media collections.
     * 
     * @return array
     */
    public function getMediaCollections(): array;
}
