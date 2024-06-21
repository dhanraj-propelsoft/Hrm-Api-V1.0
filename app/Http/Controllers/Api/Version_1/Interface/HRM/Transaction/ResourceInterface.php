<?php

namespace App\Http\Controllers\Api\Version_1\Interface\HRM\Transaction;

interface ResourceInterface
{

    public function findResourceByUid($uid);
    public function findAll();
     public function saveResource($allModels);
}
