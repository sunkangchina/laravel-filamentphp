<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAcl extends Model
{
    protected $table = 'user_acl';
    protected $fillable = ['user_id', 'acl'];
    public $timestamps = false;
}
