<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class ApiAccessDeniedHandler implements AccessDeniedHandlerInterface
{

    /**
     * @inheritDoc
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        return new Response(json_encode([
            'type' => 'error',
            'title' => 'Access denied',
            'message' => 'You have no permissions to access this request',
            'code' => 403
        ]),
            403,
            [
                'content-type' => 'application/json'
            ]
        );
    }
}