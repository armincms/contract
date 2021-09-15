<?php

namespace Armincms\Contract\Concerns;
 
use Zareismail\Gutenberg\Models\GutenbergWidget;
 
trait InteractsWithWidgets  
{     
    /**
     * Serialize the model for pass into the client view.
     *
     * @param Zareismail\Cypress\Request\CypressRequest
     * @return array
     */
    public function serializeForWidget($request): array
    {
        return $this->toArray();
    }
}
