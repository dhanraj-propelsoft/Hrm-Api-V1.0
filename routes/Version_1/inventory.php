<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Version_1\Controller\HRM\Inventory\InventoryController;

Route::post('inventoryStore/{orgId}', [InventoryController::class,'store'])->name('inventoryStore');
Route::get('deleteInventoryById/{orgId}/{id}', [InventoryController::class,'destroy'])->name('deleteInventoryById');
Route::get('getInventory/{orgId}', [InventoryController::class,'index'])->name('getInventory');
Route::get('findInventoryById/{orgId}/{id}', [InventoryController::class,'edit'])->name('findInventoryById');
Route::post('/inventoryValidation/{orgId}', [InventoryController::class, 'inventoryValidation'])->name('inventoryValidation');

// Route::apiResource('inventory/{orgId}',InventoryController::class);

