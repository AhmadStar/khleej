<?php

namespace Botble\Services\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Projects\Models\Projects;
use Illuminate\Support\Str;
use Botble\Slug\Models\Slug;

class Services extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'services';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'image',
        'icon',
        'slug',
        'color',
        'content',
        'summary',
        'status',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
    public static function getServices(){
        return Services::select('*')
            ->join('language_meta', function ($join) {
                $join->on('language_meta.reference_id', '=', 'services.id');
            })
            ->where([
                'language_meta.reference_type' => Services::class,
                'language_meta.lang_meta_code' => (app()->getLocale()=='en')?'en_US':'ar',
                'services.status' => 'published'])->orderBy('services.created_at', 'DESC')->get();

    }
    public static function getAllLangServices(){
        return Services::select('*')

            ->where([
                'services.status' => 'published'])->orderBy('services.created_at', 'DESC')->get();

    }
    public static function get_services_choices()
    {
        return Services::where(['status' => 'published'])->pluck('name', 'id')->toArray();
    }
    public function projects()
    {
        //return $this->belongsToMany(Services::class, 'project_services');
        return $this->belongsToMany(Projects::class, 'project_services','service_id','project_id');
        //return $this->belongsToMany(Services::class, 'foreign_id', 'id')->orderBy('rank');
    }


    public function createSlug()
    {
        $slug = Str::slug($this->name, '-',false);
        $index = 1;
        $baseSlug = $slug;
        while (Services::where('slug', $slug)->where('id', '!=', $this->id)->count() > 0) {
            $slug = $baseSlug . '-' . $index++;
        }

        if (empty($slug)) {
            $slug = time();
        }

        $this->slug=$slug;
        $this->save();
        return $slug;
    }


}
