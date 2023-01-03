<?php 

namespace App\auth;

require_once __DIR__ . '/../config/config.php';

class JWT
{
    public function createAuth() :string
    {
        return 'Bearer ' . JWT_TOKEN;
    }
}