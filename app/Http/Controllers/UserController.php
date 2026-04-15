<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4',
        ]);

        User::create($request->all());

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => "required|email|unique:users,email,$id",
        ]);

        $data = $request->all();

        if (!$request->password) {
            unset($data['password']); // 🔥 evita sobrescribir vacío
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return back()->with('success', 'Usuario eliminado');
    }
}
