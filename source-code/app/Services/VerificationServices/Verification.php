<?php


namespace App\Services\VerificationServices;


use App\Models\User;

/**
 * Interface Verification
 * @package App\Services\VerificationServices
 */
interface Verification
{
    /**
     * @param User $user
     * @param int $code
     * @return mixed
     */
    public function send(User $user, int $code);
}
