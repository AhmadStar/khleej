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

        $httpClient = new \GuzzleHttp\Client();
        $response = $httpClient->request('GET', $path, ['verify' => false]);
        $htmlString = (string)$response->getBody();
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML($htmlString);
        $xpath = new \DOMXPath($doc);

        $altin = $xpath->query("//*[contains(@class, 'rates')]");
        $intro = $xpath->query("//*[contains(@class, 'col-md-12')]//p");
        $chart = $xpath->query("//*[contains(@class, 'image-chart')]//img/@src");

        // dd($chart->item(0)->nodeValue);
        $label = [];
        foreach ($intro as $key => $row) {
            $label[] = $row->nodeValue;
        }

        $rdata = [];

        foreach ($altin as $key => $row) {
            $rowData = [];

            $columns = $row->getElementsByTagName('td');

            foreach ($columns as $column) {
                $rowData[] = trim($column->nodeValue);
            }

            $rdata[] = $rowData;
        }

        $rdata = $rdata[0];
        $url = $chart->item(0)->nodeValue;
        try {
            // Parse the URL to extract its components
            $urlParts = parse_url($url);

            // Check if the query component exists and remove it
            parse_str($urlParts['query'], $queryParameters);

            // Remove the query string component
            unset($urlParts['query']);

            // Rebuild the URL without the query string
            $cleanedUrl = $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'];
            //dd($cleanedUrl);


            $result = RvMedia::uploadFromUrl($cleanedUrl, 0);
            //dd($result);
            $url = $result['data']->url;
            // Load the original image
            $image = imagecreatefrompng(asset('/storage/'.$url));
            $sourceImage = imagecreatefromjpeg($image); // Replace 'original.jpg' with your image file

            // Load the watermark image
            $watermark = imagecreatefrompng(asset('/storage/watermark.png')); // Replace 'watermark.png' with your watermark image file

            // Get the dimensions of the source image and watermark
            $sourceWidth = imagesx($sourceImage);
            $sourceHeight = imagesy($sourceImage);
            $watermarkWidth = imagesx($watermark);
            $watermarkHeight = imagesy($watermark);

            $margin = 10; // Adjust the margin as needed
            $destX = $margin;
            $destY = $margin;

            // Merge the watermark onto the source image
            imagecopy($sourceImage, $watermark, $destX, $destY, 0, 0, $watermarkWidth, $watermarkHeight);

            // Output or save the watermarked image
            header('Content-Type: image/jpeg'); // Set the appropriate header for the image type
            imagejpeg($sourceImage, 'output.jpg'); // Replace 'output.jpg' with the desired output file path

            // Clean up resources
            imagedestroy($sourceImage);
            imagedestroy($watermark);


        } catch (Exception $exception) {

            dd($exception);
        }
        $gold = Gold::updateOrCreate([
            'country' => $country,
            'label1' => $label[0],
            'label2' => $label[2],
            'ounce' => $rdata[0],
            '24_karat' => $rdata[3],
            '22_karat' => $rdata[5],
            '21_karat' => $rdata[7],
            '18_karat' => $rdata[9],
            '14_karat' => $rdata[11],
            'html_table' => $doc->saveHTML($altin->item(0)),
            'chart' => $url,// $chart->item(0)->nodeValue,
        ]);

        dd($gold);

    }
}
