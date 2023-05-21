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
        $description = $website->description;
        $tags = [$website->name];

        $title = collect([$website->name])
            ->when(
                $request->isFragmentRequest(),
                function ($titles) use ($request, $description, $tags) {
                    $fragment = $request->resolveFragment();
                    $this->withMeta(['meta' => $fragment->metaValue('meta', [])]);

                    $titles->push($fragment->fragment()->name);

                    if ($fragment instanceof Resource) {
                        $titles->push($request->resolveFragment()->title());
                        $description = $request->resolveFragment()->description() ?: $description;
                        $tags = array_merge($tags, (array) $request->resolveFragment()->tags());
                    }
                },
                fn ($titles) => $titles->push($website->title)
            )
            ->implode((' | '));

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
               '<meta name="title" content="'.($this->metaValue('meta.title') ?: $this->metaValue('title')).'">'.
               '<meta name="description" content="'.($this->metaValue('meta.description') ?: $this->metaValue('description')).'">'.
               '<meta name="tags" content="'.implode(',', (array) ($this->metaValue('meta.tags') ?: $this->metaValue('tags'))).'">';
    }
}
