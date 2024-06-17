<?php

namespace App\Http\Controllers\Api\Version_1\Service\HRM\Master;

use App\Http\Controllers\Api\Version_1\Interface\HRM\Master\HrTypeInterface;
use App\Http\Controllers\Api\Version_1\Service\Common\CommonService;
use App\Models\HrmResourceType;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

/**
 * Class HrmDepartmentService
 * @package App\Services
 */
class HrTypeService
{
    protected $HrTypeInterface,$commonService;
    public function __construct(HrTypeInterface $HrTypeInterface, CommonService $commonService)
    {
        $this->HrTypeInterface = $HrTypeInterface;
        $this->commonService = $commonService;
    }
    public function index($orgId)
    {
     
        Log::info('HrTypeService > index function Inside.' . json_encode($orgId));
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $models = $this->HrTypeInterface->index();
        Log::info('HrTypeService > index function Return.' . json_encode($models));
        return $this->commonService->sendResponse($models, true);
    }
    public function store($data, $orgId)
    {
        Log::info('HrTypeService > Store function Inside.' . json_encode($data,$orgId));
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $model = $this->convertToModel($data);
        $response = $this->HrTypeInterface->store($model);
        Log::info('HrTypeService > Store function Return.' . json_encode($response));
        return $this->commonService->sendResponse($response,true);
    }

    public function convertToModel($data)
    {
        Log::info('HrTypeService > convertToModel function Inside.' . json_encode($data));
        $data = (object)$data;
        $id = isset($data->id)?$data->id:null;
        if ($id) {
            $model = $this->HrTypeInterface->findById($id);
         
            // $model->last_updated_by=auth()->user()->uid;
            $model->last_updated_by=null;


        } else {
            $model = new HrmResourceType();
            // $model->created_by=auth()->user()->uid;
            $model->created_by=null;

        }
        $model->resource_type = $data->hrType;
        $model->description = $data->description;
        $model->pfm_active_status_id =isset($data->activeStatus) ? $data->activeStatus : 1;
        Log::info('HrTypeService > convertToModel function Return.' . json_encode($model));
        return $model;
    }
    public function findById($orgId, $id)
    {
        Log::info('HrTypeService > findById function Inside.' . json_encode($orgId,$id));
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $response = $this->HrTypeInterface->findById($id);
        Log::info('HrTypeService > findById function Return.' . json_encode($response));
        return $this->commonService->sendResponse($response,true);
    }
    public function destroyById($orgId, $id)
    {
        Log::info('HrTypeService > destroyById function Inside.' . json_encode($orgId));
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $model = $this->HrTypeInterface->findById($id);
        if($model)
        {
        $destory=$this->HrTypeInterface->destroyForHrTypeByUid($model->id);
        if( $destory){
            return $this->commonService->sendResponse("Deleted Successfully", true);
        }else{
            return $this->commonService->sendResponse("Not Deleted", false);

        }    }
    }
}