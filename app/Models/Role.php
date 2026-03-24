<?php

namespace App\Models;

use Laratrust\Models\Role as RoleModel;

class Role extends RoleModel
{
    public $protected = ['name','display_name','description'];
    public function users()
    {
        return $this->belongsToMany(User::class,'user_id');
    }
}
