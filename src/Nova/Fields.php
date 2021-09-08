<?php

namespace Armincms\Contract\Nova; 

use DmitryBubyakin\NovaMedialibraryField\Fields\Medialibrary;

trait Fields  
{   
    /**
     * Create new Medialibrary field.
     * 
     * @param  string $name       
     * @param  string $collection 
     * @return \Laravle\Nova\Fields\Field             
     */
    public function medialibrary($name, $collection = 'image')
    {
        return Medialibrary::make(__($name), $collection)->autouploading();
    }
}
