<?php

namespace Armincms\Contract\Cypress\Plugins;

use Armincms\Contract\Contracts\Resource;
use Zareismail\Cypress\Http\Requests\CypressRequest;
use Zareismail\Cypress\Plugin;

class SmartMeta extends Plugin
{
    /**
     * Bootstrap the resource for the given request.
     *
     * @param  \Zareismail\Cypress\Layout  $layout
     * @return void
     */
    public function boot(CypressRequest $request, $layout)
    {
        $website = $request->resolveComponent()->website();
        $title = $website->name;
        $description = $website->description;
        $tags = [$website->name];

        if (! $request->isFragmentRequest()) {
            $title .= ' | '.$website->title;
        } else {
            $title .= ' | '.$request->resolveFragment()->fragment()->name;
            $tags[] = $request->resolveFragment()->fragment()->name;
        }

        if ($request->isFragmentRequest() && $request->resolveFragment() instanceof Resource) {
            $metaTitle = $request->resolveFragment()->title();
            $title .= ($metaTitle ? ' | '.$metaTitle : '');
            $description = $request->resolveFragment()->description() ?: $description;
            $tags = array_merge($tags, (array) $request->resolveFragment()->tags());
        }

        $this->withMeta(compact('title', 'description', 'tags'));
    }

    /**
     * Determine if the plugin should be loaded as html meta.
     */
    public function asMetadata(): bool
    {
        return true;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        return '<title>'.$this->metaValue('title').'</title>'.
               '<meta name="title" content="'.$this->metaValue('title').'">'.
               '<meta name="description" content="'.$this->metaValue('description').'">'.
               '<meta name="tags" content="'.implode(',', $this->metaValue('tags')).'">';
    }
}
