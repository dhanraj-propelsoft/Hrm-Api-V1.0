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


    public function findResourceByUid($uid)
    {
        return HrmResource::where('uid', $uid)->first();
    }

    }
