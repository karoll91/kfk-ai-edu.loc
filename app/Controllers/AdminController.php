<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\User;
use App\Models\AiLog;

class AdminController extends Controller
{
    public function index(array $params = []): void
    {
        $stats = Database::fetchOne(
            "SELECT
                (SELECT COUNT(*) FROM users WHERE role='student') as students,
                (SELECT COUNT(*) FROM users WHERE role='teacher') as teachers,
                (SELECT COUNT(*) FROM submissions) as submissions,
                (SELECT COUNT(*) FROM ai_logs) as ai_requests,
                (SELECT IFNULL(SUM(tokens_used),0) FROM ai_logs) as total_tokens"
        );
        $users   = User::allWithStats();
        $aiStats = AiLog::dailyStats();

        $this->render('admin.index', [
            'stats'   => $stats,
            'users'   => $users,
            'aiStats' => $aiStats,
            'title'   => 'Admin paneli',
        ], 'dashboard');
    }

    public function toggleUser(array $params = []): void
    {
        $id   = (int)($params['id'] ?? 0);
        $user = User::find($id);
        if (!$user) { $this->abort(404); return; }

        if ($user['role'] === 'admin') { $this->abort(403); return; }

        User::update($id, ['is_active' => $user['is_active'] ? 0 : 1]);
        $this->flash('success', 'Foydalanuvchi holati yangilandi');
        $this->redirect('/admin');
    }
}
