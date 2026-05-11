<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Validator;
use App\Models\Progress;
use App\Models\Submission;
use App\Models\Module;
use App\Models\User;

class ProgressController extends Controller
{
    public function dashboard(array $params = []): void
    {
        $userId         = Auth::user()['id'];
        $progress       = Progress::forUser($userId);
        $submissions    = Submission::byUser($userId);
        $overallPercent = Progress::overallPercent($userId);

        $this->render('dashboard.index', [
            'progress'       => $progress,
            'submissions'    => $submissions,
            'overallPercent' => $overallPercent,
            'title'          => 'Dashboard',
        ]);
    }

    public function profile(array $params = []): void
    {
        $this->render('dashboard.profile', [
            'user'  => Auth::user(),
            'title' => 'Profil',
        ], 'dashboard');
    }

    public function updatePassword(array $params = []): void
    {
        $v = Validator::make($_POST, [
            'current_password'      => 'required|min:6',
            'new_password'          => 'required|min:8|confirmed',
        ]);

        if ($v->fails()) {
            $this->flash('error', $v->firstError());
            $this->redirect('/profile');
            return;
        }

        $userId  = Auth::user()['id'];
        $userRow = User::find($userId);

        if (!$userRow || !Auth::checkPassword($this->input('current_password'), $userRow['password_hash'])) {
            $this->flash('error', 'Hozirgi parol noto\'g\'ri');
            $this->redirect('/profile');
            return;
        }

        User::updatePassword($userId, Auth::hashPassword($this->input('new_password')));

        $this->flash('success', 'Parol muvaffaqiyatli yangilandi');
        $this->redirect('/profile');
    }
}
