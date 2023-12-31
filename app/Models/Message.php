<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    use HasFactory;

    public $fillable = ['user_id', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
