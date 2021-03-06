<?php

use App\Models\Institute;
use Illuminate\Support\FacadesDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificateFramesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificate_frames', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('type')->nullable();
            $table->bigInteger('institute_id')->unsigned()->nullable();
            $table->foreign('institute_id')->references('id')->on((new Institute())->getTable())->onDelete('cascade');
            $table->text('foreground')->nullable();
            $table->text('background')->nullable();
            $table->string('layout')->nullable();
            $table->text('description')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('certificate_frames');
    }
}
