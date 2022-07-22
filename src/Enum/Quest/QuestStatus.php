<?php

namespace App\Enum\Quest;

use App\Helper\EnumModifier\EnumArrayAbility;

enum QuestStatus: int
{
    use EnumArrayAbility;

    case ACTIVE = 1;
    case FINISHED = 2;
    case SUSPENDED = 3;

    private static function getCases(): array
    {
        return self::cases();
    }
}