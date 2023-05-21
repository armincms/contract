<?php

namespace Armincms\Contract\Concerns;

use Armincms\Contract\Contracts\HasMeta;
use Armincms\Contract\Contracts\Hitsable;
use Illuminate\Support\Str;
use Laravel\Nova\Nova;

trait InteractsWithModel
{
    /**
     * The resource instance.
     */
    protected $resource;

    /**
     * Resolve the resoruce's value for the given request.
     *
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest  $request
     * @return void
     */
    public function resolve($request): bool
    {
        if ($request->isComponentRequest()) {
            return false;
        }

        if (is_null($resourceUri = $this->resourceUri($request))) {
            return false;
        }

        if (is_null($this->resource = $this->findModelByUri($request, $resourceUri))) {
            return false;
        }

        if ($this->resource instanceof Hitsable) {
            $this->resource->viewed();
        }

        if ($this->resource instanceof HasMeta) {
            $this->withMeta([
                'meta' => collect($this->resource->meta)->toArray(),
            ]);
        }

        return true;
    }

    /**
     * Get resource key from request.
     *
     * @param  [type] $request
     * @return string
     */
    public function resourceUri($request)
    {
        if ($this->fallback()) {
            return trim($request->route('fragment'));
        }

        return trim(Str::after($request->route('fragment'), $this->uriKey()), '/');
    }

    /**
     * Find model by given uri.
     *
     * @param  string  $resourceUri
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findModelByUri($request, $resourceUri)
    {
        return $this->newQuery($request)->withUri($resourceUri)->first();
    }

    /**
     * Get a new query builder for the underlying model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery($request)
    {
        return $this->newModel()->newQuery()->tap(function ($query) use ($request) {
            return $this->applyQuery($request, $query);
        });
    }

    /**
     * Get a fresh instance of the model represented by the resource.
     *
     * @return mixed
     */
    public function newModel()
    {
        $model = $this->model();

        return new $model;
    }

    /**
     * Get the resource Model class.
     */
    abstract public function model(): string;

    /**
     * Apply custom query to the given query.
     *
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyQuery($request, $query)
    {
        return $query;
    }

    /**
     * The resource title.
     *
     * @return void
     */
    public function title()
    {
        return $this->metaValue('resource.name', $this->metaValue('meta.title'));
    }

    /**
     * The resource description.
     *
     * @return void
     */
    public function description()
    {
        return $this->metaValue('resource.summary', $this->metaValue('meta.description'));
    }

    /**
     * The resource author.
     *
     * @return void
     */
    public function author()
    {
        return $this->metaValue('meta.author');
    }

    /**
     * The resource tags.
     *
     * @return void
     */
    public function tags()
    {
        return $this->metaValue('meta.tags');
    }

    /**
     * Get possible places for widget.
     */
    public function displayOptions(): array
    {
        if (is_null($resource = Nova::resourceForModel($this->model()))) {
            return [];
        }

        return $resource::newModel()->get()->keyBy([$this, 'widgetFilterKey'])->mapInto($resource)->map->title()->toArray();
    }

    /**
     * Filter relatable widgets on fragment page.
     *
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $widget
     */
    public function filterRelatableWidget($request, $widget): bool
    {
        $key = $this->widgetFilterKey($this->metaValue('resource'));

        return collect($widget->pivot?->config?->get('hide_on'))->doesntContain($key) &&
            with(collect($widget->pivot?->config?->get('display_on'))->filter(), fn ($displays) => $displays->isEmpty() || $displays->contains($key));
    }

    /**
     * Get key for widget filtering.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $widget
     */
    public function widgetFilterKey($model): string
    {
        return "{$this->uriKey()}->{$model->getKey()}";
    }

    /**
     * Get additional meta value for the given key.
     *
     * @param  mixed  $default
     * @return mixed
     */
    public function metaValue(string $key, $default = null)
    {
        if ($key === 'resource') {
            return $this->resource();
        }

        if (Str::startsWith($key, 'resource.')) {
            return data_get($this->resource(), Str::after($key, 'resource.'), $default);
        }

        return parent::metaValue($key, $default);
    }

    /**
     * Get the resource instance.
     */
    public function resource()
    {
        return $this->resource;
    }
}
