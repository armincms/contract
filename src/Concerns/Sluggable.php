<?php

namespace Armincms\Contract\Concerns; 
 
trait Sluggable  
{ 
    use \Cviebrock\EloquentSluggable\Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug'
        ];
    }

    /**
     * Query scope for finding "similar" slugs, used to determine uniqueness.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $attribute
     * @param array $config
     * @param string $slug
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindSimilarSlugs($query, string $attribute, array $config, string $slug)
    {
        if (method_exists($this, 'scopeHasLocale')) {
            $query->hasLocale((array) $this->getLocale()); 
        }
    }
}
