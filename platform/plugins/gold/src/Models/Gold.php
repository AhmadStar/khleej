<?php

namespace Botble\Gold\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Blog\Models\Post;
use Botble\Slug\SlugHelper;
use Botble\Slug\Models\Slug;

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

    public static function updateGoldPost($id,$country){
        $countries=[
        'Turkey'=>'تركيا',
        ];
        $fmt = new \IntlDateFormatter('ar', null, null);
        $fmt->setPattern('cccc, dd. MMMM YYYY');
        $date = strtotime(date('Y-m-d'));

       $title=  $fmt->format($date).' اليوم ،'.$countries[$country].''.'سعر الذهب في  ';
       $post= Post::where(['id'=>$id])->first();

       $gold= Gold::where(['status'=>'published','country'=>$country])->orderBy('created_at','DESC')->first();
        $goldsRecords=Gold::where(['status'=>'published','country'=>$country])->where('id','!=',$gold->id)->orderBy('created_at','DESC')->limit(10)->get();
        $content=$gold->getCurrentGoldPrice().(Gold::getArchiveGoldPrice($goldsRecords,$gold));

        if(!$post){
            $post=Post::create(
                [
                    'name'=>$title,
                    'content'=> $content,
                    'status'=>BaseStatusEnum::PUBLISHED,
                    'author_id'=>1
                ]
            );
            Slug::create([
                'reference_type' => 'Botble\Blog\Models\Post',
                'reference_id'   => $post->id,
                'key' =>'gold-rates-by-'.$country ,
                'prefix' => 'blog',
                ]);
        }

    }
    private function getCurrentGoldPrice(){
        $gold=$this;
        //dd( $gold);
        return view('plugins/gold::gold-view', compact('gold'));
    }
    public static function getArchiveGoldPrice($goldsRecords,$gold){

       
        return view('plugins/gold::gold-view-archive', compact('goldsRecords','gold'));
    }

}
