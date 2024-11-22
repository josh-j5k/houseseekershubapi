<?php

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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('ref');
            $table->foreignId('senders_id')->constrained('users', 'id', 'message_users_senders_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('receivers_id')->constrained('users', 'id', 'message_users_receivers_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('message');
            $table->boolean('seen')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
