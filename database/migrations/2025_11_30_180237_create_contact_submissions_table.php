<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('contact_number')->nullable();
            $table->string('subject');
            $table->longText('message');
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
            $table->index('submitted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
    }
};
