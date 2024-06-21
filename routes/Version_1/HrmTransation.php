<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Version_1\Controller\HRM\Transaction\ResourceController;

Route::middleware(['OnlyGateWay.access'])->group(function () {

  Route::post('findResourceWithCredentials/{orgId}', [ResourceController::class,'findResourceWithCredentials'])->name('findResourceWithCredentials');
  Route::get('getResourceMasterData/{orgId}', [ResourceController::class,'getResourceMasterData'])->name('getPersonMasterData');
  Route::post('resourcesStore/{orgId}', [ResourceController::class,'store'])->name('resourcesStore');
  Route::get('findAllResources/{orgId}', [ResourceController::class,'index'])->name('findAllResources');
  Route::post('generateMobileOtp/{orgId}', [ResourceController::class,'resourceMobileOtp'])->name('generateMobileOtp');
  Route::post('resourceMobileOtpValidate/{orgId}', [ResourceController::class,'resourceMobileOtpValidate'])->name('resourceMobileOtpValidate');
  Route::post('resourceEmailOtp/{orgId}', [ResourceController::class,'resourceEmailOtp'])->name('resourceEmailOtp');
  Route::post('resourceEmailOtpValidate/{orgId}', [ResourceController::class,'resourceEmailOtpValidate'])->name('resourceEmailOtpValidate');
  Route::post('masterDatasForResource/{orgId}', [ResourceController::class,'masterDatasForResource'])->name('masterDatasForResource');
 });
