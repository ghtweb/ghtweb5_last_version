<?php

namespace app\services;

class YiiPasswordManager implements PasswordManagerInterface
{
    public function encode(string $password): string
    {
        return \CPasswordHelper::hashPassword($password);
    }

    public function generate(int $length): string
    {
        return randomString($length);
    }

    public function isValidated(string $password, string $passwordHash): bool
    {
        return \CPasswordHelper::verifyPassword($password, $passwordHash);
    }
}
