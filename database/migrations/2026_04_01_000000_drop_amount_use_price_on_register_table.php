<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('register')) {
            return;
        }

        if (! Schema::hasColumn('register', 'amount')) {
            return;
        }

        if (! Schema::hasColumn('register', 'price')) {
            Schema::table('register', function (Blueprint $table) {
                $table->decimal('price', 10, 2)->nullable()->after('registration_code');
            });
        }

        $rows = DB::table('register')->select('id', 'amount', 'price', 'subcategory_id')->get();
        foreach ($rows as $row) {
            $price = $row->price;
            if ($price === null && $row->amount !== null) {
                $price = $row->amount;
            }
            if ($price === null && $row->subcategory_id) {
                $price = DB::table('subcategories')->where('id', $row->subcategory_id)->value('price');
            }
            if ($price !== null) {
                DB::table('register')->where('id', $row->id)->update(['price' => $price]);
            }
        }

        Schema::table('register', function (Blueprint $table) {
            $table->dropColumn('amount');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('register')) {
            return;
        }

        if (Schema::hasColumn('register', 'amount')) {
            return;
        }

        Schema::table('register', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->nullable()->after('registration_code');
        });

        DB::table('register')->whereNotNull('price')->update(['amount' => DB::raw('price')]);
    }
};
