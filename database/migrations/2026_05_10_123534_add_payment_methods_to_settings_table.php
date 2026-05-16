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
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('pm_cod')->default(true)->after('social_instagram');
            $table->boolean('pm_bank')->default(false)->after('pm_cod');
            $table->boolean('pm_jazzcash')->default(false)->after('pm_bank');
            $table->boolean('pm_easypaisa')->default(false)->after('pm_jazzcash');
            $table->text('pm_bank_details')->nullable()->after('pm_easypaisa');
            $table->text('pm_jazzcash_details')->nullable()->after('pm_bank_details');
            $table->text('pm_easypaisa_details')->nullable()->after('pm_jazzcash_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['pm_cod','pm_bank','pm_jazzcash','pm_easypaisa','pm_bank_details','pm_jazzcash_details','pm_easypaisa_details']);
        });
    }
};
