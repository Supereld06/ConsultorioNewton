@extends('layouts.app')

@section('content')

<div class="container">

    <h3>Nuevo Usuario</h3>

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <input type="text" name="name" placeholder="Nombre" class="form-control mb-2">

        <input type="email" name="email" placeholder="Email" class="form-control mb-2">

        <input type="password" name="password" placeholder="Password" class="form-control mb-2">

        <button class="btn btn-primary">Guardar</button>

    </form>

</div>

@endsection