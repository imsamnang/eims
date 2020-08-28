<?php

use App\Models\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailboxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailboxes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('from')->unsigned();
            $table->foreign('from')->references('id')->on((new Users())->getTable())->onDelete('cascade');
            $table->bigInteger('recipient')->unsigned();
            $table->foreign('recipient')->references('id')->on((new Users())->getTable())->onDelete('cascade');
            $table->text('subject')->nullable();
            $table->text('message')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->timestamp('updated_at')->nullable();                    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mailboxes');
    }
}
