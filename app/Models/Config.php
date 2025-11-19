<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class Config extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = ['flag_type', 'name','flag_value', 'has_image', 'is_config'];
    public function sluggable(): array
    {
        return [
            'flag_type' => [
                'source' => 'name',
                'separator' => '_'
            ]
        ];
    }

}
