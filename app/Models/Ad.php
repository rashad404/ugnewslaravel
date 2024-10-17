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

    public static function getItem($count = true)
    {
        // Retrieve the item using Eloquent
        $item = self::where('status', 1)
            ->select(['id', 'title', 'text', 'link', 'thumb', 'image'])
            ->first();
    
        // Check if the view count should be incremented
        if ($item && $count) {
            $item->increment('view');
        }
    
        return $item;
    }

    public static function updateClick($id)
    {
        // Find the item by its ID and increment the click count
        self::where('id', $id)->increment('click');

        return true;
    }

}