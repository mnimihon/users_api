<?php

namespace app\Module;

use app\Services\Auth;

class Tokens extends Module
{
    public $id;
    public $token;
    public $auth_user_id;

    public function update(AuthUsers $authUsers)
    {
        $token = $this->getByConditions(['auth_user_id' => $authUsers->id]);
        /** @var Tokens $token */
        $tokenValue = Auth::generateToken();
        if ($token) {
            $token->token = $tokenValue;
            $token->save();
        } else {
            $token = new Tokens;
            $token->token = $tokenValue;
            $token->auth_user_id = $authUsers->id;
            $token->save();
        }
        return $token->token;
    }
}