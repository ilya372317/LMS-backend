<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController
{
    #[Route(path: "/")]
    public function welcome(): Response
    {
        phpinfo();
        exit();
        return new Response('It`s work');
    }
}