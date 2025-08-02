<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('module'); // e.g. 'Product', 'Order', 'Dealer'
            $table->string('action'); // e.g. 'create', 'update', 'delete', 'login'
            $table->text('comments')->nullable(); // optional details
            $table->timestamp('action_date')->useCurrent();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
};
