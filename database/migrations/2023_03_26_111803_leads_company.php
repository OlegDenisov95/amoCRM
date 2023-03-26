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
        if(Schema::hasTable('leads_company'))
        return;
    Schema::create('leads_company', function (Blueprint $table) {
        $table->integer('lead_id');
        $table->integer('company_id');
     });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasTable('leads_company'))
        Schema::dropIfExists('leads_company');
    }
};
