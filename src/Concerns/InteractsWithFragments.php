<?php

namespace Armincms\Contract\Concerns;

use Zareismail\Gutenberg\Models\GutenbergFragment;
 
trait InteractsWithFragments  
{    
    /**
     * Get the availabel url addresses.
     *  
     * @return array        
     */
    public function urls()
    {
        return $this->fragments()->map(function($fragment) {
            return [
                'name' => $fragment->name,
                'url'  => $fragment->getUrl($this->getUri()),
            ];
        });
    }

    /**
     * Get the attachaed fragments.
     * 
     * @return \Illuminate\Support\Collection
     */
    public function fragments()
    {
        return once(function() {
            return GutenbergFragment::activated()
                    ->fragments((array) $this->cypressFragment())
                    ->with('website')
                    ->get();
        });
    }

    /**
     * Get the corresponding cypress fragment.
     * 
     * @return 
     */
    abstract public function cypressFragment(): string;
}
