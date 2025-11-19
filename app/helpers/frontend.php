<?php

namespace App\Helpers\Frontend;

use Illuminate\Support\Str;
use App\Models\Config;

class Helper
{
    public function __call($method, $arguments)
    {
        $config = Config::where('flag_type', Str::snake($method))->first();

        if ($config) {
            return $config->flag_value;
        }

        return null;
    }
}
