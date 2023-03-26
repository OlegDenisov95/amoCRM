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
        if(Schema::hasTable('company'))
        return;
    Schema::create('company', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->integer('responsibleUserId');
        $table->integer('groupId');
        $table->integer('createdBy');
        $table->integer('updatedBy');
        $table->integer('createdAt');
        $table->integer('updatedAt');
        $table->integer('closestTaskAt')->nullable();;
        $table->integer('accountId');
     });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasTable('company'))
        Schema::dropIfExists('company');
    }
};
