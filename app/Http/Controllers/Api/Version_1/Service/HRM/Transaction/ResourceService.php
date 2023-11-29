<?php

namespace App\Http\Controllers\Api\Version_1\Service\HRM\Transaction;

use App\Http\Controllers\Api\Version_1\Interface\Hrm\Master\DepartmentInterface;
use App\Http\Controllers\Api\Version_1\Interface\Hrm\Master\DesignationInterface;
use App\Http\Controllers\Api\Version_1\Interface\Hrm\Master\HrTypeInterface;
use App\Http\Controllers\Api\Version_1\Interface\Hrm\Transaction\ResourceInterface;
use App\Http\Controllers\Api\Version_1\Service\Common\CommonService;
use Illuminate\Support\Facades\Http;


class ResourceService
{
    protected $ResourceInterface, $commonService;
    public function __construct(ResourceInterface $ResourceInterface, CommonService $commonService, DepartmentInterface $DepartmentInterface, DesignationInterface $DesignationInterface, HrTypeInterface $HrTypeInterface)
    {
        $this->ResourceInterface = $ResourceInterface;
        $this->commonService = $commonService;
        $this->DepartmentInterface = $DepartmentInterface;
        $this->DesignationInterface = $DesignationInterface;
        $this->HrTypeInterface = $HrTypeInterface;


    }
 public function findResourceWithCredentials($datas, $orgId)
    {
      
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $datasObj = (object) $datas;
        
        $mobile = $datasObj->mobileNo;
        $email = $datasObj->email;
        $response = Http::post(config('person_api_base') . 'findExactPersonWithEmailAndMobile', $datas);       
        if ($response->successful()) {
            $responseData = $response->json();
            $checkPerson =$responseData['data'];
        } else {
            $errorCode = $response->status();
            $errorMessage = $response->body();
            dd("Error: $errorCode - $errorMessage");
        }
        // $checkPerson = $this->personInterface->findExactPersonWithEmailAndMobile($email,$mobile);


        /*Some Important Types credential Type Start */
        /* 1.get All Person */
        /* 2.None*/
        /* 3.Person Email Only */
        /* 4.Resource True */
        /* 5.Resource false */
        /* 6.SameOrganizationMember/employee*/
        /* 7. NotInSameOrganizationMember */

        /*Some Important Types credential Type End */
        if ($checkPerson) {
            $uid = $checkPerson['uid'];
            $response = Http::post(config('person_api_base') . 'findMemberDataByUid', $uid);       
            if ($response->successful()) {
                $responseData = $response->json();
                $findMember =$responseData['data'];
              
               

            } else {
                $errorCode = $response->status();
                $errorMessage = $response->body();
                dd("Error: $errorCode - $errorMessage");
            }
            if ($findMember) {
                $memberWithInOrganization = $this->ResourceInterface->findResourceByUid($uid);
                if ($memberWithInOrganization) {
                    $results = ['type' => 6, 'status' => "SameOrganizationMember", 'data' =>null];
                } else {
                   $value['uid']=$uid;
                   $response = Http::post(config('person_api_base') . 'personDatas', $value);    
                   if ($response->successful()) {
                       $responseData = $response->json();
                       $getMemberName =$responseData['data'];

                   } else {
                       $errorCode = $response->status();
                       $errorMessage = $response->body();
                       dd("Error: $errorCode - $errorMessage");
                   }

                    $results = ['type' => 7, 'data' => $getMemberName, 'mobile' => $findMember];
                }
                return $this->commonService->sendResponse($results,true);
            } else {
                $checkResource = $this->ResourceInterface->findResourceByUid($uid);
                if ($checkResource) {
                    $resData = ['type' => 4, 'Resuid' => $uid];
                } else {
                    $response = Http::post(config('person_api_base') . 'getPrimaryMobileAndEmailbyUid', $uid);    
                    if ($response->successful()) {
                        $responseData = $response->json();
                        $personDetails =$responseData['data'];
                    } else {
                        $errorCode = $response->status();
                        $errorMessage = $response->body();
                        dd("Error: $errorCode - $errorMessage");
                    }
                    $resData = ['type' => 5, 'PersonDatas' => $personDetails];
                }
                return $this->commonService->sendResponse($resData,true);
            }
        } else {
         
            // $personMobile = $this->personInterface->getPersonDataByMobileNo($mobile);
            // $personEmail = $this->personInterface->getPersonDataByEmail($email);
            $response = Http::post(config('person_api_base') . 'getPersonAllDetails', $uid);    
                    if ($response->successful()) {
                        $responseData = $response->json();
                        $personDetails =$responseData['data'];
                    } else {
                        $errorCode = $response->status();
                        $errorMessage = $response->body();
                        dd("Error: $errorCode - $errorMessage");
                    }
            if ($personMobile  !== null || $personEmail !== null) {
                $personData=['personMobile'=>$personMobile->mobile,'personEmail'=>$personEmail->email];
                $resData = ['type' => 1, 'PersonDatas' => $personData];
            } else {
                $resData = ['type' => 2, 'status' => 'freshResource', 'mobile' => $mobile, 'email' => $email];
            }
        }
        return $this->commonService->sendResponse($resData,true);
    }

