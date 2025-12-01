<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('whatsapp_admins', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number')->unique();
            $table->string('username');
            $table->string('password_hash');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_admins');
    }
};
