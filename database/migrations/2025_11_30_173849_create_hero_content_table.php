<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hero_content', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->text('objective');
            $table->string('resume_path')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hero_content');
    }
};
