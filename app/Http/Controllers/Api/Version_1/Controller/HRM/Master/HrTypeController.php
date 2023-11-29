<?php

namespace App\Http\Controllers\Api\Version_1\Controller\HRM\Master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Version_1\Service\HRM\Master\HrTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HrTypeController extends Controller
{
    protected $HrTypeService;
    public function __construct(HrTypeService $HrTypeService)
    {
        $this->HrTypeService = $HrTypeService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($orgId)
    {
      
        Log::info('HrTypeController > Store function Inside.' . json_encode($orgId));
        $response = $this->HrTypeService->index($orgId);
        Log::info('HrTypeController > Store function Return.' . json_encode($response));
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
         Log::info('HrTypeController > Store function Inside.' .$orgId."req datas". json_encode($request->all()));
        $response = $this->HrTypeService->store($request->all(), $orgId);
        Log::info('HrTypeController > Store function Return.' . json_encode($response));
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
        
        Log::info('HrTypeController > edit function Inside.' . json_encode($orgId,$id));
        $response = $this->HrTypeService->findById($orgId, $id);
        Log::info('HrTypeController > edit function Return.' . json_encode($response));

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
        Log::info('HrTypeController > destroy function Inside.' . json_encode($orgId,$id));
        $response = $this->HrTypeService->destroyById($orgId,$id);
        Log::info('HrTypeController > destroy function Return.' . json_encode($response));
        return $response;
    }
}
