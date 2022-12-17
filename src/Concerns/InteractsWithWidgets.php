<?php

namespace Armincms\Contract\Concerns;

trait InteractsWithWidgets
{
    /**
     * Serialize the model to pass into the client view.
     *
     * @param Zareismail\Cypress\Request\CypressRequest
     * @return array
     */
    public function serializeForWidget($request, $detail = true): array
    {
        return $detail
            ? $this->serializeForDetailWidget($request)
            : $this->serializeForIndexWidget($request);
    }

    /**
     * Serialize the model to pass into the client view for single item.
     *
     * @param Zareismail\Cypress\Request\CypressRequest
     * @return array
     */
    public function serializeForDetailWidget($request)
    {
        return $this->toArray();
    }

    /**
     * Serialize the model to pass into the client view for collection of items.
     *
     * @param Zareismail\Cypress\Request\CypressRequest
     * @return array
     */
    public function serializeForIndexWidget($request)
    {
        return $this->toArray();
    }
}
