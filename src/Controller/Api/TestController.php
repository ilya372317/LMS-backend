<?php

namespace App\Controller\Api;

use App\Image\LessonImageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route(path: '/api/test-image', name: 'test-image')]
    public function testImage(Request $request, LessonImageManager $courseImageManager): Response
    {
        $image = $request->files->get('image');

        $result = $courseImageManager->save($image);

        dd($result);
    }
}