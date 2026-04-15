@extends('layouts.app')

@section('content')

<div class="container">

    <h3>Editar Usuario</h3>

    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <input type="text" name="name" value="{{ $user->name }}" class="form-control mb-2">

        <input type="email" name="email" value="{{ $user->email }}" class="form-control mb-2">

        <input type="password" name="password" placeholder="Nueva contraseña (opcional)" class="form-control mb-2">

        <button class="btn btn-primary">Actualizar</button>

    </form>

</div>

@endsection