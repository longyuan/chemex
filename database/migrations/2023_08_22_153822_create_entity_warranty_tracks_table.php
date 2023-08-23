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
        Schema::create('entity_warranty_tracks', function (Blueprint $table) {
            $table->comment('实体维保记录表');
            $table->id();
            $table->integer('entity_id')->comment('实体ID');
            $table->integer('vendor_id')->comment('厂商ID');
            $table->integer('vendor_contact_id')->comment('厂商联系人ID');
            $table->timestamp('expire_date')->comment('过期日期');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_warranty_tracks');
    }
};
