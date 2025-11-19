<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    use LogsActivity, Sluggable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pages';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'image', 'region_id', 'status', 'slug'];

    /**
     * Change activity log event description
     *
     * @param string $eventName
     *
     * @return string
     */

     public function sections(){
        return $this->hasMany(Section::class);
     }

     public function region()
     {
         return $this->belongsTo(Region::class);
     }

    public function getDescriptionForEvent($eventName)
    {
        return __CLASS__ . " model has been {$eventName}";
    }
    public function findSection($slug)
    {
        $section = $this->sections()->where('slug', $slug)->first();
        return $section ? $section->value : null;
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}