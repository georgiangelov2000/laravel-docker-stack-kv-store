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
        Schema::create('stack_items', function (Blueprint $table) {
            $table->id();
            $table->string('stack_name', 64);
            $table->json('payload');
            $table->timestamp('pushed_at', 6)->useCurrent();

            // index for LIFO ordering
            $table->index(['stack_name', 'id'], 'k_stack_lifo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stack_items');
    }
};
