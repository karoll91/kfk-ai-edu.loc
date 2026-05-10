<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Progress;
use App\Models\Submission;
use App\Models\Module;

class ProgressController extends Controller
{
    public function dashboard(array $params = []): void
    {
        $userId        = Auth::user()['id'];
        $progress      = Progress::forUser($userId);
        $submissions   = Submission::byUser($userId);
        $overallPercent = Progress::overallPercent($userId);

        $this->render('dashboard.index', [
            'progress'        => $progress,
            'submissions'     => $submissions,
            'overallPercent'  => $overallPercent,
            'title'           => 'Dashboard',
        ]);
    }
}
