<?php

namespace App\Http\Controllers\Api\Version_1\Repositories\Common;

use App\Http\Controllers\Api\Version_1\Interface\Common\CommonInterface;
use Illuminate\Support\Facades\Log;
use App\Models\Organization\OrganizationDatabase;

class CommonRepository implements CommonInterface
{
    public function getDataBaseNameByOrgId($orgId)
    {
        return OrganizationDatabase::where('org_id', $orgId)->first();


    }
}
