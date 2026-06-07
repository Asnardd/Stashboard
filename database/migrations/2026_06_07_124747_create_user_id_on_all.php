<?php

use App\Models\ItemType;
use App\Models\Tag;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
        });

        Schema::table('item_types', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('item_types', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
