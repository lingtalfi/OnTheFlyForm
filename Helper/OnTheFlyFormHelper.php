<?php


namespace OnTheFlyForm\Helper;


use Bat\CaseTool;

class OnTheFlyFormHelper
{
    public static function idToPascal($id)
    {
        return CaseTool::snakeToPascal($id);
    }

    public static function getLabel($id, array $model)
    {
        $labelKey = "label" . self::idToPascal($id);
        if (array_key_exists($labelKey, $model)) {
            return $model[$labelKey];
        }
        return $id;
    }
}