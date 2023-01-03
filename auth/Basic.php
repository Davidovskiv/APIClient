<?php 

namespace App\auth;

require_once __DIR__ . '/../config/config.php';

class Basic
{
    public function createAuth() :string
    {
        return 'Basic ' . base64_encode(USERNAME . ':' . PASS);
    }
}