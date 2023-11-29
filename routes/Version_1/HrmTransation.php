<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Version_1\Controller\HRM\Transaction\ResourceController;

  Route::post('findResourceWithCredentials/{orgId}', [ResourceController::class,'findResourceWithCredentials'])->name('findResourceWithCredentials');
  Route::get('getResourceMasterData/{orgId}', [ResourceController::class,'getResourceMasterData'])->name('getPersonMasterData');
  Route::post('resourcesStore/{orgId}', [ResourceController::class,'store'])->name('resourcesStore');

