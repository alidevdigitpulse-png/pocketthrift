<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    protected $fillable = ['store_id', 'social_app_id', 'link', 'sort'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function socialApp()
    {
        return $this->belongsTo(SocialApp::class);
    }
}
