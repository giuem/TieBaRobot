<?php


namespace Middleware;

use Auth\Auth;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class AuthMiddleware extends BaseMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        if(!Auth::check()) {
            return $response->withRedirect($this->container->router->pathFor('auth.index'));
        }

        return next($request, $response);
    }

}