<?php

namespace App\Http\Controllers\Api\Version_1\Service\Common;

use App\Http\Controllers\Api\Version_1\Interface\Common\CommonInterface;
// use App\Http\Controllers\version1\Interfaces\Hrm\Master\HrmDepartmentInterface;
// use App\Http\Controllers\version1\Interfaces\Hrm\Master\HrmDesignationInterface;
// use App\Http\Controllers\version1\Interfaces\Hrm\Master\HrmHumanResourceTypeInterface;
// use App\Http\Controllers\version1\Interfaces\Organization\OrganizationInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CommonService
{
    public function __construct(CommonInterface $commonInterface)
    {
        $this->commonInterface = $commonInterface;

    }
    public function sendResponse($result, $message)
    {
      $response = [
        'success' => true,
        'data' => $result,
        'message' => $message,
      ];
  
      return response()->json($response, 200);
    }
    public function sendError($errorMessages = [],$error, $code = 404)
    {
      $response = [
        'success' => false,
        'message' => $error,
      ];
  
      if (!empty($errorMessages)) {
        $response['data'] = $errorMessages;
      }
      return response()->json($response, $code);
    }
    public function getOrganizationDatabaseByOrgId($orgId)
    {
        $result = $this->commonInterface->getDataBaseNameByOrgId($orgId);
        Session::put('currentDatabase', $result->db_name);
        Config::set('database.connections.mysql_external.database', $result->db_name);
        DB::purge('mysql');
        DB::reconnect('mysql');
        Log::info('CommonService > getOrganizationDatabaseByOrgId function Return.' . json_encode($result));
        return $result;
    }
}
