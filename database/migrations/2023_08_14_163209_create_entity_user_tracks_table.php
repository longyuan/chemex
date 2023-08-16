<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entity_user_tracks', function (Blueprint $table) {
            $table->comment('实体归属表。');
            $table->id();
            $table->integer('entity_id')->comment('实体ID');
            $table->integer('user_id')->comment('用户ID');
            $table->string('comment')->nullable()->comment('分配原因');
            $table->string('delete_comment')->nullable()->comment('解除原因');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_user_tracks');
    }
};
