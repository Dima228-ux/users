<?php


namespace App\Services\UserService;

use App\Helpers\Helper;
use App\Models\HistoryCodes;
use App\Models\User;
use App\Services\VerificationServices\VerificationFactory;
use Carbon\Carbon;

/**
 * Class UserService
 * @package App\Services\UserService
 */
class UserService
{
    /**
     * @param UserDTO $userData
     * @return User
     */
    public function getUser(UserDTO $userData): User
    {
        if ($user = User::query()->where('email', $userData->email)->first()) {
            return $user;
        }

        return $this->createUser($userData);
    }

    /**
     * @param UserDTO $userData
     * @return User
     */
    public function createUser(UserDTO $userData): User
    {
        return User::create($userData->toArray());
    }

    /**
     * @param User $user
     * @param int $type
     * @param array $settings
     * @return int|string
     */
    public function updateSettings(User $user, int $type, array $settings): int|string
    {
        $code = Helper::generateCode(HistoryCodes::class, 'code');
        if (!$class = VerificationFactory::createVerification($type)) {
            return 'Error';
        }

        if ($class->send($user, $code)) {
            HistoryCodes::insert(
                [
                    'user_id' => $user->id,
                    'settings' => json_encode($settings),
                    'date_expiration' => Carbon::now()->addMinute(Helper::END_VERIFICATION)->timezone(
                        Helper::TIME_ZONE
                    ),
                    'code' => $code,
                ]
            );
        }
        return $code;
    }

    /**
     * @param User $user
     * @param int $code
     * @return string[]
     */
    public function verificationSettings(User $user, int $code): array
    {
        $date_expiration = Carbon::now()->timezone("Europe/Moscow");
        if ($historyCodes = HistoryCodes::where('user_id', $user->id)->where('code', $code)
            ->where('date_expiration', '>=', $date_expiration)->first()) {
            $user->update(['settings' => $historyCodes->settings]);
            return ['status' => 'success'];
        }
        return ['status' => 'error', 'message' => 'the code has expired'];
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function getUserByEmail(string $email): ?User
    {
        return User::firstWhere('email', $email);
    }

    /**
     * @param User $user
     * @param UserDTO $userData
     */
    public function updateUser(User $user, UserDTO $userData): void
    {
        if ($userData->email == null) {
            $userData->email = $user->email;
        }

        if ($userData->password == null) {
            $userData->password = $user->password;
        }

        $user->update($userData->toArray());
    }

}
