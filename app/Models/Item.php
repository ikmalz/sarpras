<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'stock', 'image_url', 'category_id'];
    protected $appends = ['image_url'];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->attributes['image_url']);
    }
}
