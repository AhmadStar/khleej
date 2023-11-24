<?php

namespace Botble\Gold\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Gold\Http\Requests\GoldRequest;
use Botble\Gold\Repositories\Interfaces\GoldInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Gold\Tables\GoldTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Gold\Forms\GoldForm;
use Botble\Base\Forms\FormBuilder;
use GuzzleHttp\Client;
use Botble\Gold\Models\Gold;
use RvMedia;

class GoldController extends BaseController
{
    /**
     * @var GoldInterface
     */
    protected $goldRepository;

    /**
     * @param GoldInterface $goldRepository
     */
    public function __construct(GoldInterface $goldRepository)
    {
        $this->goldRepository = $goldRepository;
    }

    /**
     * @param GoldTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(GoldTable $table)
    {
        page_title()->setTitle(trans('plugins/gold::gold.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/gold::gold.create'));

        return $formBuilder->create(GoldForm::class)->renderForm();
    }

    /**
     * @param GoldRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(GoldRequest $request, BaseHttpResponse $response)
    {
        $gold = $this->goldRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(GOLD_MODULE_SCREEN_NAME, $request, $gold));

        return $response
            ->setPreviousUrl(route('gold.index'))
            ->setNextUrl(route('gold.edit', $gold->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $gold = $this->goldRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $gold));

        page_title()->setTitle(trans('plugins/gold::gold.edit') . ' "' . $gold->name . '"');

        return $formBuilder->create(GoldForm::class, ['model' => $gold])->renderForm();
    }

    /**
     * @param int $id
     * @param GoldRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, GoldRequest $request, BaseHttpResponse $response)
    {
        $gold = $this->goldRepository->findOrFail($id);

        $gold->fill($request->input());

        $gold = $this->goldRepository->createOrUpdate($gold);

        event(new UpdatedContentEvent(GOLD_MODULE_SCREEN_NAME, $request, $gold));

        return $response
            ->setPreviousUrl(route('gold.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $gold = $this->goldRepository->findOrFail($id);

            $this->goldRepository->delete($gold);

            event(new DeletedContentEvent(GOLD_MODULE_SCREEN_NAME, $request, $gold));

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
            $gold = $this->goldRepository->findOrFail($id);
            $this->goldRepository->delete($gold);
            event(new DeletedContentEvent(GOLD_MODULE_SCREEN_NAME, $request, $gold));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public static function UpdateProductPriceNew($country = 'Turkey', $path = 'https://www.150currency.com/ar/gold-rates-by-TRY.htm')
    {

        



    }
}
