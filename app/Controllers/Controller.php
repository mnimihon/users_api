<?php

namespace app\Controllers;

use app\Core\Request;
use app\Core\Response;
use app\Module\AuthUsers;
use app\Module\Tokens;
use app\Services\Auth;

abstract class Controller
{
    private static $allowMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

    const AUTH_ACTION = '/auth';
    const STATUS_CODE_SUCCESS = 200;
    const STATUS_CODE_CREATED = 201;
    const STATUS_CODE_UNAUTHORIZED = 401;
    const STATUS_CODE_NOT_FOUND = 404;
    const STATUS_CODE_INTERNAL_SERVER_ERROR = 500;
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function run()
    {
        if (!in_array($this->request->getRequestMethod(), self::$allowMethods)) die;
        $this->auth();
        $action = self::getAction($this->request->getUri());
        if (method_exists($this, $action)) {
            $data = $this->request->getData(file_get_contents('php://input'));
            $this->$action($data);
        } else {
            (new Response)
                ->setStatusCode(self::STATUS_CODE_NOT_FOUND)
                ->setJson(['description' => 'Methods not found'])
                ->send();
        }
    }

    private static function getAction(string $uri): string
    {
        return 'action' . ucfirst(str_replace('/', '', $uri));
    }

    private function auth()
    {
        if ($this->request->getUri() != self::AUTH_ACTION && !Auth::validateToken()) {
            (new Response)
                ->setStatusCode(self::STATUS_CODE_UNAUTHORIZED)
                ->setJson(['description' => 'User unauthorized'])
                ->send();
            die;
        }
    }

    public function actionAuth(array $data)
    {
        /** @var $authUsers AuthUsers */
        $authUsers = (new AuthUsers)->getByUnique($data);
        if ($authUsers) {
            (new Response)
                ->setStatusCode(self::STATUS_CODE_CREATED)
                ->setJson(['token' => (new Tokens)->update($authUsers)])
                ->send();
        } else {
            (new Response)
                ->setStatusCode(self::STATUS_CODE_NOT_FOUND)
                ->setJson(['description' => 'AuthUser not found'])
                ->send();
            die;
        }
    }
}