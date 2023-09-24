<?php

namespace Botble\Gold;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('gold');
        Schema::dropIfExists('gold_translations');
    }
}
