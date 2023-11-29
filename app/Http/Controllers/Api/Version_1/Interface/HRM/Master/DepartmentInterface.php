<?php

namespace App\Http\Controllers\Api\Version_1\Interface\Hrm\Master;

interface DepartmentInterface
{
    public function findAll();
    public function findById($id);
    public function store($data);
    public function getParentDeptExceptThisId($id);
    public function destroyForDepartmentByUid($id);

}
