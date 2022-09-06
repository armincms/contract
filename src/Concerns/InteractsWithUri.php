<?php

namespace Armincms\Contract\Concerns;

use Armincms\Contract\Casts\Url;

trait InteractsWithUri
{
    /**
     * Bootstrap the model instance.
     *
     * @return
     */
    public static function bootInteractsWithUri()
    {
        static::saved(function ($model) {
            $model->ensureUriGenerated();
        });
    }

    /**
     * Initialize the model instance.
     *
     * @return
     */
    public function initializeInteractsWithUri()
    {
        $this->casts = array_merge([$this->getUriName() => Url::class], (array) $this->casts);
    }

    /**
     * Fill teh uri column if not.
     *
     * @return void
     */
    public function ensureUriGenerated()
    {
        ! empty($this->getUri()) || $this->fillUri()->save();
    }

    /**
     * Fill the uri with new string.
     *
     * @return this
     */
    public function fillUri()
    {
        return $this->forceFill([
            $this->getUriName() => $this->generateUri(),
        ]);
    }

    /**
     * Generate new uri string.
     *
     * @return [type] [description]
     */
    public function generateUri()
    {
        $parameters = collect($this->getUriGenerators())->map(function ($generator) {
            return $generator($this);
        });

        return (string) $parameters->implode('/') ?: time();
    }

    /**
     * Get the url generator callbacks.
     *
     * @return array
     */
    public function getUriGenerators()
    {
        return [
            function ($model) {
                return $model->slug;
            },
        ];
    }

    /**
     * Get the uri value.
     *
     * @return string
     */
    public function getUri()
    {
        $uriName = $this->getUriName();

        return $this->{$uriName};
    }

    /**
     * Find a model by its uri.
     *
     * @param  string  $uri
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function findByUri($uri, $columns = ['*'])
    {
        return $this->withUri(urlencode($uri))->first($columns);
    }

    /**
     * Query where has the given uri string.
     *
     * @param  string  $uri
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithUri($query, $uri)
    {
        return $query->where($this->getQualifiedUriName(), urlencode($uri));
    }

    /**
     * Get the table qualified uri name.
     *
     * @return string
     */
    public function getQualifiedUriName()
    {
        return $this->qualifyColumn($this->getUriName());
    }

    /**
     * Get the uri for the model.
     *
     * @return string
     */
    public function getUriName()
    {
        return 'uri';
    }
}
