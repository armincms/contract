<?php

namespace Armincms\Contract\Concerns;

use Zareismail\Gutenberg\Gutenberg;
 
trait InteractsWithFragments  
{   
    /**
     * Get the url for the given request.
     * 
     * @param \Zareismail\Cypress\Http\Requests\CypressRequest $request 
     * @return string         
     */
    public function getUrl($request)
    { 
        $website = $request->resolveComponent()->website();
        $fragment = $website->fragments->firstWhere('fragment', $this->cypressFragment());

        return $fragment ? $fragment->getUrl($this->getUri()) : null;
    }

    /**
     * Get the availabel url addresses.
     *  
     * @return array        
     */
    public function urls()
    {
        return $this->fragments()->map(function($fragment) {
            return [
                'name'      => $fragment->name,
                'url'       => $fragment->getUrl($this->getUri()),
                'website'   => $fragment->website->name,
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
        return Gutenberg::cachedFragments()
            ->where('fragment', $this->cypressFragment())                    
            ->loadMissing('website');
    }

    /**
     * Get the corresponding cypress fragment.
     * 
     * @return 
     */
    abstract public function cypressFragment(): string;
}
