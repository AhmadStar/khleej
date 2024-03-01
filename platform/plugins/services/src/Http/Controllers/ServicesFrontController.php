<?php

namespace Botble\Services\Http\Controllers;

use Botble\Services\Repositories\Interfaces\ServicesInterface;
use Botble\Base\Http\Controllers\BaseController;
use Theme;
use Botble\Services\Tables\ServicesTable;
use Botble\SeoHelper\SeoMeta;
use Botble\SeoHelper\Entities\MiscTags;
use SeoHelper;
use Botble\SeoHelper\SeoOpenGraph;
use RvMedia;

class ServicesFrontController extends BaseController
{
    /**
     * @var ServicesInterface
     */
    protected $servicesRepository;

    /**
     * @param ServicesInterface $servicesRepository
     */
    public function __construct(ServicesInterface $servicesRepository)
    {
        $this->servicesRepository = $servicesRepository;
    }

    /**
     * @param ServicesTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function view($slug)
    {
        page_title()->setTitle(trans('plugins/services::services.name'));

        Theme::asset()
            ->usePath(false)
            ->add('vue-js', asset('/themes/martfury/js/vue.js'), [], [], '1.0.0')
            ->add('vue-js', asset('/themes/martfury/js/services-vue.js'), [], [], '1.0.0');

        $service = app(ServicesInterface::class)->getModel()
            ->where('slug',$slug)
            ->first();

        $services = app(ServicesInterface::class)->getModel()->get();


        if($service){
            SeoHelper::setTitle($service->slug)
            ->setDescription(substr($service->summary, 0, 155));

            $meta = new SeoOpenGraph;
            if ($service->image) {
                $meta->setImage(RvMedia::getImageUrl($service->image, 'medium'));
            }
            $meta->setDescription(substr($service->summary, 0, 155));
            $meta->setUrl('https://alkhaleej.services/service/' . $service->slug);
            $meta->setTitle($service->slug);
            $meta->setType('Service');

            // canonical
            $seometa = new SeoMeta;
            $canonical = new MiscTags;
            $canonical->setUrl('https://alkhaleej.services/service/' . $service->slug);
            $seometa->misc($canonical);
            $seometa->setTitle($service->name);
            $seometa->setDescription($service->summary);
            // $seometa->addMeta('robots', 'noindex,nofollow');

            SeoHelper::setSeoMeta($seometa);

            SeoHelper::setSeoOpenGraph($meta);
        }

        return \Theme::layout('new-layout')->scope('service', ['service' => $service,'services' => $services])->render();

    }


}
