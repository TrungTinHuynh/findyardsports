<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Yard extends Model
{
    use HasFactory;
    protected $table = 'yards';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'price',
        'img',
        'view',
        'total_booking',
        'address',
        'description',
    ];
    public function district()
    {
        return $this->belongsToMany(District::class);
    }
    protected static function boot()
    {
        parent::boot();

        static::created(function ($yard) {

            $yard->slug = $yard->createSlug($yard->name);

            $yard->save();
        });
    }
    private function createSlug($name)
    {
        if (static::whereSlug($slug = Str::slug($name))->exists()) {

            $max = static::whereName($name)->latest('id')->skip(1)->value('slug');

            if (isset($max[-1]) && is_numeric($max[-1])) {

                return preg_replace_callback('/(\d+)$/', function ($mathces) {

                    return $mathces[1] + 1;
                }, $max);
            }
            return "{$slug}-2";
        }
        return $slug;
    }
}