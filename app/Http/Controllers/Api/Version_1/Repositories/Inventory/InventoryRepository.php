<?php

namespace App\Http\Controllers\Api\Version_1\Repositories\Inventory;

use App\Http\Controllers\Api\Version_1\Interface\Inventory\InventoryInterface;

use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

//use Your Model

/**
 * Class InventoryRepository.
 */
class InventoryRepository implements InventoryInterface
{
    public function index()
    {

        return Inventory::whereNull('deleted_flag')->whereNull('deleted_at')->get();
    }
    public function inventoryFindById($id)
    {
        return Inventory::where('id', $id)->whereNull('deleted_flag')->first();
    }
   public function store($model)
    {
        try {
            $result = DB::transaction(function () use ($model) {

                $model->save();
                return [
                    'message' => "Success",
                    'data' => $model
                ];
            });

            return $result;
        } catch (\Exception $e) {


            return [

                'message' => "failed",
                'data' => $e
            ];
        }
    }
    // public function getParentDeptExceptThisId($id)
    // {
    //     return Inventory::where('id', '!=', $id)->whereNull('deleted_flag')->get();
    // }
    public function destroyForInventoryByUid($id)
    {
        return Inventory::where('id',$id)->update(['deleted_flag' => 1, 'deleted_at' => Carbon::now()]);
    }
}
