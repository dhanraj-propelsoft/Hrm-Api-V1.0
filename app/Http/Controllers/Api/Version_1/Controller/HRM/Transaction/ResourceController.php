<?php

namespace App\Http\Controllers\Api\Version_1\Controller\HRM\Transaction;

use App\Http\Controllers\Api\Version_1\Service\HRM\Transaction\ResourceService;
use App\Http\Controllers\Api\Version_1\Service\Common\CommonService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResourceController extends Controller
{
    public function __construct(ResourceService $ResourceService,CommonService $commonService)
    {
        $this->ResourceService = $ResourceService;
        $this->commonService = $commonService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($orgId)
    {
        Log::info('ResourceController-> index Inside.' . json_encode($orgId));
        $response = $this->ResourceService->findAll($orgId);
        Log::info('ResourceController>Store Return.' . json_encode($response));
        return $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $orgId)
    {

        Log::info('ResourceController > resourcesStore.' . json_encode($request->all(), $orgId));
        $response = $this->ResourceService->resourcesStore($request->all(), $orgId);
        // Log::info('HrmResourceController>Store Return.' . json_encode($response));
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function findResourceWithCredentials(Request $request, $orgId)
    {

        Log::info('findResourceWithCredentials-> Store Inside.' . json_encode($request->all()));
        $response = $this->ResourceService->findResourceWithCredentials($request->all(), $orgId);
        return $response;
        Log::info('HrmResourceController>Store Return.' . json_encode($response));
    }
    public function getResourceMasterData($orgId)
    {
        Log::info('ResourceController-> getPersonMasterData Inside OrgId .' . json_encode($orgId));
        $response = $this->ResourceService->getResourceMasterData($orgId);
        Log::info('ResourceController>Store Return.' . json_encode($response));

        return $response;
    }
    public function resourceMobileOtp(Request $request, $orgId)
{
    Log::info('ResourceController > resourceMobileOtp.' . json_encode($request->all()));
    $response = $this->ResourceService->resourceMobileOtp($request->all(),$orgId);
    Log::info('ResourceController> resourceMobileOtp .' . json_encode($response));
    return $response;

}
public function resourceMobileOtpValidate(Request $request, $orgId)
{
    Log::info('ResourceController > resourceMobileOtpValidate.' . json_encode($request->all()));
    $response = $this->ResourceService->resourceMobileOtpValidate($request->all(),$orgId);
    Log::info('ResourceController > resourceMobileOtpValidate .' . json_encode($response));
    return $response;

}
public function resourceEmailOtp(Request $request, $orgId)
{
    Log::info('ResourceController > resourceEmailOtp.' . json_encode($request->all()));
    $response = $this->ResourceService->resourceEmailOtp($request->all(),$orgId);
    Log::info('ResourceController > resourceEmailOtp .' . json_encode($response));
    return $response;

}
public function resourceEmailOtpValidate(Request $request, $orgId)
{
    Log::info('ResourceController > resourceEmailOtpValidate.' . json_encode($request->all()));
    $response = $this->ResourceService->resourceEmailOtpValidate($request->all(),$orgId);
    Log::info('ResourceController> resourceEmailOtpValidate .' . json_encode($response));
    return $response;

}
public function masterDatasForResource(Request $request, $orgId)
{
    
    Log::info('ResourceController > masterDatasForResource.' . json_encode($request->all()));
    $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
    $response = $this->ResourceService->resourceAndPersonMasterDatas($request->all(),$orgId);
    Log::info('ResourceController> masterDatasForResource .' . json_encode($response));
    return $this->commonService->sendResponse($response,true);


}
}
