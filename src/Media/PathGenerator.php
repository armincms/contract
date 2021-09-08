<?php

namespace Armincms\Contract\Media;

use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;  
use Spatie\MediaLibrary\Models\Media; 

class PathGenerator extends DefaultPathGenerator
{   
    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    { 
        return parent::getBasePath($media).'/'.$media->created_at->format('yN/md');
    } 
}
