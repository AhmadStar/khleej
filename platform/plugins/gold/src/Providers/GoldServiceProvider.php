<?php

namespace Botble\Gold\Providers;

use Botble\Gold\Models\Gold;
use Illuminate\Support\ServiceProvider;
use Botble\Gold\Repositories\Caches\GoldCacheDecorator;
use Botble\Gold\Repositories\Eloquent\GoldRepository;
use Botble\Gold\Repositories\Interfaces\GoldInterface;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class GoldServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(GoldInterface::class, function () {
            return new GoldCacheDecorator(new GoldRepository(new Gold));
        });

        $this->setNamespace('plugins/gold')->loadHelpers();
    }

    public function boot()
    {
        $this
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes(['web']);

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            if (defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
                // Use language v2
                \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Gold::class, [
                    'name',
                ]);
            } else {
                // Use language v1
                $this->app->booted(function () {
                    \Language::registerModule([Gold::class]);
                });
            }
        }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-gold',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/gold::gold.name',
                'icon'        => 'fa fa-list',
                'url'         => route('gold.index'),
                'permissions' => ['gold.index'],
            ]);
        });
    }
}
