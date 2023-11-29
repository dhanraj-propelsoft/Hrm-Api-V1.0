<?php

namespace App\Http\Controllers\Api\Version_1\Service\HRM\Master;

use App\Http\Controllers\Api\Version_1\Interface\Hrm\Master\DepartmentInterface;
use App\Http\Controllers\Api\Version_1\Service\Common\CommonService;
use App\Models\HrmDepartment;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

/**
 * Class HrmDepartmentService
 * @package App\Services
 */
class DepartmentService
{
    protected $DepartmentInterface,$commonService;
    public function __construct(DepartmentInterface $DepartmentInterface, CommonService $commonService)
    {
        $this->DepartmentInterface = $DepartmentInterface;
        $this->commonService = $commonService;
    }
    public function findAll($orgId)
    {
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        
        $models = $this->DepartmentInterface->findAll();
        return $this->commonService->sendResponse($models, true);
    }
    public function create($orgId)
    {
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $models = $this->DepartmentInterface->findAll();
        return $this->commonService->sendResponse($models, true);
    }
    public function store($data, $orgId)
    {
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $model = $this->convertToModel($data);
        $response = $this->DepartmentInterface->store($model);

        return $this->commonService->sendResponse($response, true);
    }

    public function convertToModel($data)
    {
        $data = (object) $data;
        $id=isset($data->id)?$data->id:null;
        if ($id) {
            $model = $this->DepartmentInterface->findById($id);
            // $model->last_updated_by=auth()->user()->uid;
            $model->last_updated_by=null;

        } else {
            $model = new HrmDepartment();
           // $model->created_by=auth()->user()->uid;
           $model->created_by=null;


        }
        $model->department_name = $data->department;
        $model->parent_dept_id = isset($data->parent_dept_id) ? $data->parent_dept_id : null;
        $model->description = $data->description;
        $model->pfm_active_status_id =isset($data->activeStatus) ? $data->activeStatus : 1;

        return $model;
    }
    public function findById($orgId, $id)
    {
     
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $response = $this->DepartmentInterface->findById($id);
        $responseParentDeptData = $this->DepartmentInterface->getParentDeptExceptThisId($id);
        $responseArray = ['responseModelData' => $response, 'responseParentDeptData' => $responseParentDeptData];

        return $this->commonService->sendResponse($responseArray, true);
    }
    public function destroyById($orgId, $id)
    {
        
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $model = $this->DepartmentInterface->findById($id);
        if($model)
        {
        $destory=$this->DepartmentInterface->destroyForDepartmentByUid($model->id);
        if( $destory){
            return $this->commonService->sendResponse("Deleted Successfully", true);
        }else{
            return $this->commonService->sendResponse("Not Deleted", false);
        }
        }
    }
}
