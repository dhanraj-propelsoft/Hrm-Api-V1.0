<?php

namespace App\Http\Controllers\Api\Version_1\Service\HRM\Master;

use App\Http\Controllers\Api\Version_1\Interface\Hrm\Master\DesginationInterface;
use App\Http\Controllers\Api\Version_1\Interface\Hrm\Master\DepartmentInterface;
use App\Http\Controllers\Api\Version_1\Interface\Hrm\Master\DesignationInterface;
use App\Http\Controllers\Api\Version_1\Service\Common\CommonService;
use App\Models\HrmDepartment;
use App\Models\HrmDesignation;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

/**
 * Class HrmDepartmentService
 * @package App\Services
 */
class DesignationService
{
    protected $DesginationInterface,$commonService,$DepartmentInterface;
    public function __construct(DesignationInterface $DesginationInterface, CommonService $commonService,DepartmentInterface $DepartmentInterface)
    {
        $this->DesginationInterface = $DesginationInterface;
        $this->commonService = $commonService;
        $this->DepartmentInterface = $DepartmentInterface;
    }
    public function findAll($orgId)
    {

        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $models = $this->DesginationInterface->findAll();
        $department = $this->DepartmentInterface->findAll();
        $responseArray = ['model' => $models, 'department' => $department];
        return $this->commonService->sendResponse($responseArray,true);
    }

    public function store($data, $orgId)
    {
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $model = $this->convertToModel($data);
        $response = $this->DesginationInterface->store($model);

        return $response;
    }


    public function convertToModel($data)
    {

        $data = (object)$data;
        $id=isset($data->id)?$data->id:null;
        if ($id) {
            $model = $this->DesginationInterface->findById($id);
            // $model->last_updated_by = auth()->user()->uid;
            $model->last_updated_by = null;

        } else {
            $model = new HrmDesignation();
            // $model->created_by = auth()->user()->uid;
            $model->created_by = null;

        }
        $model->designation_name = $data->designation;
        $model->no_of_posting = $data->no_of_posting;
        $model->dept_id = $data->department;
        $model->description = $data->description;
        $model->pfm_active_status_id = isset($data->activeStatus) ? $data->activeStatus : 1;
      
        return $model;
    }
    public function findById($orgId, $id)
    {
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $response = $this->DesginationInterface->findById($id);
        $department = $this->DepartmentInterface->findAll();
        $responseArray = ['responseModelData' => $response, 'department' => $department];

        return $this->commonService->sendResponse($responseArray, true);
    }
    public function destroyById($orgId, $id)
    {
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $model = $this->DesginationInterface->findById($id);
        if($model)
        {
        $destory=$this->DesginationInterface->destroyForDesignationByUid($model->id);
        if( $destory){
            return $this->commonService->sendResponse("Deleted Successfully", true);
        }else{
            return $this->commonService->sendResponse("Not Deleted", false);

        }
    }
}
public function create($orgId)
{
    $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
    $models = $this->DepartmentInterface->findAll();
    return $this->commonService->sendResponse($models, true);
}
public function findDesignationByDeptId($orgId,$deptId)
    {
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $response = $this->DesginationInterface->findByDeptId($deptId);
        return $this->commonService->sendResponse($response,true);
    }
}
