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
        Schema::create('register', function (Blueprint $table) {
            $table->id();


            $table->string('team_name');
            $table->string('robot_name');


            $table->enum('education_level', ['high_school', 'university']);
            $table->string('institution');

            $table->string('email');

            $table->string('registration_code')->nullable();
            $table->decimal('amount', 10, 2)->nullable();

            $table->enum('payment_status', ['pending', 'confirmed', 'rejected'])->default('pending');
            $table->date('payment_date')->nullable();
            $table->date('confirmed_at')->nullable();

            $table->string('comments')->nullable();


            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('subcategory_id')->constrained('subcategories')->cascadeOnDelete();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('register');
    }
};
