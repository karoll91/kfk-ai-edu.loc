<?php
declare(strict_types=1);
namespace App\Models;
use App\Core\Model;
use App\Core\Database;

class AiLog extends Model
{
    protected static string $table = 'ai_logs';

    public static function log(int $userId, ?int $exerciseId, string $type, string $prompt, array $result): void
    {
        static::create([
            'user_id'       => $userId,
            'exercise_id'   => $exerciseId,
            'request_type'  => $type,
            'prompt_text'   => $prompt,
            'response_text' => $result['text'] ?? '',
            'tokens_used'   => $result['tokens'] ?? 0,
            'duration_ms'   => $result['duration'] ?? 0,
        ]);
    }

    public static function dailyStats(): array
    {
        return Database::fetchAll(
            "SELECT DATE(created_at) as date, COUNT(*) as requests, SUM(tokens_used) as tokens
             FROM ai_logs
             GROUP BY DATE(created_at)
             ORDER BY date DESC
             LIMIT 30"
        );
    }
}
