<?php

namespace Armincms\Contract\Concerns;

trait Sluggable
{
    use \Cviebrock\EloquentSluggable\Sluggable {
        scopeFindSimilarSlugs as scopeSimilarSlugs;
    }

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug',
        ];
    }

    /**
     * Query scope for finding "similar" slugs, used to determine uniqueness.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindSimilarSlugs($query, string $attribute, array $config, string $slug)
    {
        $query = call_user_func_array([$this, 'scopeSimilarSlugs'], func_get_args());

        return $query->when(method_exists($this, 'scopeHasLocale'), function ($query) {
            $query->hasLocale((array) $this->getLocale());
        });
    }
}
