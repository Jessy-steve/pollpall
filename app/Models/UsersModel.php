<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = "tbl_users";
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['user_name', 'email', 'password', 'active', 'remember_token', 'activation_code', 'remember_email', 'remember_password'];

}