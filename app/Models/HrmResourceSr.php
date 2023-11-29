<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrmResourceSr extends Model
{
    use HasFactory;
    protected $connection;
    
    public function __construct(){
        parent::__construct();
        $this->connection = "mysql_external";
    }
    public function ParentHrmResource()
    {
        return $this->belongsTo(HrmResource::class, 'resource_id', 'id');
    }
    public function resourceServiceDetail()
    {
        return $this->hasOne(HrmResourceSrAffinity::class, 'resource_sr_id', 'id');
    }
}
