<?php

namespace App\Tests\Integrated\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthControllerTests extends WebTestCase
{
    public function testRegister(): void
    {
        $client = static::createClient();

        $alreadyRegisteredUser = new User();
        $alreadyRegisteredUser->setUsername('ilya');
        $alreadyRegisteredUser->setPassword('123');

        $this->createIfNotExistFakeUser($alreadyRegisteredUser);

        $username = 'ilya123';
        $password = '123123';

        $this->deleteUserBeforeRegister($username);

        $client->request('POST', '/api/register', content: json_encode([
            'password' => $password,
            'username' => $username
        ]));

        $content = $client->getResponse()->getContent();
        $contentArray = json_decode($content);
        $userFromResponse = $contentArray[0];

        $this->assertIsObject($userFromResponse);
        $this->assertObjectHasAttribute('username', $userFromResponse);
        $this->assertObjectHasAttribute('id', $userFromResponse);
        $this->assertObjectHasAttribute('password', $userFromResponse);
    }

    public function testRegisterWithExistAccount(): void
    {
        $client = static::createClient();

        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        $username = 'ilya';
        $password = '123';
        $alreadyRegisteredUser = new User();
        $alreadyRegisteredUser->setUsername($username);
        $alreadyRegisteredUser->setPassword($passwordHasher->hashPassword($alreadyRegisteredUser, $password));
        $this->createIfNotExistFakeUser($alreadyRegisteredUser);

        $client->request('POST', '/api/register', content: json_encode([
            'username' => $username,
            'password' => $password
        ]));

        $content = $client->getResponse()->getContent();
        $contentArray = json_decode($content, true);

        $this->assertResponseStatusCodeSame(401);
        $this->assertArrayHasKey('errors', $contentArray);
        $this->assertArrayHasKey('detail', $contentArray['errors']);
        $this->assertArrayHasKey('type', $contentArray['errors']);
        $this->assertArrayHasKey('title', $contentArray['errors']);
    }

    public function testRegisterWithShortUsername(): void
    {
        $client = static::createClient();

        $username = 'a';
        $password = '123';
        $this->deleteUserBeforeRegister($username);

        $client->request('POST', '/api/register', content: json_encode([
            'username' => $username,
            'password' => $password
        ]));

        $contentArray = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(401);
        $this->assertArrayHasKey('errors', $contentArray);
        $this->assertArrayHasKey('type', $contentArray['errors']);
        $this->assertArrayHasKey('title', $contentArray['errors']);
        $this->assertArrayHasKey('detail', $contentArray['errors']);
        $this->assertContains('Validation Failed', $contentArray['errors']);
        $this->assertContains('username: Username should have at least 3 symbols', $contentArray['errors']);
    }

    public function testRegisterWithShortPassword(): void
    {
        $client = static::createClient();
        $username = 'ilya';
        $password = '1';

        $client->request('POST', '/api/register', content: json_encode([
            'username' => $username,
            'password' => $password
        ]));

        $contentArray = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(401);
        $this->assertArrayHasKey('errors', $contentArray);
        $this->assertArrayHasKey('type', $contentArray['errors']);
        $this->assertArrayHasKey('title', $contentArray['errors']);
        $this->assertArrayHasKey('detail', $contentArray['errors']);
        $this->assertContains('username', $contentArray['errors']['violations'][0]);
    }

    public function testRegisterWithLongUsername(): void
    {
        $client = static::createClient();
        $username = 'qweqqweqweqweqweqweqweqweqweqweqweqwesadasdasdasdqweqqweqweqweqweqweqweqweqweqweqweqwesadasdasdasd
        qweqqweqweqweqweqweqweqweqweqweqweqwesadasdasdasdqweqqweqweqweqweqweqweqweqweqweqweqwesadasdasdasd
        qweqqweqweqweqweqweqweqweqweqweqweqwesadasdasdasdqweqqweqweqweqweqweqweqweqweqweqweqwesadasdasdasd
        qweqqweqweqweqweqweqweqweqweqweqweqwesadasdasdasdqweqqweqweqweqweqweqweqweqweqweqweqwesadasdasdasd
        qweqqweqweqweqweqweqweqweqweqweqweqwesadasdasdasdqweqqweqweqweqweqweqweqweqweqweqweqwesadasdasdasd
        qweqqweqweqweqweqweqweqweqweqweqweqwesadasdasdasdqweqqweqweqweqweqweqweqweqweqweqweqwesadasdasdasd
        qweqqweqweqweqweqweqweqweqweqweqweqwesadasdasdasdqweqqweqweqweqweqweqweqweqweqweqweqwesadasdasdasd';
        $password = '123';

        $this->deleteUserBeforeRegister($username);
        $client->request('POST', '/api/register', content: json_encode([
            'username' => $username,
            'password' => $password
        ]));

        $contentArray = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(401);
        $this->assertArrayHasKey('errors', $contentArray);
        $this->assertArrayHasKey('type', $contentArray['errors']);
        $this->assertArrayHasKey('title', $contentArray['errors']);
        $this->assertArrayHasKey('detail', $contentArray['errors']);
        $this->assertContains('username: Username should have less then 250 symbols', $contentArray['errors']);
    }

