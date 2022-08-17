<?php

namespace App\Controller\Api\Course;

use App\Service\Course\CourseServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseCrudController extends AbstractController
{
    private CourseServiceInterface $courseService;

    public function __construct(CourseServiceInterface $courseService)
    {
        $this->courseService = $courseService;
    }

    #[Route(path: "/api/course/index")]
    public function index(Request $request): Response
    {
        $courses = $this->courseService->getCourseList($request);
        return $this->json($courses);
    }
}