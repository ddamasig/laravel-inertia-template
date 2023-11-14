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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('logo_url')->nullable();
            $table->string('status')->default('active');
            // Settings
            $table->string('market_plan')->default('binary');
            $table->boolean('has_account_levels')->default(false);
            $table->integer('maximum_sub_accounts')->default(7);
            $table->boolean('has_infinity_bonus')->default(false);
            $table->boolean('has_pairing_bonus')->default(false);
            $table->boolean('has_fifth_pairs')->default(false);
            $table->boolean('has_direct_referral_bonus')->default(false);
            $table->boolean('has_region_tagging_bonus')->default(false);
            $table->boolean('has_flush_out')->default(false);
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
        Schema::dropIfExists('tenants');
    }
};
