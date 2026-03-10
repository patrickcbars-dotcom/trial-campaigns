<?php

use App\Models\Campaign;
use App\Services\CampaignService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {


    $campaing = Campaign::first();

    $campaignService = new CampaignService();

    $campaignService->dispatch($campaing);

    return view('welcome');
});
