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
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('imageUrl')->nullable()->default("https://images.deliveryhero.io/image/talabat/restaurants/Logo_1638421418735242644.jpg?width=180");
            $table->string('coverImageUrl')->nullable();
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('imageUrl')->nullable()->default("https://images.deliveryhero.io/image/talabat/Menuitems/C0C55BD6E4766173E7413FCD9AA09F1F?width=172&amp;height=172");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('imageUrl');
            $table->dropColumn('coverImageUrl');
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('imageUrl');
        });
    }
};
