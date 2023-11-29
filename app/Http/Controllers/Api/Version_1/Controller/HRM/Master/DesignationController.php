<?php

namespace App\Http\Controllers\Api\Version_1\Controller\HRM\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Version_1\Service\HRM\Master\DesignationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DesignationController extends Controller
{
    protected $DesignationService;
    public function __construct(DesignationService $DesignationService)
    {
        $this->DesignationService = $DesignationService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($orgId)
    {
        Log::info('DesignationController>Index Function>Inside.'.json_encode($orgId));
        $response = $this->DesignationService->findAll($orgId);
        Log::info('DesignationController>Index Function>Return' . json_encode($response));
        return $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($orgId)
    {
        Log::info('DesignationController -> Create Function -> Inside.'. json_encode($orgId));
        $response = $this->DesignationService->create($orgId);
        Log::info('DesignationController -> Create Function -> Inside.'. json_encode($response));
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$orgId )
    {

        Log::info('Store function Inside.' . json_encode($request->all()));
        $response = $this->DesignationService->store($request->all(),$orgId);
        // Log::info('Store function Return.' . json_encode($response));
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
    public function edit($orgId,$id)
    {
        Log::info('Edit function Inside.' . json_encode($orgId,$id));
        $response = $this->DesignationService->findById($orgId, $id);
        Log::info('Edit function Return.' . json_encode($response));
        return $response;
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
    public function destroy($orgId,$id)
    {
        Log::info('Destroy function Inside id.' . json_encode($orgId,$id));
        $response = $this->DesignationService->destroyById($orgId, $id);
        Log::info('Destroy function Return .' . json_encode($response));
        return $response;
    }
    public function findDesignationByDeptId($orgId,$id)
    {
        Log::info('DesignationController  > findDesignationByDeptId Inside.' . json_encode($id));
        $response = $this->DesignationService->findDesignationByDeptId($orgId,$id);
        Log::info('DesignationController > findDesignationByDeptId Return.' . json_encode($response));
        return $response;
    }
}
