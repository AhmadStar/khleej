<?php

namespace Botble\Services\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Services\Http\Requests\ServicesRequest;
use Botble\Services\Repositories\Interfaces\ServicesInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Services\Tables\ServicesTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Services\Forms\ServicesForm;
use Botble\Base\Forms\FormBuilder;

class ServicesController extends BaseController
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
    public function index(ServicesTable $table)
    {
        page_title()->setTitle(trans('plugins/services::services.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/services::services.create'));

        return $formBuilder->create(ServicesForm::class)->renderForm();
    }

    /**
     * @param ServicesRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(ServicesRequest $request, BaseHttpResponse $response)
    {
        $services = $this->servicesRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(SERVICES_MODULE_SCREEN_NAME, $request, $services));

        return $response
            ->setPreviousUrl(route('services.index'))
            ->setNextUrl(route('services.edit', $services->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $services = $this->servicesRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $services));

        page_title()->setTitle(trans('plugins/services::services.edit') . ' "' . $services->name . '"');

        return $formBuilder->create(ServicesForm::class, ['model' => $services])->renderForm();
    }

    /**
     * @param $id
     * @param ServicesRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, ServicesRequest $request, BaseHttpResponse $response)
    {
        $services = $this->servicesRepository->findOrFail($id);

        $services->fill($request->input());

        $this->servicesRepository->createOrUpdate($services);

        event(new UpdatedContentEvent(SERVICES_MODULE_SCREEN_NAME, $request, $services));

        return $response
            ->setPreviousUrl(route('services.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $services = $this->servicesRepository->findOrFail($id);

            $this->servicesRepository->delete($services);

            event(new DeletedContentEvent(SERVICES_MODULE_SCREEN_NAME, $request, $services));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $services = $this->servicesRepository->findOrFail($id);
            $this->servicesRepository->delete($services);
            event(new DeletedContentEvent(SERVICES_MODULE_SCREEN_NAME, $request, $services));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
