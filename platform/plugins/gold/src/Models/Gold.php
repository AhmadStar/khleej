<?php

namespace Botble\Gold\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

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
}
