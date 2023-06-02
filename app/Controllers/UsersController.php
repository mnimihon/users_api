<?php

namespace app\Controllers;

use app\Core\Response;
use app\Module\Users;

class UsersController extends Controller
{

    public function actionAdd(array $data)
    {
        $user = new Users();
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->save();
        if ($user->id) {
            (new Response)
                ->setStatusCode(self::STATUS_CODE_CREATED)
                ->setJson(['description' => 'User added'])
                ->send();
        } else {
            (new Response)
                ->setStatusCode(self::STATUS_CODE_INTERNAL_SERVER_ERROR)
                ->setJson(['description' => 'Internal Server Error'])
                ->send();
        }
    }

    public function actionUpdate(array $data)
    {
        $user = (new Users)->getByConditions(['id' => $data['id']]);
        /** @var $user Users */
        if ($user) {
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->save();
            (new Response)
                ->setStatusCode(self::STATUS_CODE_SUCCESS)
                ->setJson([
                    'id' => $user->id,
                    'email' => $user->email,
                    'password' => $user->password,
                ])
                ->send();
        } else {
            (new Response)
                ->setStatusCode(self::STATUS_CODE_NOT_FOUND)
                ->setJson(['description' => 'User not found'])
                ->send();
        }
    }

    public function actionGet(array $data)
    {
        $user = (new Users)->getByConditions(['id' => $data['id']]);
        /** @var $user Users */
        if ($user) {
            (new Response)
                ->setStatusCode(self::STATUS_CODE_SUCCESS)
                ->setJson([
                    'id' => $user->id,
                    'email' => $user->email,
                    'password' => $user->password,
                ])
                ->send();
        } else {
            (new Response)
                ->setStatusCode(self::STATUS_CODE_NOT_FOUND)
                ->setJson(['description' => 'User not found'])
                ->send();
        }
    }

    public function actionDelete(array $data)
    {
        $user = (new Users)->getByConditions(['id' => $data['id']]);
        if ($user) {
            $user->delete();
            (new Response)
                ->setStatusCode(self::STATUS_CODE_SUCCESS)
                ->setJson(['description' => 'User deleted'])
                ->send();
        } else {
            (new Response)
                ->setStatusCode(self::STATUS_CODE_NOT_FOUND)
                ->setJson(['description' => 'User not found'])
                ->send();
        }
    }
}