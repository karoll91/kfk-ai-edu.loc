<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\ClaudeAPI;
use App\Models\Exercise;
use App\Models\Submission;
use App\Models\Progress;
use App\Models\AiLog;

class ExerciseController extends Controller
{
    public function show(array $params = []): void
    {
        $exercise = Exercise::withCreativeTask((int)($params['id'] ?? 0));
        if (!$exercise) { $this->abort(404, 'Mashq topilmadi'); return; }

        $userId     = Auth::user()['id'];
        $submission = Submission::byUserAndExercise($userId, $exercise['id']);

        $this->render('exercises.show', [
            'exercise'   => $exercise,
            'submission' => $submission,
            'title'      => $exercise['title'],
        ]);
    }

    public function submit(array $params = []): void
    {
        $exerciseId = (int)($params['id'] ?? 0);
        $exercise   = Exercise::withModule($exerciseId);
        if (!$exercise) { $this->abort(404, 'Mashq topilmadi'); return; }

        $content = trim($_POST['content'] ?? '');
        if (mb_strlen($content) < 50) {
            $this->flash('error', 'Javob kamida 50 ta belgidan iborat bo\'lishi kerak');
            $this->redirect("/exercises/{$exerciseId}");
            return;
        }
        if (mb_strlen($content) > 10000) {
            $this->flash('error', 'Javob 10 000 belgidan oshmasligi kerak');
            $this->redirect("/exercises/{$exerciseId}");
            return;
        }

        $userId = Auth::user()['id'];

        // Avvalgi topshiriq borligini tekshirish
        $existing = Submission::byUserAndExercise($userId, $exerciseId);

        // AI bilan baholash
        $ai     = new ClaudeAPI();
        $result = $ai->review($exercise['title'] . "\n" . $exercise['instructions'], $content);

        if (!$result['success']) {
            $this->flash('error', 'AI xizmati hozircha mavjud emas. Iltimos, keyinroq urinib ko\'ring.');
            $this->redirect("/exercises/{$exerciseId}");
            return;
        }

        $score    = $result['review']['score'] ?? 50;
        $feedback = $result['review']['feedback'] ?? '';
        $now      = date('Y-m-d H:i:s');

        // Saqlash yoki yangilash
        if ($existing) {
            Submission::update($existing['id'], [
                'content'      => $content,
                'score'        => $score,
                'ai_feedback'  => $feedback,
                'status'       => 'ai_reviewed',
                'submitted_at' => $now,
                'reviewed_at'  => $now,
            ]);
            $submissionId = $existing['id'];
        } else {
            $submissionId = Submission::create([
                'user_id'      => $userId,
                'exercise_id'  => $exerciseId,
                'content'      => $content,
                'score'        => $score,
                'ai_feedback'  => $feedback,
                'status'       => 'ai_reviewed',
                'submitted_at' => $now,
                'reviewed_at'  => $now,
            ]);
        }

        // AI logga yozish
        AiLog::log($userId, $exerciseId, 'review', $content, $result);

        // Taraqqiyotni yangilash
        Progress::recalculate($userId, $exercise['module_id']);

        $this->redirect("/exercises/{$exerciseId}/result/{$submissionId}");
    }

    public function result(array $params = []): void
    {
        $exerciseId   = (int)($params['id'] ?? 0);
        $submissionId = (int)($params['sid'] ?? 0);
        $exercise     = Exercise::withModule($exerciseId);
        $submission   = Submission::find($submissionId);

        if (!$exercise || !$submission) {
            $this->abort(404);
            return;
        }

        if ((int)$submission['user_id'] !== Auth::user()['id']) {
            $this->abort(403);
            return;
        }

        $this->render('exercises.result', [
            'exercise'   => $exercise,
            'submission' => $submission,
            'title'      => 'Natija — ' . $exercise['title'],
        ]);
    }
}
