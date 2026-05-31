<?php

use App\Http\Controllers\Api\AdsTrackingController;
use Illuminate\Support\Facades\Route;

/*
| -------------------------------------------------------------------------
| Public Ads Tracking API
| -------------------------------------------------------------------------
| These endpoints are consumed by the hirevo website (and any other publisher
| where the ad creative is embedded). They are intentionally public, but
| every endpoint is scoped to a specific Ad's `public_key` so traffic can
| only be attributed to the correct advertiser.
*/

Route::get('/ads/serve', [AdsTrackingController::class, 'serve']);
Route::get('/track/impression/{publicKey}', [AdsTrackingController::class, 'impression'])
    ->where('publicKey', '[A-Za-z0-9-]+');
Route::get('/track/click/{publicKey}', [AdsTrackingController::class, 'click'])
    ->where('publicKey', '[A-Za-z0-9-]+');
Route::match(['get', 'post'], '/track/lead/{publicKey}', [AdsTrackingController::class, 'lead'])
    ->where('publicKey', '[A-Za-z0-9-]+');
