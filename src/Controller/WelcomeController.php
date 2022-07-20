<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class WelcomeController extends AbstractController
{
    #[Route(path: "/", name: 'homepage')]
    public function welcome(): Response
    {
        return new Response('it`s work');
    }
}