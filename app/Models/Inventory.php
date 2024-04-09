<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $table = 'inventory_items';
    protected $connection;

    public function __construct(){
        parent::__construct();
        $this->connection = "mysql_external";
    }
}
