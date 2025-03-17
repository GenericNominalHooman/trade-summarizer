<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Note extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'summary',
        'note',
        'user_id',
        'chart_image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
