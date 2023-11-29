<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationDatabase extends Model
{
    use HasFactory;

    protected $table = 'organization_databases';
    public function ParentOrganization()
    {
        return $this->belongsTo(Organization::class, 'org_id', 'id');
    }
}
