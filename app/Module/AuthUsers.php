<?php

namespace app\Module;

class AuthUsers extends Module
{
    public $id;
    public $login;
    public $password;

    public function getByUnique(array $condition)
    {
        $condition['password'] = md5($condition['password']);
        return $this->getByConditions($condition);
    }
}