    public function getResourceMasterData($orgId)
    {

        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        // $masterDatas = $this->commonService->getPersonMasterData();
        $hrmDepartmentLists = $this->DepartmentInterface->findAll();
        $hrmDesignationLists = $this->DesignationInterface->findAll();
        $hrTypeLists = $this->HrTypeInterface->index();

        $masterDatas = [];
        $masterDatas['hrmDepartmentLists'] = $hrmDepartmentLists;
        $masterDatas['hrmDesignationLists'] = $hrmDesignationLists;
        $masterDatas['hrTypeLists'] = $hrTypeLists;

        return $this->commonService->sendResponse($masterDatas, true);
    }
    public function resourcesStore($datas, $orgId)
    {
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);

        $orgdatas = (object) $datas;
        $personModelresponse = $this->personService->storePerson($datas, 'resource');

        if ($personModelresponse['message'] == "Success") {

            $personModel = $personModelresponse['data'];

            $uid = $personModel->uid;
            Log::info('HrmResourceService > saveUid.' . json_encode($uid));

            $convertToResourceModel = $this->convertToResourceModel($orgdatas, $uid);
            Log::info('HrmResourceService > convertToResourceModel.' . json_encode($convertToResourceModel));

            //$resourceModel = $this->hrmResourceInterface->saveResourceModel($convertToResourceModel);
            $convertToResourceTypeDetailModel = $this->convertToResourceTypeDetailModel($orgdatas);
                        $convertToResourceDesignationModel = $this->convertToResourceDesignationModel($orgdatas);
            Log::info('HrmResourceService > convertToResourceDesignationModel.' . json_encode($convertToResourceDesignationModel));

            $convertToResourceService = $this->convertToResourceService($orgdatas);
            Log::info('HrmResourceService > convertToResourceService.' . json_encode($convertToResourceService));

            $convertToResourceServiceDetails = $this->convertToResourceServiceDetails($orgdatas);
            Log::info('HrmResourceService > convertToResourceServiceDetails.' . json_encode($convertToResourceServiceDetails));



            $allModels = [
                'resourceModel' => $convertToResourceModel,
                 'resourceTypeDetailModel' => $convertToResourceTypeDetailModel,
                 'resourceDesignModel' => $convertToResourceDesignationModel,
                'resourceServiceModel' => $convertToResourceService,
                'ResourceServiceDetailsModel' => $convertToResourceServiceDetails,

            ];

            $saveResourceModel = $this->hrmResourceInterface->saveResource($allModels);


            log::info('saveResource ' . json_encode($saveResourceModel));
            return $this->commonService->sendResponse($saveResourceModel,True);
        }
    }
    public function convertToResourceModel($datas, $uid)
    {
        $model = HrmResource::where('uid', isset($datas->personUid))->first();
        if ($model) {
            $model->uid = $uid;
        } else {
            $model = new HrmResource();
            $model->uid = $uid;
        }
        $model->resource_code = $datas->resourceCode;
        return $model;
    }
}

