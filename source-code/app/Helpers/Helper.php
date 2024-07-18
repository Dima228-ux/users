<?php


namespace App\Helpers;

use Exception;

/**
 * Class Helper
 * @package App\Helpers
 */
class Helper
{
    public const END_VERIFICATION = 1;
    public const TIME_ZONE = 'Europe/Moscow';

    /**
     * @param $model
     * @param $parameter
     * @return int
     * @throws Exception
     */
    public static function generateCode($model, $parameter): int
    {
        do {
            $code = random_int(100000, 999999);
        } while ($model::where($parameter, "=", $code)->first());

        return $code;
    }
}
