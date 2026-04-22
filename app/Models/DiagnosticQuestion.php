<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/DiagnosticQuestion.php

class DiagnosticQuestion extends Model
{
    protected $fillable = ['question_key', 'title', 'subtitle', 'layout', 'scrollable', 'order'];

    public function options()
    {
        return $this->hasMany(DiagnosticOption::class, 'question_id')->orderBy('order');
    }
}
