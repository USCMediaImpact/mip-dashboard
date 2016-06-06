<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->string('website', 500)->nullable();
            $table->string('code', 50)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
        });

        Schema::table('users', function ($table) {
            $table->integer('client_id')->nullable();
            $table->index('client_id', 'client_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('client');
        Schema::table('users', function ($table) {
            $table->dropColumn('client_id');
            $table->dropIndex('client_id_index');
        });
    }
}
