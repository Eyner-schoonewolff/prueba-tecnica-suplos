<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        try {
            return view('users');
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */

    // create users
    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|max:250|unique:user,email',
            ], [
                'email.unique' => 'El correo electrónico ya está registrado. Por favor, elige otro.'
            ]);

            $user = new User($validated);
            $user->save();

            return redirect()->back()->with('success', 'Usuario Creado Correctamente.');
            
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
