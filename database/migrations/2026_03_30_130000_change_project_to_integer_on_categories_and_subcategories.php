<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * MySQL stores Laravel booleans as TINYINT(1); widen to INT for existing databases.
     * SQLite already uses INTEGER for booleans; PostgreSQL users can adjust manually if needed.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'project')) {
            DB::statement('ALTER TABLE categories MODIFY project INT NOT NULL');
        }

        if (Schema::hasTable('subcategories') && Schema::hasColumn('subcategories', 'project')) {
            DB::statement('ALTER TABLE subcategories MODIFY project INT NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'project')) {
            DB::statement('ALTER TABLE categories MODIFY project TINYINT(1) NOT NULL');
        }

        if (Schema::hasTable('subcategories') && Schema::hasColumn('subcategories', 'project')) {
            DB::statement('ALTER TABLE subcategories MODIFY project TINYINT(1) NOT NULL');
        }
    }
};
