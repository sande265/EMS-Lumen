<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    protected $fillable = [
        'name', 'email', 'designation', 'department', 'blood_group', 'gender', "role", "phone_no", "branch", "city", "country", "status", "supervisor"
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}
