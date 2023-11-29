<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Version_1\Controller\HRM\Master\DepartmentController;
use App\Http\Controllers\Api\Version_1\Controller\HRM\Master\DesignationController;
use App\Http\Controllers\Api\Version_1\Controller\HRM\Master\HrTypeController;


Route::post('getDepartment/{orgId}', [DepartmentController::class,'index'])->name('getDepartment');
Route::get('createDepartment/{orgId}', [DepartmentController::class,'create'])->name('createDepartment');
Route::post('storeDepartment/{orgId}', [DepartmentController::class,'store'])->name('storeDepartment');
Route::get('findDepartmentById/{orgId}/{id}', [DepartmentController::class,'edit'])->name('findDepartmentById');
Route::get('deleteDepartmentById/{orgId}/{id}', [DepartmentController::class,'destroy'])->name('deleteDepartmentById');
Route::post('storeDesignation/{orgId}', [DesignationController::class,'store'])->name('storeDesignation');
Route::get('getDesignation/{orgId}', [DesignationController::class,'index'])->name('getDesignation');
Route::get('findDesignationById/{orgId}/{id}', [DesignationController::class,'edit'])->name('findDesignationById');
Route::get('destroyDesignationById/{orgId}/{id}', [DesignationController::class,'destroy'])->name('destroyDesignationById');
Route::get('createDesignation/{orgId}', [DesignationController::class,'create'])->name('createDesignation');
Route::get('getHrType/{orgId}', [HrTypeController::class,'index'])->name('getHrType');
Route::post('storeHrType/{orgId}', [HrTypeController::class,'store'])->name('storeHrType');
Route::get('findHrType/{orgId}/{id}', [HrTypeController::class,'edit'])->name('findHrType');
Route::get('destroyHrType/{orgId}/{id}', [HrTypeController::class,'destroy'])->name('destroyHrType');
Route::get('findDesignationByDeptId/{orgId}/{id}', [DesignationController::class,'findDesignationByDeptId'])->name('findDesignationByDeptId');
