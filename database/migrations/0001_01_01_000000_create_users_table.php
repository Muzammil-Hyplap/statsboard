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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->integer('age');
                $table->enum('gender', ['F', 'M']);
                $table->rememberToken();
                $table->timestamps();
            });
        }

        //Modify table
        Schema::table('users', function(Blueprint $table){
            if(!Schema::hasColumn('users', 'state_id')){
                $table->unsignedMediumInteger('state_id');
                $table->foreign('state_id')->references('id')->on('states')->cascadeOnDelete()->cascadeOnUpdate();
            }

            if(!Schema::hasColumn('users', 'country_id')){
                $table->unsignedMediumInteger('country_id');
                $table->foreign('country_id')->references('id')->on('countries')->cascadeOnDelete()->cascadeOnUpdate();
            }

            if(!Schema::hasColumn('users', 'city_id')){
                $table->unsignedMediumInteger('city_id');
                $table->foreign('city_id')->references('id')->on('cities')->cascadeOnDelete()->cascadeOnUpdate();
            }

        });



        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
