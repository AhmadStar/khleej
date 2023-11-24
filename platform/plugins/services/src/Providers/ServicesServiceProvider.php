<?php

namespace Botble\Services\Providers;

use Botble\Services\Models\Services;
use Illuminate\Support\ServiceProvider;
use Botble\Services\Repositories\Caches\ServicesCacheDecorator;
use Botble\Services\Repositories\Eloquent\ServicesRepository;
use Botble\Services\Repositories\Interfaces\ServicesInterface;
use Botble\Base\Supports\Helper;
use Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class ServicesServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(ServicesInterface::class, function () {
            return new ServicesCacheDecorator(new ServicesRepository(new Services));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/services')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web']);

        Event::listen(RouteMatched::class, function () {
            if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
                \Language::registerModule([Services::class]);
                \SeoHelper::registerModule([Services::class]);
            }

            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-services',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/services::services.name',
                'icon'        => 'fa fa-sitemap ',
                'url'         => route('services.index'),
                'permissions' => ['services.index'],
            ]);
        });
    }
}
