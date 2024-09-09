<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Relación con usuarios
            $table->string('title');
            $table->text('description');
            $table->boolean('completed')->default(false)->change(); // Estado de la tarea (completada o no)
            $table->timestamp('deleted_at')->nullable(); // Fecha y hora de eliminación lógica
            $table->timestamps();

            // 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null'); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
