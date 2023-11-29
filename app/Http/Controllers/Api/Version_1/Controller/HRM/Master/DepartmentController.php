<?php

namespace App\Http\Controllers\Api\Version_1\Controller\HRM\Master;

use App\Http\Controllers\Api\Version_1\Service\HRM\Master\DepartmentService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;


class DepartmentController extends Controller
{
    protected $DepartmentService;
    public function __construct(DepartmentService $DepartmentService)
    {
        $this->DepartmentService = $DepartmentService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($orgId)
    {
       
        Log::info('DepartmentController>Index Function>Inside.' .json_encode($orgId));
        $response = $this->DepartmentService->findAll($orgId);
        Log::info('DepartmentController>Index Function>Return' . json_encode($response));
        return $response;
    }

  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($orgId)
    {
        $response = $this->DepartmentService->create($orgId);
        Log::info('DepartmentController>Create Function>Return' . json_encode($response));
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$orgId)
    {
        Log::info('Store function Inside.' . json_encode($request->all()));
        $response = $this->DepartmentService->store($request->all(), $orgId);
        Log::info('Store function Return.' . json_encode($response));
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
        Log::info('Edit function Inside.' . json_encode($id));
        $response = $this->DepartmentService->findById($orgId, $id);
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
    public function update(Request $request,$id)
    {

    }
    
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($orgId, $id)
    {
            Log::info('Destroy function Inside id.' . json_encode($id));
            $response = $this->DepartmentService->destroyById($orgId, $id);
            Log::info('Destroy function Return id.' . json_encode($response));
            return $response;
        
    }

}
