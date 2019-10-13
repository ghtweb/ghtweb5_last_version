<?php

namespace app\services;

interface PasswordManagerInterface
{
    public function encode(string $password): string;
    public function generate(int $length): string;
    public function isValidated(string $password, string $passwordHash): bool;
}
