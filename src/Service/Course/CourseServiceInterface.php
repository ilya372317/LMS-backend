<?php

namespace App\Service\Course;

use App\DTO\Request\ResponseMessage;
use App\Entity\Course;
use Symfony\Component\HttpFoundation\Request;

interface CourseServiceInterface
{
    public function getCourseList(Request $request): iterable;

    public function findById(int $id): ?Course;
}