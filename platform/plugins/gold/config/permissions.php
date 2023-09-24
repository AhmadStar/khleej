<?php

return [
    [
        'name' => 'Gold',
        'flag' => 'gold.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'gold.create',
        'parent_flag' => 'gold.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'gold.edit',
        'parent_flag' => 'gold.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'gold.destroy',
        'parent_flag' => 'gold.index',
    ],
];
