<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Validator;
use App\Models\Submission;
use App\Models\User;
use App\Models\Progress;

class TeacherController extends Controller
{
    public function index(array $params = []): void
    {
        $pending  = Submission::pendingTeacherReview();
        $students = User::activeStudents();

        $this->render('teacher.index', [
            'pending'  => $pending,
            'students' => $students,
            'title'    => 'O\'qituvchi paneli',
        ]);
    }

    public function gradeForm(array $params = []): void
    {
        $submission = Submission::findWithDetails((int)($params['id'] ?? 0));
        if (!$submission) { $this->abort(404); return; }

        $this->render('teacher.grade', [
            'submission' => $submission,
            'title'      => 'Baho berish — ' . $submission['exercise_title'],
        ]);
    }

    public function grade(array $params = []): void
    {
        $id = (int)($params['id'] ?? 0);
        $v  = Validator::make($_POST, [
            'teacher_score'    => 'required|numeric',
            'teacher_feedback' => 'required|min:10',
        ]);
        if ($v->fails()) {
            $this->flash('error', $v->firstError());
            $this->back();
            return;
        }

        $sub = Submission::find($id);
        if (!$sub) { $this->abort(404); return; }

        Submission::update($id, [
            'teacher_score'    => min(100, max(0, (int)$_POST['teacher_score'])),
            'teacher_feedback' => trim($_POST['teacher_feedback']),
            'status'           => 'teacher_reviewed',
            'reviewed_at'      => date('Y-m-d H:i:s'),
        ]);

        // Taraqqiyotni qayta hisoblash
        $exercise = \App\Models\Exercise::find($sub['exercise_id']);
        if ($exercise) {
            Progress::recalculate($sub['user_id'], $exercise['module_id']);
        }

        $this->flash('success', 'Baho saqlandi');
        $this->redirect('/teacher');
    }
}
