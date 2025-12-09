<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentFieldsToBookToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('book_tours', function (Blueprint $table) {
            $table->string('b_payment_method')->nullable()->after('b_status');
            $table->string('b_payment_transaction_id')->nullable()->after('b_payment_method');
            $table->timestamp('b_payment_date')->nullable()->after('b_payment_transaction_id');
            $table->text('b_payment_note')->nullable()->after('b_payment_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_tours', function (Blueprint $table) {
            $table->dropColumn(['b_payment_method', 'b_payment_transaction_id', 'b_payment_date', 'b_payment_note']);
        });
    }
}
