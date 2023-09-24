<?php

namespace Botble\Gold\Models;

use Botble\Base\Models\BaseModel;

class GoldTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gold_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'gold_id',
        'name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
