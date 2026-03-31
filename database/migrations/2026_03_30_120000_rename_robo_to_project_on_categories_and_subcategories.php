<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('categories', 'robo')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->renameColumn('robo', 'project');
            });
        }

        if (Schema::hasColumn('subcategories', 'robo')) {
            Schema::table('subcategories', function (Blueprint $table) {
                $table->renameColumn('robo', 'project');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('categories', 'project')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->renameColumn('project', 'robo');
            });
        }

        if (Schema::hasColumn('subcategories', 'project')) {
            Schema::table('subcategories', function (Blueprint $table) {
                $table->renameColumn('project', 'robo');
            });
        }
    }
};
