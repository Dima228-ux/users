<?php

namespace App\Services\UserService;

use App\Traits\HydratesProps;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserDTO
 * @package App\Services\UserService
 */
class UserDTO
{
    use HydratesProps;

    public ?string $email = null;
    public ?string $name = null;
    public ?string $password = null;
    public ?string $phone = null;
    public ?string $telegram = null;

    /**
     * @param $request
     * @return UserDTO
     */
    public static function fromRequest($request): UserDTO
    {
        return (new self())->hydrate($request->all());
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'password' => Hash::make($this->password),
            'phone' => $this->phone,
            'telegram' => $this->telegram,
        ];
    }
}
