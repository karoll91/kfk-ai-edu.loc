<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class User extends Model
{
    protected static string $table = 'users';

    public static function findByEmail(string $email): array|false
    {
        return static::findBy('email', $email);
    }

    public static function activeStudents(): array
    {
        return Database::fetchAll(
            "SELECT * FROM users WHERE role = 'student' AND is_active = 1 ORDER BY name"
        );
    }

    public static function allWithStats(): array
    {
        return Database::fetchAll(
            "SELECT u.*, 
                COUNT(DISTINCT s.id) as submissions_count,
                IFNULL(AVG(s.score), 0) as avg_score
             FROM users u
             LEFT JOIN submissions s ON s.user_id = u.id
             GROUP BY u.id
             ORDER BY u.created_at DESC"
        );
    }

    public static function updatePassword(int $id, string $hash): void
    {
        static::update($id, ['password_hash' => $hash]);
    }

    public static function verifyEmail(int $id): void
    {
        static::update($id, ['email_verified_at' => date('Y-m-d H:i:s')]);
    }
}
