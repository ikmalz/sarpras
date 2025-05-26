<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class Categories extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'name'];
    
    public function items() {
        return $this->hasMany(Item::class, 'category_id');
    }
}
