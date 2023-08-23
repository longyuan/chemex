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
        Schema::create('entities', function (Blueprint $table) {
            $table->comment('实体主数据表。');
            $table->id();
            $table->string('asset_number')
                ->unique()
                ->comment('资产编号');
            $table->integer('category_id')
                ->comment('分类ID');
            $table->string('name')
                ->nullable()
                ->comment('名称');
            $table->string('sn')
                ->nullable()
                ->comment('序列号');
            $table->string('specification')
                ->nullable()
                ->comment('规格');
            $table->boolean('virtual')
                ->default(false)
                ->comment('虚拟实体标签');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entities');
    }
};
