<?php

namespace App\Tests\Unit\Enum;

use App\Enum\Quest\QuestStatus;
use PHPUnit\Framework\TestCase;

class ModifierEnumTest extends TestCase
{
    public function testEnumToArray(): void
    {
        $array = QuestStatus::toArray();
        $this->assertIsArray($array);
        foreach (QuestStatus::cases() as $status) {
            $this->assertArrayHasKey($status->name, $array);
        }
    }
}