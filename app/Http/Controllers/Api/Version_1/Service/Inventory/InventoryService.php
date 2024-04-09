<?php

namespace App\Http\Controllers\Api\Version_1\Service\Inventory;

use App\Http\Controllers\Api\Version_1\Interface\Inventory\InventoryInterface;
use App\Http\Controllers\Api\Version_1\Service\Common\CommonService;
use App\Models\Inventory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

/**
 * Class InventoryService
 * @package App\Services
 */
class InventoryService
{
    protected $InventoryInterface, $commonService;

    public function __construct(InventoryInterface $InventoryInterface, CommonService $commonService)
    {
        $this->InventoryInterface = $InventoryInterface;
        $this->commonService = $commonService;
    }

    public function index($orgId)
    {
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);

        $models = $this->InventoryInterface->index();
        return $this->commonService->sendResponse($models, true);
    }

    public function create($orgId)
    {
    }
    public function ValidationForInventory($datas)
    {

        $rules = [];

        foreach ($datas as $field => $value) {
            if ($field === 'inventoryName') {
                $rules['inventoryName'] = [
                    'required',
                    'string',
                ];
            }

            // if ($field === 'inventoryImage') {
            //     $rules['inventoryImage'] = [
            //         'required',
            //         'image', // Add image validation rule if it's supposed to be an image
            //         'mimes:jpeg,png,gif,jpg,',
            //     ];
            // }
        }

        $validator = Validator::make($datas, $rules);

        if ($validator->fails()) {
            $resStatus = ['errors' => $validator->errors()];
            $resCode = 400;
        } else {
            $resStatus = ['errors' => false];
            $resCode = 200;
        }

        return [
            'data' => $resStatus,
            'status_code' => $resCode,
            'resCode' =>$resCode
        ];
    }
    public function store($datas, $orgId)
    {
    
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $db_name = $dbConnection['db_name'];
        $validation = $this->ValidationForInventory($datas);
        if ($validation['data']['errors'] === false) {
            $datas = (object) $datas;
            $model = $this->convertToModel($datas, $db_name);
            $response = $this->InventoryInterface->store($model);
            Log::info('ActivitySubsetService -> Store Return.' . json_encode($response));
            return $this->commonService->sendResponse($response, true);

        } else {
            return $validation['data']['errors'] ;
        }

    }

    public function convertToModel($data, $db_name)
    {
        $data = (object)$data;
        
        $id = isset($data->id) ? $data->id : null;
        if ($id) {
            $model = $this->InventoryInterface->inventoryFindById($id);
            // $model->last_updated_by=auth()->user()->uid;
            // $model->last_updated_by = null;
        } else {
            $model = new Inventory();
            // $model->created_by=auth()->user()->uid;
            // $model->created_by = null;
        }
        $previousImageFilename = $model->item_image;

        $uniqueFilename ="";
        if (isset($data->inventoryImage)) {
            $decodedImageContents = base64_decode($data->inventoryImage);
            $uniqueFilename = date('YmdHis') . '_' . uniqid() . '.jpg';
            $directoryPath = storage_path('app/public/' . $db_name . '/inventory');
            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true, true);
            }
            if ($previousImageFilename) {
                $previousImagePath = $directoryPath . '/' . $previousImageFilename;
                if (File::exists($previousImagePath)) {
                    File::delete($previousImagePath);
                }
            }
            $savePath = $directoryPath . '/' . $uniqueFilename;
            File::put($savePath, $decodedImageContents);
            Log::info('InventoryService > savePath function Return.' . json_encode($savePath));
        }



        $model->item_name = $data->inventoryName;
        $model->item_price = $data->inventoryPrice;
        $model->item_image = $uniqueFilename;
        // $model->description = $data->description;
        $model->pfm_active_status_id = isset($data->activeStatus) ? $data->activeStatus : 1;

        return $model;
    }

    public function inventoryFindById($orgId, $id)
    {

        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $response = $this->InventoryInterface->inventoryFindById($id);
        return $this->commonService->sendResponse($response, true);
    }

    public function destroyForInventoryByUid($orgId, $id)
    {
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $model = $this->InventoryInterface->inventoryFindById($id);
        if ($model) {
            $destroy = $this->InventoryInterface->destroyForInventoryByUid($model->id);
            if ($destroy) {
                $result = ['type' => 1, 'Message' => 'Success', 'status' => 'The Inventory Item Is Deleted'];
            } else {
                $result = ['type' => 2, 'Message' => 'Failed', 'status' => 'Failed to delete the Inventory Item'];
            }
            return $this->commonService->sendResponse($result, true);
        } else {
            return response()->json([
                'error' => 'Inventory item not found.',
                'status_code' => 404,
            ], 404);
        }
    }
}
