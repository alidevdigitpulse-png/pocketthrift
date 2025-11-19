<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class RegionScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // Only apply scope if a region is set and the model has the country_codes column
        if (app()->has('current_region') && \Schema::hasColumn($model->getTable(), 'country_codes')) {
            $region = app('current_region');

            if ($region) {
                // Assumes country_codes is a comma-separated string like 'us,ca,it'
                $builder->where(function ($query) use ($region) {
                    $query->where('country_codes', 'LIKE', '%' . $region->code . '%')
                          ->orWhereNull('country_codes') // Include content with no region specified
                          ->orWhere('country_codes', '');
                });
            }
        }
    }
}
