<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        $password = $parameters['password'];
        $username = $parameters['username'];
        $email = $parameters['email'];

        $user = new User();

        $user->setUsername($username);
        $user->setPassword($passwordHasher->hashPassword($user, $password));
        $user->setEmail($email);

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            return $this->json([
                'errors' => $errors,
            ]);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            $user->getUsername(),
            $user->getEmail(),
        ]);
    }

    #[Route(path: "/api/login", name: 'api_login', methods: 'POST')]
    public function login(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ): Response {

        $parameters = json_decode($request->getContent(), true);

        $user = $userRepository->findOneBy([
            'username' => $parameters['username']
        ]);
        $inputPassword = $parameters['password'];

        $userDoesNotExist = !$user;
        $passwordIsWrong = !$passwordHasher->isPasswordValid($user, $inputPassword);

        if ($userDoesNotExist || $passwordIsWrong) {
            return $this->json([
                'error' => 'user data no valid',
            ]);
        }

        $payload = [
            'user' => $user->getUsername(),
            'exp' => (new \DateTime())->modify('+5 minutes')->getTimestamp(),
        ];

        $jwt = JWT::encode($payload, $this->getParameter('jwt_secret'), 'HS256');
        return $this->json([
            'message' => 'you success authenticate',
            'token' => sprintf('Bearer %s', $jwt),
        ]);

    }
}
