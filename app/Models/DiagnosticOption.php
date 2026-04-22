<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/DiagnosticOption.php

class DiagnosticOption extends Model
{
    protected $fillable = ['question_id', 'value', 'label', 'order'];

    public function question()
    {
        return $this->belongsTo(DiagnosticQuestion::class, 'question_id');
    }
}
