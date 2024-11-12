<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserCreateFormRequest;
use App\Http\Requests\UserUpdateFormRequest;
use App\Traits\DiscordReporting;
use App\Events\UserCreated;
use App\Events\UserUpdated;
use App\Events\UserDeleted;
use App\Events\UserRestore;

class UserController extends Controller
{
    use DiscordReporting;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->handleOperationWithDiscord(
            function () {
                $users = User::paginate(10);
                return view('users.index', compact('users'));
            },
            'index',
            [
                'url' => request()->fullUrl(),
                'method' => request()->method()
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateFormRequest $request)
    {
        return $this->handleOperationWithDiscord(
            function () use ($request) {
                $user = User::create([
                    'names' => $request->names,
                    'lastnames' => $request->lastnames,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'address' => $request->address,
                ]);

                event(new UserCreated($user));
                return true;
            },
            'store',
            [
                'request_data' => $request->except('password'),
                'url' => request()->fullUrl(),
                'method' => request()->method()
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->handleOperationWithDiscord(
            function () use ($id) {
                $user = User::findOrFail($id);
                return view('users.show', compact('user'));
            },
            'show',
            [
                'user_id' => $id,
                'url' => request()->fullUrl(),
                'method' => request()->method()
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return $this->handleOperationWithDiscord(
            function () use ($id) {
                $user = User::findOrFail($id);
                return view('users.edit', compact('user'));
            },
            'edit',
            [
                'user_id' => $id,
                'url' => request()->fullUrl(),
                'method' => request()->method()
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateFormRequest $request, string $id)
    {
        return $this->handleOperationWithDiscord(
            function () use ($request, $id) {
                $user = User::findOrFail($id);
                $user->names = $request->input('names');
                $user->lastnames = $request->input('lastnames');
                if ($request->input('password')) {
                    $user->password = bcrypt($request->input('password'));
                }
                $user->address = $request->input('address');
                $user->save();

                event(new UserUpdated($user));
                return true;
            },
            'update',
            [
                'user_id' => $id,
                'request_data' => $request->except('password'),
                'url' => request()->fullUrl(),
                'method' => request()->method()
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->handleOperationWithDiscord(
            function () use ($id) {
                $user = User::findOrFail($id);
                $user->delete();
                event(new UserDeleted($user));
                return true;
            },
            'delete',
            [
                'user_id' => $id,
                'url' => request()->fullUrl(),
                'method' => request()->method()
            ]
        );
    }

    /**
     * Display a listing of the trashed resources.
     */
    public function trashed()
    {
        return $this->handleOperationWithDiscord(
            function () {
                $users = User::onlyTrashed()->paginate(10);
                return view('users.trashed', compact('users'));
            },
            'trashed',
            [
                'url' => request()->fullUrl(),
                'method' => request()->method()
            ]
        );
    }

    /**
     * Restore the specified trashed resource.
     */
    public function restore(string $id)
    {
        return $this->handleOperationWithDiscord(
            function () use ($id) {
                $user = User::withTrashed()->findOrFail($id);
                $user->restore();
                event(new UserRestore($user));
                return true;
            },
            'restore',
            [
                'user_id' => $id,
                'url' => request()->fullUrl(),
                'method' => request()->method()
            ]
        );
    }
}
