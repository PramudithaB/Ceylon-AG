<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case MANAGER = 'manager';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::USER => 'Standard User',
            self::MANAGER => 'Manager',
        };
    }
}
