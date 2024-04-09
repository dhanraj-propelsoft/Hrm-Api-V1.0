<?php

namespace App\Http\Controllers\Api\Version_1\Controller\HRM\Inventory;

use App\Http\Controllers\Api\Version_1\Service\Inventory\InventoryService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;


class InventoryController extends Controller
{
    protected $InventoryService;
    public function __construct(InventoryService $InventoryService)
    {
        $this->InventoryService = $InventoryService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($inventoryId)
    {

        Log::info('InventoryController>Index Function>Inside.' .json_encode($inventoryId));
        $response = $this->InventoryService->index($inventoryId);
        Log::info('InventoryController>Index Function>Return' . json_encode($response));
        return $response;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($inventoryId)
    {
    }

    public function inventoryValidation(Request $request)
    {
        Log::info('InventoryController  -> Validation Inside.' . json_encode($request->all()));
        $response = $this->InventoryService->ValidationForInventory($request->all());
        return $response;
        Log::info('InventoryController -> Validation Return.' . json_encode($response));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$inventoryId)
    {
        Log::info('InventoryController>Store function Inside.' . json_encode($request->all()));
        $response = $this->InventoryService->store($request->all(), $inventoryId);
        Log::info('InventoryController>Store function Return.' . json_encode($response));
        return $response;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($inventoryId,$id)
    {
        Log::info('InventoryController>Edit function Inside.' . json_encode($id));
        $response = $this->InventoryService->inventoryFindById($inventoryId, $id);
        Log::info('InventoryController>Edit function Return.' . json_encode($response));
        return $response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {

    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($orgId, $inventoryId)
    {
            Log::info('InventoryController>Destroy function Inside id.' . json_encode($inventoryId));
            $response = $this->InventoryService->destroyForInventoryByUid($orgId, $inventoryId);
            Log::info('InventoryController>Destroy function Return id.' . json_encode($response));
            return $response;

    }

}
