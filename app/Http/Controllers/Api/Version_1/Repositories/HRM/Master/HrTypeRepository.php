<?php

namespace App\Http\Controllers\Api\Version_1\Repositories\Hrm\Master;

use App\Http\Controllers\Api\Version_1\Interface\Hrm\Master\HrTypeInterface;
use App\Models\HrmResourceType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


//use Your Model

/**
 * Class HrmDesignationRepository.
 */
class  HrTypeRepository implements HrTypeInterface
{
    public function index()
    {
        return HrmResourceType::whereNull('deleted_flag')->get();
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
    public function findById($id)
    {
        return HrmResourceType::where('id', $id)->whereNull('deleted_flag')->first();
      
    }
    public function destroyForHrTypeByUid($id)
    {
        return HrmResourceType::where('id',$id)->update(['deleted_flag' => 1, 'deleted_at' => Carbon::now()]);

    }
}