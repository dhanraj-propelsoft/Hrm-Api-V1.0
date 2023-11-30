<?php

namespace App\Http\Controllers\Api\Version_1\Interface\Hrm\Transaction;

interface ResourceInterface
{

    public function findResourceByUid($uid);
    public function findAll();
    // public function store($data);
    // public function findById($id);
    // public function getParentDeptExceptThisId($id);
    // public function saveResourceModel($data);
     public function saveResource($allModels);
}
