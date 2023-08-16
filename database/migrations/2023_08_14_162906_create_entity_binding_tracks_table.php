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
        Schema::create('entity_binding_tracks', function (Blueprint $table) {
            $table->comment('实体绑定记录表。');
            $table->id();
            $table->integer('entity_id')->comment('实体ID');
            $table->integer('child_entity_id')->comment('子级实体ID');
            $table->string('comment')->nullable()->comment('绑定原因');
            $table->string('delete_comment')->nullable()->comment('解除绑定原因');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_binding_tracks');
    }
};
