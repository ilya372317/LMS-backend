<?php

namespace App\Tests\Integrated\Controller\Api;

use App\Constants\Request\RequestParameter;
use App\Entity\Course;
use App\Entity\User;
use App\Enum\User\UserRole;
use App\Repository\CourseRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CourseCrudControllerTests extends WebTestCase
{
    private UserPasswordHasherInterface $hasher;
    private UserRepository $userRepository;
    private CourseRepository $courseRepository;
    private EntityManagerInterface $entityManager;
    private KernelBrowser $client;
    private User $testUser;

    /**
     * @before
     */
    public function beforeTests(): void
    {
        $this->setupServices();
        $this->makeTestUser();
        $this->makeTestCourses();
    }

    public function testIndex(): void
    {
        $this->client->loginUser($this->testUser);
        $this->client->request('GET', '/api/course/index');
        $response = $this->client->getResponse();
        $responseArray = json_decode($response->getContent(), true);
        // Check the count of course more than 100
        $this->assertGreaterThan(100,count($responseArray));
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('id', $responseArray[0]);
        $this->assertArrayHasKey('price', $responseArray[1]);
        $this->assertArrayHasKey('title', $responseArray[2]);
        $this->assertArrayHasKey('createdAt', $responseArray[3]);
    }

    public function testIndexWithPageNumberParameter(): void
    {
        $firstCourse = $this->courseRepository->first();
        $this->client->loginUser($this->testUser);
        $this->client->request('GET', '/api/course/index', [
            RequestParameter::PAGE_NUMBER => 2,
        ]);
        $responseArray = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        //Because we not specify _items_per_page parameter, we expect ten items from response
        $this->assertCount(10, $responseArray);
        //First course id in database should not be equals in first item in response.
        // By this way we check is pagination work
        $this->assertNotEquals($firstCourse->getId(), $responseArray[0]['id']);
        $this->assertArrayHasKey('id', $responseArray[0]);
        $this->assertArrayHasKey('title', $responseArray[1]);
        $this->assertArrayHasKey('description', $responseArray[2]);
    }

    public function testIndexWithItemsPerPageParameter(): void
    {
        $this->client->loginUser($this->testUser);
        $this->client->request('GET', 'api/course/index', [
            RequestParameter::ITEM_PER_PAGE => 1
        ]);
        $responseArray = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertCount(1, $responseArray);
        $this->assertArrayHasKey('id', $responseArray[0]);
        $this->assertArrayHasKey('title', $responseArray[0]);
        $this->assertArrayHasKey('description', $responseArray[0]);
    }

    public function testIndexWithItemPerPageAndPageNumberParameters(): void
    {
        $firstCourse = $this->courseRepository->first();
        $this->client->loginUser($this->testUser);
        $this->client->request('GET', '/api/course/index', [
           RequestParameter::PAGE_NUMBER => 2,
           RequestParameter::ITEM_PER_PAGE => 3
        ]);

        $responseArray = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        //Because we not specify _items_per_page parameter, we expect ten items from response
        $this->assertCount(3, $responseArray);
        //First course id in database should not be equals in first item in response.
        // By this way we check is pagination work
        $this->assertNotEquals($firstCourse->getId(), $responseArray[0]['id']);
        $this->assertArrayHasKey('id', $responseArray[0]);
        $this->assertArrayHasKey('title', $responseArray[1]);
        $this->assertArrayHasKey('description', $responseArray[2]);
    }


    private function makeTestUser(): void
    {
        $username = 'ilya';
        $existingUser = $this->userRepository->findOneBy(['username' => $username]);
        if (isset($existingUser)) {
            $this->entityManager->remove($existingUser);
            $this->entityManager->flush();
        }

        $user = new User();
        $user->setUsername($username);
        $user->setEmail('ilya.otinov@gmail.com');
        $user->setPassword($this->hasher->hashPassword($user, 'Ilya372317'));
        $user->setRoles([UserRole::BASE_USER->value]);
        $this->testUser = $user;

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function makeTestCourses(): void
    {
        $courseGenerator = $this->generateCourses(100);
        foreach ($courseGenerator as $course) {
            $this->entityManager->persist($course);
        }

        $this->entityManager->flush();
    }

    private function generateCourses(int $countOfItems): \Generator
    {
        $coursesCount = 0;

        while ($coursesCount <= $countOfItems) {
            $course = new Course();
            $course->setTitle("Title course".$coursesCount);
            $course->setDescription('Description course'.$coursesCount);
            $course->setPrice($coursesCount);
            $coursesCount += 1;
            yield $course;
        }
    }

    private function setupServices(): void
    {
        $this->client = static::createClient();
        $container = $this->getContainer();
        $this->hasher = $container->get(UserPasswordHasherInterface::class);
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->userRepository = $container->get(UserRepository::class);
        $this->courseRepository = $container->get(CourseRepository::class);
    }

    /**
     * @after
     */
    public function afterTests(): void
    {
        $this->courseRepository->removeAll();
        $this->userRepository->removeAll();
    }
}