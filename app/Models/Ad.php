<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
        'publish_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}