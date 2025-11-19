<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'category_id',
        'image',
        'images',
        'discount',
        'slug',
        'short_desc',
        'description',
        'featured',
        'status',
        'stock'
    ];

    protected $casts = [
        'images' => 'array',
        'featured' => 'boolean',
        'status' => 'boolean',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'stock' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getFinalPriceAttribute()
    {
        $discountAmount = ($this->price * $this->discount) / 100;
        return $this->price - $discountAmount;
    }
}