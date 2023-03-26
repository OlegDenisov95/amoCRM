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
        if(Schema::hasTable('leads_tags'))
        return;
    Schema::create('leads_tags', function (Blueprint $table) {
        $table->integer('lead_id');
        $table->integer('tag_id');
     });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasTable('leads_tags'))
        Schema::dropIfExists('leads_tags');
    }
};
