<?php
declare(strict_types=1);
namespace App\Controllers\API;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Progress;

class ProgressApiController extends Controller
{
    public function me(array $params = []): void
    {
        $userId  = Auth::user()['id'];
        $progress = Progress::forUser($userId);
        $overall  = Progress::overallPercent($userId);

        $this->json([
            'modules' => $progress,
            'overall' => $overall,
        ]);
    }
}
