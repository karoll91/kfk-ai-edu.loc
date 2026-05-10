<?php
declare(strict_types=1);
namespace App\Models;
use App\Core\Model;
use App\Core\Database;

class Exercise extends Model
{
    protected static string $table = 'exercises';

    public static function byModule(int $moduleId): array
    {
        return Database::fetchAll(
            "SELECT * FROM exercises WHERE module_id = ? AND is_active = 1 ORDER BY sort_order",
            [$moduleId]
        );
    }

    public static function withModule(int $id): array|false
    {
        return Database::fetchOne(
            "SELECT e.*, m.name as module_name, m.slug as module_slug
             FROM exercises e
             JOIN modules m ON m.id = e.module_id
             WHERE e.id = ?",
            [$id]
        );
    }

    public static function withCreativeTask(int $id): array|false
    {
        return Database::fetchOne(
            "SELECT e.*, m.name as module_name, m.slug as module_slug,
                    ct.task_type, ct.elements, ct.prompt as creative_prompt
             FROM exercises e
             JOIN modules m ON m.id = e.module_id
             LEFT JOIN creative_tasks ct ON ct.exercise_id = e.id
             WHERE e.id = ?",
            [$id]
        );
    }
}
