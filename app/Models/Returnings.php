<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returnings extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrowing_id',
        'returned_quantity',
        'is_confirmed',
        'handled_by',
        'image',
        'description'
    ];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
