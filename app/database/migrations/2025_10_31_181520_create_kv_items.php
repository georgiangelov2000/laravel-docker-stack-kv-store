<?php
declare(strict_types=1);

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
        Schema::create('kv_items', function (Blueprint $table) {
            $table->id();
            $table->string('k', 250)->unique();          // key
            $table->json('v');                            // value (JSON)
            $table->timestamp('expires_at')->nullable();  // TTL deadline
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_kv_items');
    }
};
