<?php

namespace SyntoraPHP\App\Models;

use Medoo\Medoo;

class Database
{
    public function connect() {
        $env = new Env('.env');

        $database = new Medoo([
            'type' => 'mysql',
            'host' => $env->get('DB_HOST'),
            'database' => $env->get('DB_NAME'),
            'username' => $env->get('DB_USER'),
            'password' => $env->get('DB_PASSWORD'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'port' => $env->get('DB_PORT'),
        ]);

        return $database;
    }
}