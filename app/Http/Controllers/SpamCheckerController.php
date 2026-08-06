<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SpamCheckerService;

class SpamCheckerController extends Controller
{
    protected $spamChecker;

    // Menyuntikkan (Inject) Service yang sudah kita buat
    public function __construct(SpamCheckerService $spamChecker)
    {
        $this->spamChecker = $spamChecker;
    }

    public function analyze(Request $request)
    {
        // Jalankan fungsi analisis dari Service
        $result = $this->spamChecker->analyze(
            $request->input('subject', ''),
            $request->input('body', '')
        );

        return response()->json($result);
    }
}