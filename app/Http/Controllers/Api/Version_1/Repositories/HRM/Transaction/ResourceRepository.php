<?php

namespace App\Http\Controllers\Api\Version_1\Repositories\Hrm\Transaction;

use App\Http\Controllers\Api\Version_1\Interface\Hrm\Transaction\ResourceInterface;
use App\Models\HrmDepartment;
use App\Models\HrmResource;
use App\Models\HrmResourceSr;
use Illuminate\Support\Facades\DB;

//use Your Model

/**
 * Class HrmDepartmentRepository.
 */
class ResourceRepository implements ResourceInterface
{


    public function findAll()
    {
        return HrmResource::with([
            'Person.personDetails',
            'resourceDesignation.ParentHrmDesignation.department',
            'resourceSr',
        ])->whereNull('deleted_flag')->get()->toArray();

    }
    public function findResourceByUid($uid)
    {
        return HrmResource::where('uid', $uid)->first();
    }
    public function saveResource($allModels)
    {

        try {

            $result = DB::transaction(function () use ($allModels) {

                $resourceModel = $allModels['resourceModel'];
                $resourceTypeDetailModel = $allModels['resourceTypeDetailModel'];
                $resourceDesignModel = $allModels['resourceDesignModel'];
                $resourceServiceModel = $allModels['resourceServiceModel'];
                $resourceServiceDetailsModel = $allModels['ResourceServiceDetailsModel'];

                $resourceModel->save();

                $resourceTypeDetailModel->ParentHrmResource()->associate($resourceModel, 'resource_id', 'id');
                 $resourceDesignModel->ParentHrmResource()->associate($resourceModel, 'resource_id', 'id');
                $resourceServiceModel->ParentHrmResource()->associate($resourceModel, 'resource_id', 'id');
                $resourceTypeDetailModel->save();
                 $resourceDesignModel->save();
                $resourceServiceModel->save();
                $resourceServiceDetailsModel->ParentHrmResourceService()->associate($resourceServiceModel, 'resource_sr_id', 'id');
                $resourceServiceDetailsModel->save();

                return [
                    'message' => "Success",
                    'data' => $resourceModel

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
    }
