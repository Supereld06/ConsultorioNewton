@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between mb-3">
        <h3>Usuarios</h3>

        <a href="{{ route('users.create') }}" class="btn btn-success">
            ➕ Nuevo Usuario
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">

        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @foreach($users as $u)
                <tr>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>
                        <a href="{{ route('users.edit', $u->id) }}" class="btn btn-warning btn-sm">Editar</a>

                        <form action="{{ route('users.destroy', $u->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Eliminar usuario?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

    {{ $users->links() }}

</div>

@endsection