<?php
namespace FacturaScripts\Plugins\Randomizer\Lib\Random;

trait GetIdsTrait
{
    private static $ids = [];

    public static function getIds(): array
    {
        return self::$ids;
    }

    public static function setId($id): void
    {
        self::$ids[] = $id;
    }
}