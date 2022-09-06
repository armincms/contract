<?php

namespace Armincms\Contract\Media;

use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;

class PathGenerator extends DefaultPathGenerator
{
    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(SpatieMedia $media): string
    {
        return $media->created_at->format('Y/m/d').'/'.parent::getBasePath($media);
    }
}
