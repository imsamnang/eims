<?php

use App\Models\Roles;
use App\Models\Institute;
use Illuminate\Support\FacadesDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('location')->nullable();
            $table->string('profile')->nullable();
            $table->bigInteger('role_id')->unsigned()->nullable()->default(9);
            $table->foreign('role_id')->references('id')->on((new Roles())->getTable())->onDelete('cascade');
            $table->bigInteger('node_id')->nullable(); //Can be staff or students
            $table->bigInteger('institute_id')->unsigned()->nullable();
            $table->foreign('institute_id')->references('id')->on((new Institute())->getTable())->onDelete('cascade');
            $table->enum('status',['active','blocked','disabled'])->default('active')->nullable();
            $table->rememberToken();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->timestamp('updated_at')->nullable();
             //
             
             
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
