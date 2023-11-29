<?php

namespace App\Http\Controllers\Api\Version_1\Repositories\Hrm\Master;

use App\Http\Controllers\Api\Version_1\Interface\Hrm\Master\DepartmentInterface;

use App\Models\HrmDepartment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

//use Your Model

/**
 * Class HrmDepartmentRepository.
 */
class DepartmentRepository implements DepartmentInterface
{
    public function findAll()
    {

        return HrmDepartment::with('hrmParentDept')->whereNull('deleted_flag')->whereNull('deleted_at')->get();
    }
    public function findById($id)
    {
        return HrmDepartment::with('hrmParentDept')->where('id', $id)->whereNull('deleted_flag')->first();  
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
    public function getParentDeptExceptThisId($id)
    {
        return HrmDepartment::where('id', '!=', $id)->whereNull('deleted_flag')->get();
    }
    public function destroyForDepartmentByUid($id)
    {
       
        return HrmDepartment::where('id',$id)->update(['deleted_flag' => 1, 'deleted_at' => Carbon::now()]);
    }
}
