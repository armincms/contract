<?php

namespace Armincms\Contract\Concerns;

trait InteractsWithMeta
{
    /**
     * Bootstrap the model instance.
     *
     * @return
     */
    public function initializeInteractsWithMeta()
    {
        $this->casts = array_merge(['meta' => 'array'], (array) $this->casts);
    }

    /**
     * Get the meta attribute.
     *
     * @param  array  $meta
     * @return \Illuminate\Support\Collection
     */
    public function getMetaAttribute($meta = '[]')
    {
        return collect($this->defaultMeta())->merge(json_decode($meta, true));
    }

    /**
     * Retrive value from meta values for the give nkey.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function metaValue(string $key, $default = null)
    {
        return data_get($this->meta, $key, $default);
    }

    /**
     * Get the default meta datas.
     *
     * @return array
     */
    protected function defaultMeta(): array
    {
        return [
            'title' => null,
            'description' => null,
            'tags' => null,
        ];
    }
}
