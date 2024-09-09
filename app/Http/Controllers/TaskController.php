<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        try {
            $status = $request->input('status');
    
            $tasksQuery = Task::query();
    
            if ($status !== null) {
                $tasksQuery->where('completed', $status);
            }
    
            $tasks = $tasksQuery->get();
    
            return $tasks->isEmpty()
                ? view('tasks', ['tasks' => [], 'message' => 'No hay tareas disponibles.'])
                : view('tasks', ['tasks' => $tasks]);
    
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['error' => 'Problema en la base de datos: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ups, ha ocurrido un problema...' . $e->getMessage()], 500);
        }
    }
    
    // Crear tarea
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|max:255',
                'description' => 'required|max:500',
                'user' => 'required|max:500',
            ]);

            $user = User::where('email', $validated['user'])->first();

            if (!$user) {
                return redirect()->back()->with('error', 'Usuario no encontrado, verrificar con otro correo.');
            }

            $task = new Task($validated);
            $task->user_id = $user->id;
            $task->save();

            return redirect()->back()->with('success', 'Tarea Registrada correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Problema en la base de datos ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ups, ha ocurrrido un problema... ' . $e->getMessage());
        }
    }




    // Actualizar tarea
    public function update(Request $request, $id)
    {
        try {
            // Validación de los datos de entrada
            $validated = $request->validate([
                'title' => 'required|max:255',
                'description' => 'required|max:500',
            ]);

            // Buscar la tarea por ID
            $task = Task::find($id);

            if (!$task) {
                return response()->json(['error' => 'Task not found.'], 404);
            }

            // Actualizar la tarea con los datos validados
            $task->update($validated);

            // Devolver una respuesta exitosa en formato JSON
            return response()->json(['success' => 'Tarea Actualizada correctamente.'], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ups, ha ocurrrido un problema... ' . $e->getMessage()], 500);
        }
    }
    public function updateStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:0,1',
            ]);
            // Buscar la tarea por ID
            $task = Task::find($id);

            if (!$task) {
                return response()->json(['error' => 'Tarea no existente.'], 404);
            }

            $task->update(['completed' => $validated['status']]);


        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['error' => 'Problema en la base de datos: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ups, ha ocurrrido un problema... ' . $e->getMessage()], 500);
        }
    }


    public function deleted($id)
    {
        try {
            $task = Task::find($id);

            if (!$task) {
                return response()->json(['error' => 'Tarea no existente.'], 404);
            }

            $task->delete();

            return response()->json(['success' => 'Tarea eliminada correctamente.'], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['error' => 'Problema en la base de datos: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ups, ha ocurrrido un problema... ' . $e->getMessage()], 500);
        }
    }
}
