<?php

namespace App\Enums;

enum ArticleCategory: string
{
    case Tech = 'Tech';
    case Sport = 'Sport';
    case Finance = 'Finance';
    case Politics = 'Politics';
    case Health = 'Health';
    case Travel = 'Travel';
    case Food = 'Food';
    case Gaming = 'Gaming';
    case Science = 'Science';
    case Education = 'Education';
    case Culture = 'Culture';
    case Auto = 'Auto';
    case Environment = 'Environment';
    case Fashion = 'Fashion';
    case Business = 'Business';

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}