<?php

namespace App\Http\Controllers\Api\Version_1\Interface\Inventory;

interface InventoryInterface
{
    public function index();
    public function inventoryFindById($id);
    public function store($data);
    public function destroyForInventoryByUid($id);

}
