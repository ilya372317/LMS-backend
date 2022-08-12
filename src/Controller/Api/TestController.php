<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Factory\File\ImageManagerFactory;
use App\Service\File\FileManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route(path: "/api/test-image", name: "image-test", methods: 'POST')]
    public function testImageUpload(Request $request, ImageManagerFactory $managerFactory): Response
    {
        $uploadedFile = $request->files->get('image');
        $fileManager = new FileManager($managerFactory, $uploadedFile);
        $fileName = $fileManager->save(['relatedEntity'=> User::class]);

        return $this->json([
            'file-name' => $fileName,
        ]);
    }
}