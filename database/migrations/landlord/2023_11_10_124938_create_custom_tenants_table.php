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
        Schema::create('custom_tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('domain')->unique();
            $table->string('logo_url')->nullable();
            $table->string('status')->default('active');
            // Settings
            $table->string('market_plan');
            $table->integer('max_sub_accounts')->default(7);
            $table->boolean('account_upgrade_enabled')->default(false);
            $table->boolean('direct_referral_bonus_enabled')->default(false);
            $table->float('direct_referral_bonus_amount')->default(0);
            $table->boolean('pairing_bonus_enabled')->default(false);
            $table->float('pairing_bonus_amount')->default(0);
            $table->boolean('pairing_bonus_fifth_pairs_enabled')->default(false);
            $table->integer('pairing_bonus_max_pairs')->default(0);
            $table->boolean('flush_out_enabled')->default(false);
            $table->integer('pairing_bonus_max_waiting_points')->default(0);
            $table->boolean('infinity_bonus_enabled')->default(false);
            $table->float('infinity_bonus_amount')->default(0);
            $table->integer('infinity_bonus_starting_level')->default(0);
            $table->boolean('region_tagging_bonus_enabled')->default(false);
            $table->string('region_tagging_bonus_commission_mode')->nullable();
            $table->float('region_tagging_bonus_amount')->default(0);
            // Services
            $table->boolean('ticketing_enabled')->default(false);
            $table->boolean('eloading_enabled')->default(false);
            $table->boolean('insurance_enabled')->default(false);
            $table->boolean('bills_payment_enabled')->default(false);
            // Colors
            $table->string('color_primary', 7)->default('#376fd0');
            $table->string('color_primary_faded', 7)->default('#e9efff');
            $table->string('color_error', 7)->default('#ad3636');
            $table->string('color_success', 7)->default('#4aad36');
            $table->string('color_dark', 7)->default('#233044');
            $table->string('color_background', 7)->default('#FFFFFF');
            // Contacts
            $table->string('email')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('contact_person')->nullable();
            $table->foreignId('province_id')->constrained('provinces')->nullable();
            $table->foreignId('municipality_id')->constrained('municipalities')->nullable();
            $table->text('additional_address_information')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_tenants');
    }
};
