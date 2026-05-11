<?php
declare(strict_types=1);
namespace App\Models;
use App\Core\Model;
use App\Core\Database;

class Progress extends Model
{
    protected static string $table = 'progress';

    public static function forUser(int $userId): array
    {
        return Database::fetchAll(
            "SELECT p.*, m.name as module_name, m.icon, m.slug
             FROM progress p
             JOIN modules m ON m.id = p.module_id
             WHERE p.user_id = ?
             ORDER BY m.sort_order",
            [$userId]
        );
    }

    public static function recalculate(int $userId, int $moduleId): void
    {
        $row = Database::fetchOne(
            "SELECT 
                COUNT(e.id) as total,
                COUNT(s.id) as done,
                IFNULL(SUM(IFNULL(s.teacher_score, s.score)), 0) as scored,
                COUNT(e.id) * 100 as max_possible
             FROM exercises e
             LEFT JOIN submissions s ON s.exercise_id = e.id AND s.user_id = ?
             WHERE e.module_id = ? AND e.is_active = 1",
            [$userId, $moduleId]
        );

        if (!$row) return;

        $percent = $row['max_possible'] > 0
            ? (int)round($row['scored'] / $row['max_possible'] * 100)
            : 0;

        Database::query(
            "INSERT INTO progress (user_id, module_id, total_score, max_possible, exercises_done, exercises_total, percent)
             VALUES (?, ?, ?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
               total_score = VALUES(total_score),
               max_possible = VALUES(max_possible),
               exercises_done = VALUES(exercises_done),
               exercises_total = VALUES(exercises_total),
               percent = VALUES(percent),
               updated_at = NOW()",
            [$userId, $moduleId, $row['scored'], $row['max_possible'], $row['done'], $row['total'], $percent]
        );
    }

    public static function overallPercent(int $userId): int
    {
        $row = Database::fetchOne(
            "SELECT IFNULL(SUM(total_score) / NULLIF(SUM(max_possible), 0) * 100, 0) as avg
             FROM progress WHERE user_id = ?",
            [$userId]
        );
        return (int)round((float)($row['avg'] ?? 0));
    }
}
