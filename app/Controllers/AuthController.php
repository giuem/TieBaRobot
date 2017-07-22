<?php


namespace Controllers;

use Auth\Auth;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class AuthController extends BaseController
{

    public function index(Request $request, Response $response)
    {
        return "aaaaa";
    }

    public function login(Request $request, Response $response) {
        $account = $request->getParsedBodyParam('account');
        $password = $request->getParsedBodyParam('password');

        if(Auth::attempt($account, $password)) {
            return $response->withJson([
                'msg' => 'ok'
            ], 200);
        } else {
            return $response->withJson([
                'msg' => 'login failed'
            ], 401);
        }
    }

}