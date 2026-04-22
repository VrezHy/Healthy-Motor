<?php

// app/Http/Controllers/Api/DiagnosticQuestionController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiagnosticQuestion;

class DiagnosticQuestionController extends Controller
{
    public function index()
    {
        $questions = DiagnosticQuestion::with('options')
            ->orderBy('order')
            ->get()
            ->map(fn($q) => [
                'id'       => $q->question_key,
                'title'    => $q->title,
                'subtitle' => $q->subtitle,
                'layout'   => $q->layout,
                'scroll'   => $q->scrollable,
                'options'  => $q->options->map(fn($o) => [
                    'val'   => $o->value,
                    'label' => $o->label,
                ]),
            ]);

        return response()->json($questions);
    }
}