<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreRequest;
use App\Http\Requests\Users\UpdateRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return Inertia::render('Users/indexUser',compact('users'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return Inertia::render('Users/createUser');


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            $user = $request->validated();
            $user['password'] = Hash::make($user['password']);

            User::create($user);
            return to_route('users.create')
                ->with('message', 'Usuario creado correctamente');

        } catch (\Exception $e) {
            return to_route('users.create')->withErrors(['error' => 'Error al crear el usuario']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return Inertia::render('Users/editUser',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        try {
            $user = User::findOrFail($id);
            $data = $request->validated();

            // Solo actualizar password si se proporciona uno nuevo
            if (isset($data['password']) && !empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);

            return to_route('users.edit', $user->id)
                ->with('message', 'Usuario actualizado correctamente');

        } catch (\Exception $e) {
            return to_route('users.edit')->withErrors(['error' => 'Error al actualizar el usuario']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return to_route('users.index')->with('message', 'Usuario eliminado correctamente');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar el usuario']);
        }
    }
}
