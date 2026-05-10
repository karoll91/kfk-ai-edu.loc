<?php
declare(strict_types=1);
namespace App\Controllers\API;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\ClaudeAPI;
use App\Models\Exercise;
use App\Models\AiLog;

class AiApiController extends Controller
{
    public function help(array $params = []): void
    {
        $exerciseId = (int)($_POST['exercise_id'] ?? 0);
        $question   = trim($_POST['question'] ?? '');

        if (!$question) {
            $this->json(['error' => 'Savol bo\'sh bo\'lishi mumkin emas'], 422);
            return;
        }

        $exercise = Exercise::find($exerciseId);
        $ai       = new ClaudeAPI();
        $result   = $ai->help($exercise['title'] ?? 'Mashq', $question);

        AiLog::log(Auth::user()['id'], $exerciseId ?: null, 'help', $question, $result);

        $this->json([
            'success' => $result['success'],
            'text'    => $result['text'],
            'tokens'  => $result['tokens'],
        ]);
    }

    public function review(array $params = []): void
    {
        $exerciseId = (int)($_POST['exercise_id'] ?? 0);
        $content    = trim($_POST['content'] ?? '');

        if (mb_strlen($content) < 20) {
            $this->json(['error' => 'Javob juda qisqa'], 422);
            return;
        }

        $exercise = Exercise::find($exerciseId);
        $ai       = new ClaudeAPI();
        $result   = $ai->review($exercise['title'] ?? '', $content);

        AiLog::log(Auth::user()['id'], $exerciseId ?: null, 'review', $content, $result);

        $this->json([
            'success' => $result['success'],
            'review'  => $result['review'] ?? [],
        ]);
    }
}
