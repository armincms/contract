<?php

namespace Armincms\Contract\Concerns;

use Zareismail\Gutenberg\Gutenberg;

trait InteractsWithFragments
{
    /**
     * Append urls into attributes.
     *
     * @return void
     */
    public function initializeInteractsWithFragments()
    {
        $this->append('urls');
    }

    /**
     * Get url's as attribute.
     *
     * @return array
     */
    public function getUrlsAttribute()
    {
        return $this->urls();
    }

    /**
     * Get the url for the given request.
     *
     * @param  \Zareismail\Cypress\Http\Requests\CypressRequest  $request
     * @return string
     */
    public function getUrl($request)
    {
        return once(function () use ($request) {
            return optional($this->searchFragment($request))->getUrl($this->getUri());
        });
    }

    /**
     * Find fragment for given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function searchFragment($request)
    {
        $fragments = Gutenberg::cachedFragments()->forHandler($this->cypressFragment());
        $website = $request->resolveComponent()->website();

        return $fragments->firstWhere('website_id', $website->getKey())
            ?: $fragments->first();
    }

    /**
     * Get the availabel url addresses.
     *
     * @return array
     */
    public function urls()
    {
        return $this->fragments()->map(function ($fragment) {
            return [
                'name' => $fragment->name,
                'url' => $fragment->getUrl($this->getUri()),
                'website' => $fragment->website->name,
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
