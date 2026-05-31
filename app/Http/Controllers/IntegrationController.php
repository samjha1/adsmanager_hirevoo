<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Support\Facades\Auth;

class IntegrationController extends Controller
{
    public function index()
    {
        $ads = Ad::where('user_id', Auth::id())
            ->with('campaign')
            ->orderBy('status')
            ->latest()
            ->get();

        $base = rtrim(config('app.url'), '/');

        return view('integrations.index', compact('ads', 'base'));
    }
}
