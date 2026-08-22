<?php

use Database\Seeders\CatSeeder;
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
        Schema::create('cats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('father_id')->nullable()->constrained('cats')->nullOnDelete();
            $table->foreignId('mother_id')->nullable()->constrained('cats')->nullOnDelete();
            $table->dateTime('birthdate')->index('birtdate_index');
            $table->string('breed')->index('breed_index');
            $table->string('sex')->index('sex_index');
            $table->decimal('price', 12, 2);
            $table->string('color');
            $table->string('name');
            $table->text('description');
            $table->decimal('weight');
            $table->string('status');
            $table->timestamps();
        });

        $seeder = new CatSeeder;
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cats');
    }
};
