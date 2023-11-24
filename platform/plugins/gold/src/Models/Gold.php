<?php

namespace Botble\Gold\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Blog\Models\Post;
use Botble\Slug\SlugHelper;
use Botble\Slug\Models\Slug;
use GuzzleHttp\Client;
use RvMedia;

class Gold extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gold';

    /**
     * @var array
     */
    protected $fillable = [
        'ounce',
        'chart',
        'country',
        '24_karat',
        'label1',
        'label2',
        '22_karat',
        '21_karat',
        '18_karat',
        '14_karat',
        'html_table',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    public static function GoldPriceSync($country = 'Turkey', $path = 'https://www.150currency.com/ar/gold-rates-by-TRY.htm')
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
            if (isset($result['data'])) {
                $url = $result['data']->url;
                // Load the original image
                $image = imagecreatefrompng(asset('/storage/' . $url));
                $sourceImage = imagecreatefromjpeg($image); // Replace 'original.jpg' with your image file
            }


//            // Load the watermark image
//            $watermark = imagecreatefrompng(asset('/storage/watermark.png')); // Replace 'watermark.png' with your watermark image file
//
//            // Get the dimensions of the source image and watermark
//            $sourceWidth = imagesx($sourceImage);
//            $sourceHeight = imagesy($sourceImage);
//            $watermarkWidth = imagesx($watermark);
//            $watermarkHeight = imagesy($watermark);
//
//            $margin = 10; // Adjust the margin as needed
//            $destX = $margin;
//            $destY = $margin;
//
//            // Merge the watermark onto the source image
//            imagecopy($sourceImage, $watermark, $destX, $destY, 0, 0, $watermarkWidth, $watermarkHeight);
//
//            // Output or save the watermarked image
//            header('Content-Type: image/jpeg'); // Set the appropriate header for the image type
//            imagejpeg($sourceImage, 'output.jpg'); // Replace 'output.jpg' with the desired output file path
//
//            // Clean up resources
//            imagedestroy($sourceImage);
//            imagedestroy($watermark);


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
    }

    public static function updateGoldPost($id, $country)
    {
        $countries = [
            'Turkey' => 'تركيا',
            'Saudi Arabia' => 'السعودية',
            'Emarat' => 'الأمارات',
            'Egypt' => 'مصر',
        ];
        $fmt = new \IntlDateFormatter('ar', null, null);
        $fmt->setPattern('cccc, dd. MMMM YYYY');
        $date = strtotime(date('Y-m-d'));

        $title = ' سعر الذهب   ' . $countries[$country];
        $title.=  '   '.$fmt->format($date).'   ';
        $post = Post::where(['id' => $id])->first();

        $gold = Gold::where(['status' => 'published', 'country' => $country])->orderBy('created_at', 'DESC')->first();
        if ($gold)
            $goldsRecords = Gold::where(['status' => 'published', 'country' => $country])->where('id', '!=', $gold->id)->orderBy('created_at', 'DESC')->limit(10)->get();
        $content = $gold->getCurrentGoldPrice() . (Gold::getArchiveGoldPrice($goldsRecords, $gold));
//       // dd($post);
        if (!$post) {
            $post = Post::create(
                [
                    'id' => $id,
                    'name' => $title,
                    'image' => 'news/سعر-الذهب-اليوم.jpg',
                    'content' => $content,
                    'status' => BaseStatusEnum::PUBLISHED,
                    'author_id' => 1
                ]
            );
            Slug::create([
                'reference_type' => 'Botble\Blog\Models\Post',
                'reference_id' => $post->id,
                'key' => 'gold-rates-in-' . $country,
                'prefix' => 'blog',
            ]);



            if($country=='Turkey') $post->categories()->attach([36,5]);
            if($country=='Emarat') $post->categories()->attach([36,39,5]);
            if($country=='Egypt') $post->categories()->attach([36,5]);
            if($country=='Saudi Arabia') $post->categories()->attach([36,37,5]);
        } else {
            $post->name = $title;
            $post->content = $content;
            $post->save();


        }
        //dd($post);

    }

    private function getCurrentGoldPrice()
    {
        $gold = $this;
        //dd( $gold);
        return view('plugins/gold::gold-view', compact('gold'));
    }

    public static function getArchiveGoldPrice($goldsRecords, $gold)
    {


        return view('plugins/gold::gold-view-archive', compact('goldsRecords', 'gold'));
    }

}
