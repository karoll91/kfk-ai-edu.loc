<?php
declare(strict_types=1);
namespace App\Models;
use App\Core\Model;
use App\Core\Database;

class Submission extends Model
{
    protected static string $table = 'submissions';

    public static function byUserAndExercise(int $userId, int $exerciseId): array|false
    {
        return Database::fetchOne(
            "SELECT * FROM submissions WHERE user_id = ? AND exercise_id = ?",
            [$userId, $exerciseId]
        );
    }

    public static function byUser(int $userId): array
    {
        return Database::fetchAll(
            "SELECT s.*, e.title as exercise_title, m.name as module_name
             FROM submissions s
             JOIN exercises e ON e.id = s.exercise_id
             JOIN modules m ON m.id = e.module_id
             WHERE s.user_id = ?
             ORDER BY s.submitted_at DESC",
            [$userId]
        );
    }

    public static function pendingTeacherReview(): array
    {
        return Database::fetchAll(
            "SELECT s.*, u.name as user_name, u.email as user_email,
                    e.title as exercise_title, m.name as module_name
             FROM submissions s
             JOIN users u ON u.id = s.user_id
             JOIN exercises e ON e.id = s.exercise_id
             JOIN modules m ON m.id = e.module_id
             WHERE s.status IN ('submitted', 'ai_reviewed')
             ORDER BY s.submitted_at ASC"
        );
    }

    public static function saveAiFeedback(int $id, int $score, string $feedback): void
    {
        static::update($id, [
            'score'       => $score,
            'ai_feedback' => $feedback,
            'status'      => 'ai_reviewed',
            'reviewed_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
