<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Firebase\JWT\JWT;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    #[Route('/api/register', name: 'api_register', methods: 'POST')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $parameters = json_decode($request->getContent(), true);

        $password = $parameters['password'];
        $username = $parameters['username'];
        $email = $parameters['email'];

        //TODO: Replace creating of user in other class
        $user = new User();

        //TODO: Make costume validator to check value is unique
        $user->setUsername($username);
        $user->setPassword($passwordHasher->hashPassword($user, $password));
        $user->setEmail($email);

        //TODO: Replace persisting in other class
        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

        } catch (ORMException $ORMException) {
            $this->logger->error('failed to save user data [' . $user . ']');
            $this->logger->error($ORMException->getMessage());
            return $this->json([
                'error' => "failed to save user data",
            ]);
        }

        return $this->json([
            $user->getUsername(),
            $user->getEmail(),
        ]);
    }

    #[Route(path: "/api/login", name: 'api_login', methods: 'POST')]
    public function login(
        Request                     $request,
        UserRepository              $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        $user = $userRepository->findOneBy([
            'username' => $request->get('username')
        ]);
        $inputPassword = $request->get('password');

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
