<?php

namespace Botble\Services\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Projects\Models\Projects;

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

    
}
