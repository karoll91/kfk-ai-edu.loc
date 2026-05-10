<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Module;

class HomeController extends Controller
{
    public function index(array $params = []): void
    {
        $modules = Module::withExerciseCount();
        $stats = Database::fetchOne(
            "SELECT 
                (SELECT COUNT(*) FROM modules WHERE is_active=1) as modules_count,
                (SELECT COUNT(*) FROM exercises WHERE is_active=1) as exercises_count,
                (SELECT COUNT(*) FROM users WHERE role='student' AND is_active=1) as students_count"
        );

        $this->render('home.index', [
            'modules' => $modules,
            'stats'   => $stats,
            'title'   => 'KompMind — Bosh sahifa',
        ]);
    }
}
