<?php

namespace App\Http\Controllers\Api\Version_1\Interface\Hrm\Master;

interface DesignationInterface
{
    public function findAll();
    public function store($data);
    public function findById($id);
    public function findByDeptId($id);
    public function destroyForDesignationByUid($id);
   
}