<?php

namespace App\Service\Course;

use Symfony\Component\HttpFoundation\Request;

interface CourseServiceInterface
{
    public function getCourseList(Request $request): iterable;
}