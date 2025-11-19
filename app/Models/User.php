<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function wishlist()
    {
        return $this->belongsToMany(User::class,'wishlists', 'user_id', 'product_id');
    }

    public function regions()
    {
        return $this->belongsToMany(Region::class, 'user_regions', 'user_id', 'region_id');
    }

    public function getAssignedRegionCodesAttribute()
    {
        if ($this->assigned_regions) {
            // If it's comma-separated (for backward compatibility) or single region
            if (strpos($this->assigned_regions, ',') !== false) {
                return explode(',', $this->assigned_regions);
            } else {
                // Single region
                return [$this->assigned_regions];
            }
        }
        return [];
    }
}
