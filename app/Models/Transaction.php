<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function Source()
    {
        return $this->belongsTo(User::class, 'source_id', 'id');
    }
}
