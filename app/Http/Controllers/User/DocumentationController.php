<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    public function webhookNews()
    {
        return view('user.documentations.webhooks.news');
    }
}
