<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/register', name: 'api_register', methods: 'POST')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator
    ): Response {
        $parameters = json_decode($request->getContent(), true);

        $user = new User();
        $password = $parameters['password'];
        $username = $parameters['username'];

        if (isset($parameters['email'])) {
            $email = $parameters['email'];
            $user->setEmail($email);
        }

        $user->setUsername($username);
        $user->setPassword($passwordHasher->hashPassword($user, $password));

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            return $this->json([
                'errors' => $errors,
            ], 401);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            $user
        ]);
    }
}
