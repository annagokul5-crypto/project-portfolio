<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('project_screenshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('image_path');
            $table->string('title');
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_screenshots');
    }
};
