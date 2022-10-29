<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->string('disk');
            $table->string('name');
            $table->unsignedInteger('size');
            $table->string('path');
            $table->string('sender');
            $table->text('comments')->nullable();
            $table->string('recipient')->nullable();
            $table->string('password')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('hash', 10)->unique();
            $table->string('ua_ip');
            $table->string('ua_browser');
            $table->string('ua_os');
            $table->string('ua_device');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploads');
    }
};
