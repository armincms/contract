<?php

namespace Armincms\Contract\Concerns;

use Armincms\Contract\Events\ResourceHitsEvent;

trait HasHits  
{  
    /**
     * Bootstrap the model instance.
     * 
     * @return 
     */
    public function initializeHasHits()
    {
        $this->casts = array_merge(['hits' => 'integer'], (array) $this->casts); 
    } 

    /**
     * Process the hits.
     * 
     * @return $this
     */
    public function viewed()
    {
        $this->incrementHits();
        $this->dispatchHitsEvent();

        return $this;
    }

    /**
     * Increase the model hits.
     * 
     * @return integer
     */
    public function incrementHits()
    {
        $this->increment('hits');

        return $this->hits;
    }

    /**
     * Dispatch hits event.
     * 
     * @return 
     */
    protected function dispatchHitsEvent()
    {
        ResourceHitsEvent::dispatch($this);

        return $this;
    }
}
