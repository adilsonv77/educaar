<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterSchoolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schools', function(Blueprint $table) {
            $table->string('qr_letra', 1);
            $table->unsignedInteger('qr_numero');

         });

         DB::table('schools')->update(['qr_letra'=>'A', 'qr_numero'=>1]);
 
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
