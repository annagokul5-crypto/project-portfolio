<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contact_info', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->string('email');
            $table->string('whatsapp');
            $table->string('linkedin')->nullable();
            $table->string('github')->nullable();
            $table->string('location');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contact_info');
    }
};
