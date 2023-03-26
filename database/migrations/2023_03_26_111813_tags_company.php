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
        if(Schema::hasTable('tags_company'))
        return;
    Schema::create('tags_company', function (Blueprint $table) {
        $table->integer('tags_id');
        $table->integer('company_id');
     });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasTable('tags_company'))
        Schema::dropIfExists('tags_company');
    }
};
