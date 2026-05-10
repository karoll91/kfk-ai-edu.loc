<?php
declare(strict_types=1);
namespace App\Models;
use App\Core\Model;
use App\Core\Database;

class Module extends Model
{
    protected static string $table = 'modules';

    public static function allActive(): array
    {
        return Database::fetchAll(
            "SELECT * FROM modules WHERE is_active = 1 ORDER BY sort_order"
        );
    }

    public static function findBySlug(string $slug): array|false
    {
        return static::findBy('slug', $slug);
    }

    public static function withExerciseCount(): array
    {
        return Database::fetchAll(
            "SELECT m.*, COUNT(e.id) as exercise_count
             FROM modules m
             LEFT JOIN exercises e ON e.module_id = m.id AND e.is_active = 1
             WHERE m.is_active = 1
             GROUP BY m.id
             ORDER BY m.sort_order"
        );
    }
}
