<?php

namespace App\Http\Controllers\Api\Version_1\Service\HRM\Transaction;

use App\Http\Controllers\Api\Version_1\Interface\Hrm\Master\DepartmentInterface;
use App\Http\Controllers\Api\Version_1\Interface\Hrm\Master\DesignationInterface;
use App\Http\Controllers\Api\Version_1\Interface\Hrm\Master\HrTypeInterface;
use App\Http\Controllers\Api\Version_1\Interface\Hrm\Transaction\ResourceInterface;
use App\Http\Controllers\Api\Version_1\Service\Common\CommonService;
use App\Models\HrmResource;
use App\Models\HrmResourceDesignation;
use App\Models\HrmResourceSr;
use App\Models\HrmResourceSrAffinity;
use App\Models\HrmResourceTypeAffinity;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
    public function findAll($orgId)
    {

        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $resources = $this->ResourceInterface->findAll();
        $resourcesCollection = collect($resources);
        $resourceDetails = $resourcesCollection->map(function ($resource) {
            $resource = (object) $resource;
            $personUid = $resource->uid;
            $resourceId = $resource->id;
            $personDetails = $resource->person['person_details'];
            $personDetails = (object) $personDetails;
            $personName = "{$personDetails->first_name} {$personDetails->last_name} {$personDetails->nick_name}";
            $department = isset($resource->resource_designation) ? $resource->resource_designation['parent_hrm_designation']['department']['department_name'] : null;
            $designation = isset($resource->resource_designation) ? $resource->resource_designation['parent_hrm_designation']['designation_name'] : null;
            $resourceStatus = isset($resource->resource_sr) ? $resource->resource_sr['hrm_resource_activity_status_id'] : null;

            return [
                'designation' => $designation,
                'department' => $department,
                'resourceName' => $personName,
                'resourceId' => $resourceId,
                'uid' => $personUid,
                'resourceStatus' => $resourceStatus,
            ];
        });
        return $this->commonService->sendResponse($resourceDetails, true);
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
            $checkPerson = $responseData['data'];
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
                $findMember = $responseData['data'];

            } else {
                $errorCode = $response->status();
                $errorMessage = $response->body();
                dd("Error: $errorCode - $errorMessage");
            }
            if ($findMember) {
                $memberWithInOrganization = $this->ResourceInterface->findResourceByUid($uid);
                if ($memberWithInOrganization) {
                    $results = ['type' => 6, 'status' => "SameOrganizationMember", 'data' => null];
                } else {
                    $value['uid'] = $uid;
                    $response = Http::post(config('person_api_base') . 'personDatas', $value);
                    if ($response->successful()) {
                        $responseData = $response->json();
                        $getMemberName = $responseData['data'];

                    } else {
                        $errorCode = $response->status();
                        $errorMessage = $response->body();
                        dd("Error: $errorCode - $errorMessage");
                    }

                    $results = ['type' => 7, 'data' => $getMemberName, 'mobile' => $findMember];
                }
                return $this->commonService->sendResponse($results, true);
            } else {
                $checkResource = $this->ResourceInterface->findResourceByUid($uid);
                if ($checkResource) {
                    $resData = ['type' => 4, 'Resuid' => $uid];
                } else {
                    $response = Http::post(config('person_api_base') . 'getPrimaryMobileAndEmailbyUid', $uid);
                    if ($response->successful()) {
                        $responseData = $response->json();
                        $personDetails = $responseData['data'];
                    } else {
                        $errorCode = $response->status();
                        $errorMessage = $response->body();
                        dd("Error: $errorCode - $errorMessage");
                    }
                    $resData = ['type' => 5, 'PersonDatas' => $personDetails];
                }
                return $this->commonService->sendResponse($resData, true);
            }
        } else {
            $response = Http::post(config('person_api_base') . 'getPersonAllDetails', $datas);
            if ($response->successful()) {
                $responseData = $response->json();
                $personDetails = $responseData['data'];
                $mobileData = isset($personDetails['personMobile']['mobile']) ? $personDetails['personMobile']['mobile'] : null;
                $EmailData = isset($personDetails['personEmail']['email']) ? $personDetails['personEmail']['email'] : null;
            } else {
                $errorCode = $response->status();
                $errorMessage = $response->body();
                dd("Error: $errorCode - $errorMessage");
            }
            if ($mobileData !== null || $EmailData !== null) {
                $personData = ['personMobile' => $mobileData, 'personEmail' => $EmailData];
                $resData = ['type' => 1, 'PersonDatas' => $personData];
            } else {
                $resData = ['type' => 2, 'status' => 'freshResource', 'mobile' => $mobile, 'email' => $email];
            }
        }
        return $this->commonService->sendResponse($resData, true);
    }

    public function getResourceMasterData($orgId)
    {

        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $response = Http::get(config('person_api_base') . 'getPersonMasterData');
        if ($response->successful()) {
            $responseData = $response->json();
            $MasterData = $responseData['data'];
            $hrmDepartmentLists = $this->DepartmentInterface->findAll();
            $hrmDesignationLists = $this->DesignationInterface->findAll();
            $hrTypeLists = $this->HrTypeInterface->index();
            $masterDatas['hrmDepartmentLists'] = $hrmDepartmentLists;
            $masterDatas['hrmDesignationLists'] = $hrmDesignationLists;
            $masterDatas['hrTypeLists'] = $hrTypeLists;

        } else {
            $errorCode = $response->status();
            $errorMessage = $response->body();
            dd("Error: $errorCode - $errorMessage");
        }

        return $this->commonService->sendResponse($masterDatas, true);
    }
    public function resourcesStore($datas, $orgId)
    {

        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $orgdatas = (object) $datas;

        $datas['type'] = 'resource';
        $response = Http::post(config('person_api_base') . 'storePerson', $datas);
        if ($response->successful()) {
            $personModel = $response->json();

        } else {
            $errorCode = $response->status();
            $errorMessage = $response->body();
            dd("Error: $errorCode - $errorMessage");
        }

        if ($personModel['message'] == "Success") {

            $personDatas = $personModel['data'];
            $uid = $personDatas['uid'];
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

            $saveResourceModel = $this->ResourceInterface->saveResource($allModels);
            return $this->commonService->sendResponse($saveResourceModel, true);
        }
    }
    public function convertToResourceModel($datas, $uid)
    {
        if (isset($datas->personUid)) {
            $model = $this->ResourceInterface->findResourceByUid($datas->personUid);
            $model->uid = $uid;
        } else {
            $model = new HrmResource();
            $model->uid = $uid;
        }
        $model->resource_code = isset($datas->resourceCode) ? $datas->resourceCode : null;
        return $model;
    }
    public function convertToResourceTypeDetailModel($datas)
    {
        $model = new HrmResourceTypeAffinity();
        $model->resource_type_id = isset($datas->resourceTypeId) ? $datas->resourceTypeId : null;
        return $model;
    }
    public function convertToResourceDesignationModel($datas)
    {

        $model = new HrmResourceDesignation();
        $model->designation_id = $datas->designationId;
        return $model;
    }
    public function convertToResourceService($datas)
    {
        $model = new HrmResourceSr();
        $model->hrm_resource_activity_status_id = isset($datas->resourceActivityStatusId) ? $datas->resourceActivityStatusId : 1;
        return $model;
    }
    public function convertToResourceServiceDetails($datas)
    {
        $model = new HrmResourceSrAffinity();
        $model->activity_id = isset($datas->activityId) ? $datas->activityId : 1;
        if (isset($datas->resourceJoinDate)) {
            $date = date('Y-m-d', strtotime($datas->resourceJoinDate));
        }
        $model->date = isset($date) ? $date : null;
        $model->reason = isset($datas->reason) ? $datas->reason : null;
        return $model;
    }
    public function resourceMobileOtp($datas, $orgId)
    {

        log::info('hrmResourceService  ->resourceMobileOtp ' . json_encode($datas));
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $response = Http::post(config('person_api_base') . 'personMobileOtp', $datas);
        if ($response->successful()) {
            $responseData = $response->json();
            if ($responseData['success'] == true) {
                $result = ['type' => 1, 'status' => "OtpSuccessfully", 'datas' => $datas];
            } else {
                $result = ['type' => 2, 'status' => "OtpFailed", 'datas' => "Mobile Not Found"];
            }
            return $this->commonService->sendResponse($result, true);
        } else {
            $errorCode = $response->status();
            $errorMessage = $response->body();
            dd("Error: $errorCode - $errorMessage");
        }
    }

    public function resourceMobileOtpValidate($datas, $orgId)
    {

        $datas = (object) $datas;
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $response = Http::post(config('person_api_base') . 'getPersonMobileNoByUid', $datas);
        if ($response->successful()) {
            $responseData = $response->json();
            if ($responseData['data']['original']['success'] == true) {
                $mobileData = $responseData['data']['original']['data'];
            } else {
                return $this->commonService->sendResponse('mobileNo Not Found', false);
            }
        } else {
            $errorCode = $response->status();
            $errorMessage = $response->body();
            dd("Error: $errorCode - $errorMessage");
        }
        if ($mobileData['otp_received'] == $datas->otp) {
            $resourceMaster = $this->resourceAndPersonMasterDatas($datas->uid);
            $result = ['type' => 1,
                'status' => "OTP Successfully", 'resourceMaster' => $resourceMaster];

        } else {
            $result = ['Status' => 'Invalid OTP', 'datas' => null];
        }

        return $this->commonService->sendResponse($result, true);
    }
    public function resourceEmailOtp($datas, $orgId)
    {
        $datas = (object) $datas;
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $response = Http::post(config('person_api_base') . 'resendOtpForSecondaryEmail', $datas);
        if ($response->successful()) {
            $responseData = $response->json();
            if ($responseData['data']['type'] == 1) {
                $result = ['type' => 1, 'status' => "OtpSuccessfully", 'datas' => $datas];
            } else {
                $result = ['type' => 2, 'status' => "OtpFailed", 'datas' => "Email Not Found"];
            }
            return $this->commonService->sendResponse($result, true);
        } else {
            $errorCode = $response->status();
            $errorMessage = $response->body();
            dd("Error: $errorCode - $errorMessage");
        }
    }

    public function resourceAndPersonMasterDatas($uid)
    {
        if ($uid) {
            $response = Http::get(config('person_api_base') . 'getPersonMasterData');
            if ($response->successful()) {
                $responseData = $response->json();
                $MasterData = $responseData['data'];
            } else {
                $errorCode = $response->status();
                $errorMessage = $response->body();
                dd("Error: $errorCode - $errorMessage");
            }
            $response = Http::post(config('person_api_base') . 'getPersonPrimaryDataByUid', $uid);
            if ($response->successful()) {
                $responseData = $response->json();
                if ($responseData['data']['original']['success'] == true) {
                    $personPrimaryData = $responseData['data']['original']['data'];
                } else {
                    return $this->commonService->sendResponse('Uid Not Found', false);
                }
            } else {
                $errorCode = $response->status();
                $errorMessage = $response->body();
                dd("Error: $errorCode - $errorMessage");
            }

            $response = Http::post(config('person_api_base') . 'personMotherTongueByUid', $uid);
            if ($response->successful()) {
                $responseData = $response->json();
                $personMotherTongues = $responseData['data']['original']['data'];
            } else {
                $errorCode = $response->status();
                $errorMessage = $response->body();
                dd("Error: $errorCode - $errorMessage");
            }
            $response = Http::post(config('person_api_base') . 'personGetAnniversaryDate', $uid);
            if ($response->successful()) {
                $responseData = $response->json();
                $personAnniversaryDate = $responseData['data']['original']['data'];
            } else {
                $errorCode = $response->status();
                $errorMessage = $response->body();
                dd("Error: $errorCode - $errorMessage");
            }

            $response = Http::post(config('person_api_base') . 'personAddressByUid', $uid);
            if ($response->successful()) {
                $responseData = $response->json();
                $personAddress = $responseData['data']['original']['data'];
            } else {
                $errorCode = $response->status();
                $errorMessage = $response->body();
                dd("Error: $errorCode - $errorMessage");
            }

            $department = $this->DepartmentInterface->findAll();
            $designation = $this->DesignationInterface->findAll();
            $resourceType = $this->HrTypeInterface->index();

            return [
                'department' => $department,
                'designation' => $designation,
                'resourceType' => $resourceType,
                'MasterData' => $MasterData,
                'personPrimaryData' => $personPrimaryData,
                'personMotherTongues' => $personMotherTongues,
                'personAnniversaryDate' => $personAnniversaryDate,
                'personAddress' => $personAddress,
            ];
        }
    }
    public function resourceEmailOtpValidate($datas, $orgId)
    {
        $datas = (object) $datas;
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $response = Http::post(config('person_api_base') . 'getPersonEmailByUidAndEmail', $datas);
        if ($response->successful()) {
            $responseData = $response->json();

            if ($responseData['data']['original']['success'] == true) {
                $emailData = $responseData['data']['original']['data'];
            } else {
                return $this->commonService->sendResponse('Email Not Found', false);
            }
        } else {
            $errorCode = $response->status();
            $errorMessage = $response->body();
            dd("Error: $errorCode - $errorMessage");
        }
        if ($emailData['otp_received'] == $datas->otp) {
            $resourceMaster = $this->resourceAndPersonMasterDatas($datas->uid);
            $result = ['type' => 1,
                'status' => "OTP Successfully", 'resourceMaster' => $resourceMaster];
        } else {
            $result = ['Status' => 'Invalid OTP', 'datas' => null];
        }
        return $this->commonService->sendResponse($result, true);
    }
    public function masterDatasForResource($datas, $orgId)
    {
        $datas = (object) $datas;
        $dbConnection = $this->commonService->getOrganizationDatabaseByOrgId($orgId);
        $resourceMaster=$this->resourceAndPersonMasterDatas($datas->uid);
        $response = Http::post(config('person_api_base') . 'getPersonPrimaryDataByUid', $datas->uid);
            if ($response->successful()) {
                $responseData = $response->json();
                if ($responseData['data']['original']['success'] == true) {
                    $resourceMaster['personDetails'] = $responseData['data']['original']['data'];
                } else {
                    return $this->commonService->sendResponse('Uid Not Found', false);
                }
            } else {
                $errorCode = $response->status();
                $errorMessage = $response->body();
                dd("Error: $errorCode - $errorMessage");
            }
            return $this->commonService->sendResponse($resourceMaster, true);
    }
}
