<?php

namespace App\Http\Controllers\Api\Version_1\Interface\Hrm\Master;

interface HrTypeInterface
{
    public function index();
    public function store($data);
    public function findById($id); 
    public function destroyForHrTypeByUid($id);


}