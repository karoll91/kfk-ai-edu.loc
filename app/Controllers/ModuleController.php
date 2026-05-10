<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Module;
use App\Models\Exercise;
use App\Models\Progress;

class ModuleController extends Controller
{
    public function index(array $params = []): void
    {
        $modules  = Module::withExerciseCount();
        $userId   = Auth::user()['id'];
        $progress = [];
        foreach (Progress::forUser($userId) as $p) {
            $progress[$p['module_id']] = $p;
        }
        $this->render('modules.index', [
            'modules'  => $modules,
            'progress' => $progress,
            'title'    => 'Modullar',
        ]);
    }

    public function show(array $params = []): void
    {
        $module = Module::find((int)($params['id'] ?? 0));
        if (!$module) { $this->abort(404, 'Modul topilmadi'); return; }

        $exercises = Exercise::byModule($module['id']);
        $userId    = Auth::user()['id'];

        // Foydalanuvchi topshirgan ishlarni olish
        $submitted = [];
        foreach ($exercises as $ex) {
            $sub = \App\Models\Submission::byUserAndExercise($userId, $ex['id']);
            if ($sub) $submitted[$ex['id']] = $sub;
        }

        $this->render('modules.show', [
            'module'    => $module,
            'exercises' => $exercises,
            'submitted' => $submitted,
            'title'     => $module['name'],
        ]);
    }
}
