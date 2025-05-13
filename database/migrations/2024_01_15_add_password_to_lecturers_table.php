<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lecturers', function (Blueprint $table) {
            $table->string('password')->default(bcrypt('12345678'));
            $table->boolean('is_first_login')->default(true);
        });
    }

    public function down()
    {
        Schema::table('lecturers', function (Blueprint $table) {
            $table->dropColumn(['password', 'is_first_login']);
        });
    }
}; 