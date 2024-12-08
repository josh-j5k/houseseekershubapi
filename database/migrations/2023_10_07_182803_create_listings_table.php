<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('slug')->nullable()->unique();
            $table->foreignIdFor(User::class, 'user_id')->onDelete('cascade');
            $table->string('title');
            $table->string('property_status');
            $table->string('property_type');
            $table->string('location');
            $table->bigInteger('price')->unsigned();
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
