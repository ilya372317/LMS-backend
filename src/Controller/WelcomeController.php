<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    #[Route(path: "/", name: 'homepage')]
    public function welcome(): Response
    {
        return $this->render('test.html.twig');
    }
}