    public function testRegisterWithEmail(): void
    {
        $client = static::createClient();
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $username = 'ilya';
        $password = '123';
        $email = 'ilya.otinov@gmail.com';
        $this->deleteUserBeforeRegister($username);

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($passwordHasher->hashPassword($user, $password));
        $user->setEmail($email);

        $client->request('POST', '/api/register', content: json_encode([
            'username' => $username,
            'password' => $password,
            'email' => $email
        ]));

        $contentArray = json_decode($client->getResponse()->getContent());

        $registeredUser = $contentArray[0];

        $this->assertResponseStatusCodeSame(200);
        $this->assertObjectHasAttribute('email', $registeredUser);
        $this->assertEquals($email, $registeredUser->email);
    }

    public function testLogin(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $passwordHasher = $container->get(UserPasswordHasherInterface::class);

        $user = new User();
        $user->setUsername('ilya');
        $user->setPassword($passwordHasher->hashPassword($user, '123'));

        $this->createIfNotExistFakeUser($user, $container);

        $client->request('POST', '/api/login',
            server: [
                'CONTENT_TYPE' => 'application/json',
            ],
            content: json_encode([
                'username' => 'ilya',
                'password' => '123'
            ]));

        $responseContent = $client->getResponse()->getContent();
        $responseArray = json_decode($responseContent, true);

        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $responseArray);
    }

    public function testFailedLogin(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $passwordHasher = $container->get(UserPasswordHasherInterface::class);
        $username = 'ilya';
        $password = '123';
        $wrongUsername = 'ilya123';

        $this->deleteUserBeforeRegister($wrongUsername);

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($passwordHasher->hashPassword($user, $password));

        $this->createIfNotExistFakeUser($user);

        $client->request('POST', '/api/login',
            server: [
                'CONTENT_TYPE' => 'application/json'
            ],
            content: json_encode([
            'username' => $wrongUsername,
            'password' => $password
        ]));

        $responseContent = $client->getResponse()->getContent();
        $contentArray = json_decode($responseContent, true);

        $this->assertResponseStatusCodeSame(401);
        $this->assertArrayHasKey('message', $contentArray);
        $this->assertContains('Invalid credentials.', $contentArray);
    }

    public function testLoginWithIncorrectPassword(): void
    {
        $client = static::createClient();

        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        $username = 'ilya';
        $password = '123';
        $wrongPassword = '321';
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($passwordHasher->hashPassword($user, $password));

        $this->createIfNotExistFakeUser($user);

        $client->request('POST', '/api/login', server: [
            'CONTENT_TYPE' => 'application/json'
        ], content: json_encode([
            'username' => $username,
            'password' => $wrongPassword
        ]));

        $content = $client->getResponse()->getContent();
        $contentArray = json_decode($content, true);

        $this->assertResponseStatusCodeSame(401);
        $this->assertArrayHasKey('message', $contentArray);
        $this->assertContains('Invalid credentials.', $contentArray);
    }

    private function createIfNotExistFakeUser(User $user): void
    {
        $container = self::getContainer();
        $userRepository = $container->get(UserRepository::class);
        $userFromDatabase = $userRepository->findOneBy(['username' => $user->getUsername()]);

        if ($userFromDatabase !== null) {
            $userRepository->remove($userFromDatabase, true);
        }

        $entityManager = $container->get(EntityManagerInterface::class);

        $entityManager->persist($user);
        $entityManager->flush();
    }

    private function deleteUserBeforeRegister(string $username): void
    {
        $container = self::getContainer();
        $userRepository = $container->get(UserRepository::class);
        $user = $userRepository->findOneBy([
            'username' => $username
        ]);
        if ($user) {
            $userRepository->remove($user, true);
        }
    }
}