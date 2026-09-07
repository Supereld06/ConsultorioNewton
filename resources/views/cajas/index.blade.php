@extends('layouts.app')

@section('content')


        <h2 class="mb-0">
            💰 Cajas
        </h2>
        <small class="">
            Control de cajas y movimiento de dinero
        </small>

    <div class="container-fluid">

        {{-- Mensaje --}}
        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        {{-- Total --}}
        <div class="card shadow-sm mb-4">

            <div class="card-body text-center">

                <h6 class="text-muted">
                    Dinero total en cajas
                </h6>

                <h2 class="fw-bold">
                    Bs. {{ number_format($totalCajas, 2) }}
                </h2>

            </div>

        </div>


        {{-- Cajas --}}
        <div class="row">

            @foreach($cajas as $caja)

                <div class="col-md-4 mb-4">

                    <div class="card shadow-sm h-100">

                        <div class="card-body text-center">

                            @if($caja->nombre == 'Doctores')

                                <div style="font-size: 50px;">
                                    👨‍⚕️
                                </div>

                            @elseif($caja->nombre == 'Empresa')

                                <div style="font-size: 50px;">
                                    🏢
                                </div>

                            @else

                                <div style="font-size: 50px;">
                                    📦
                                </div>

                            @endif


                            <h4 class="mt-2">
                                {{ $caja->nombre }}
                            </h4>


                            <h2 class="fw-bold">
                                Bs.
                                {{ number_format($caja->saldo, 2) }}
                            </h2>


                            <a href="{{ route('cajas.movimientos', $caja->id) }}"
                               class="btn btn-primary">

                                Ver movimientos

                            </a>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>


        {{-- Transferencia --}}
        <div class="text-end">

            <a href="{{ route('cajas.transferencia') }}"
               class="btn btn-warning">
                🔄 Transferir dinero
            </a>

        </div>

    </div>

@endsection