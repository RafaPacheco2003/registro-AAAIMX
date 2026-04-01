<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('register', function (Blueprint $table) {
            $table->boolean('has_discount')->nullable()->after('confirmed_at');
        });
    }

    public function down(): void
    {
        Schema::table('register', function (Blueprint $table) {
            $table->dropColumn('has_discount');
        });
    }
};
