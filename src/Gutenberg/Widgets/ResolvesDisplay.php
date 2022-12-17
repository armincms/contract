<?php

namespace Armincms\Contract\Gutenberg\Widgets;

trait ResolvesDisplay
{
    /**
     * The callback to be used to resolve the resourc's display.
     *
     * @var \Closure
     */
    public $displayResourceCallback = [];

    /**
     * Define the callback that should be used to display the resources.
     *
     * @param  callable  $displayResourceCallback
     * @param  string  $resource
     * @return $this
     */
    public function displayResourceUsing(callable $displayResourceCallback, string $resource = null)
    {
        $this->displayResourceCallback[$resource] = $displayResourceCallback;

        return $this;
    }

    /**
     * Display resource for the given attributes.
     *
     * @param  array  $attributes
     * @param  string  $resource
     * @return string
     */
    public function displayResource(array $attributes, string $resource = null)
    {
        return isset($this->displayResourceCallback[$resource])
            ? call_user_func($this->displayResourceCallback[$resource], $attributes)
            : '';
    }
}
