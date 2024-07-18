<?php


namespace App\Services\VerificationServices;

/**
 * Class VerificationFactory
 * @package App\Services\VerificationServices
 */
class VerificationFactory
{
    /**
     * @param int $type
     * @return Mail|Phone|Telegram
     */
    public static function createVerification(int $type): Mail|Phone|Telegram
    {
        $class = match ($type) {
            1 => new Mail(),
            2 => new Phone(),
            3 => new Telegram(),
            default => false,
        };

        return $class;
    }
}
