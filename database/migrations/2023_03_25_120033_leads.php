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
        if(Schema::hasTable('leads'))
        return;
    Schema::create('leads', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->integer('price');
        $table->integer('responsible_user_id');
        $table->integer('group_id');
        $table->integer('created_by')->nullable();;
        $table->integer('updated_by')->nullable();;
        $table->integer('updated_at')->nullable();;
        $table->integer('account_id')->nullable();;
        $table->integer('pipeline_id')->nullable();;
        $table->integer('status_id')->nullable();;
        $table->integer('closest_task_at')->nullable();;
        $table->integer('loss_reason_id')->nullable();
        $table->integer('source_id')->nullable();
        $table->integer('closed_at')->nullable();;
        $table->integer('created_at')->nullable();;
        $table->boolean('is_deleted')->nullable();;
        $table->integer('score')->nullable();
        $table->boolean('is_price_modified_by_robot')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasTable('leads'))
        Schema::dropIfExists('leads');
    }
};
