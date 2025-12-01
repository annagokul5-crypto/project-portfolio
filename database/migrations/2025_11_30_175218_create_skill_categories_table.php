<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('skill_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('skill_categories');
    }
};
