<?php

namespace App\Helper\EnumModifier;

/**
 * Should implement only by enums.
 * In other way it steel be work, but un expected way.
 *
 * @author Ilya Otinov
 */
trait EnumArrayAbility
{
    /**
     * Convert standard enum cases to map.
     *
     * @return array
     */
    public static function toArray(): array
    {
        $result = [];
        $statusList = self::getCases();
        foreach ($statusList as $status) {
            $result[$status->name] = $status->value;
        }

        return $result;
    }

    /**
     * Convert standard enum cases to map, where keys in lower cases.
     *
     * @return array
     */
    public static function toPreviewArray(): array
    {
        $result = [];
        $statusList = self::getCases();
        foreach ($statusList as $status) {
            $result[strtolower($status->name)] = $status->value;
        }

        return $result;
    }

    /**
     * Should return array of enum cases.
     * Probably you always want use EnumClass::cases() method.
     * This method need, for ability to use this trait to all classes.
     *
     * Used template method pattern
     *
     * @return array
     */
    abstract private static function getCases(): array;
}