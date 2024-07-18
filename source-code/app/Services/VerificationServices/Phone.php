<?php


namespace App\Services\VerificationServices;


use App\Models\User;

/**
 * Class Phone
 * @package App\Services\VerificationServices
 */
class Phone implements Verification
{
    /**
     * @param User $user
     * @param int $code
     * @return bool
     */
    public function send(User $user, int $code): bool
    {
        return true;
    }
}
