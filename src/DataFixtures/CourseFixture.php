<?php

namespace App\DataFixtures;

use App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Generator;

class CourseFixture extends Fixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $courseGenerator = $this->generateCourses(100);
        foreach ($courseGenerator as $course) {
            $manager->persist($course);
        }

        $manager->flush();
    }

    private function generateCourses(int $countOfItems): Generator
    {
        $coursesCount = 0;

        while ($coursesCount <= $countOfItems) {
            $course = new Course();
            $course->setTitle("Title course" . $coursesCount);
            $course->setDescription('Description course' . $coursesCount);
            $course->setPrice($coursesCount);
            $coursesCount += 1;
            yield $course;
        }
    }
}