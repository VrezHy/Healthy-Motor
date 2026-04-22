<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_diagnostic_questions_table.php

public function up(): void
{
    Schema::create('diagnostic_questions', function (Blueprint $table) {
        $table->id();
        $table->string('question_key')->unique(); // 'merek', 'jenis', dll
        $table->string('title');
        $table->string('subtitle')->nullable();
        $table->enum('layout', ['list', 'grid'])->default('list');
        $table->boolean('scrollable')->default(false);
        $table->integer('order')->default(0);
        $table->timestamps();
    });

    Schema::create('diagnostic_options', function (Blueprint $table) {
        $table->id();
        $table->foreignId('question_id')->constrained('diagnostic_questions')->cascadeOnDelete();
        $table->string('value');
        $table->string('label');
        $table->integer('order')->default(0);
        $table->timestamps();
    });
}
};
