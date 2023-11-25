<?php

namespace Botble\Services\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Services\Http\Requests\ServicesRequest;
use Botble\Services\Repositories\Interfaces\ServicesInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Theme;
use Botble\Services\Tables\ServicesTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Services\Forms\ServicesForm;
use Botble\Base\Forms\FormBuilder;

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

        $services = app(ServicesInterface::class)->getModel()
            ->get();
        return \Theme::layout('new-layout')->scope('service', ['service' => $service,'services' => $services])->render();
        //return view('plugins/services::service', ['results' => '', 'model' => '']);

        //return $table->renderTable();
    }


}
