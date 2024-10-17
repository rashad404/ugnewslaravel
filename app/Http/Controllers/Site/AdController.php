<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Ad;

class AdController extends Controller
{
    public function click($id)
    {
        // Retrieve the ad item without incrementing the view count
        $adItem = Ad::getItem(false);
    
        // Update the click count for the given ad ID
        $update = Ad::updateClick($id);
    
        // If the click count was successfully updated, redirect to the ad's link
        if ($update && isset($adItem->link)) {
            return redirect()->away($adItem->link);
        }
    
        // Optionally, handle the case where the ad item is not found or no redirect is necessary
        return redirect()->back()->with('error', __('Ad not found or cannot be redirected'));
    }
    
}