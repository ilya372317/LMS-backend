<?php

namespace App\Service\Course;

use App\Entity\Course;
use App\Repository\CourseRepository;
use App\Service\Request\State\PaginatorContext;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class StandardCourseService implements CourseServiceInterface
{
    private CourseRepository $courseRepository;
    private PaginatorInterface $paginator;

    public function __construct(CourseRepository $courseRepository, PaginatorInterface $paginator)
    {
        $this->courseRepository = $courseRepository;
        $this->paginator = $paginator;
    }

    public function getCourseList(Request $request): iterable
    {
        $paginatorContext = new PaginatorContext($request, $this->paginator);
        $allCourses = $this->courseRepository->findAll();
        return $paginatorContext->getPagination($allCourses);
    }

    public function findById(int $id): ?Course
    {
        return $this->courseRepository->findOneBy(['id' => $id]);
    }
}