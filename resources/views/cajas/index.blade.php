@extends('layouts.app')

@section('content')

    <div class="container-fluid">

        {{-- ENCABEZADO --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="mb-0">💰 Cajas</h2>
                <small class="text-muted">
                    Control de cajas y movimiento de dinero
                </small>
            </div>
        </div>


        {{-- MENSAJE DE ÉXITO --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show py-2 mb-3">
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>
            </div>
        @endif


        {{-- ERRORES --}}
        @if($errors->any())
            <div class="alert alert-danger py-2 mb-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- TOTAL GENERAL --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body py-3 text-center">

                <div class="text-muted small">
                    Dinero total en cajas
                </div>

                <div class="fs-3 fw-bold">
                    Bs. {{ number_format($totalCajas, 2) }}
                </div>

            </div>
        </div>


        {{-- CAJAS --}}
        <div class="row g-3">

            @forelse($cajas as $caja)

                <div class="col-lg-4 col-md-6">

                    <div class="card shadow-sm h-100">

                        <div class="card-body p-3">

                            {{-- ICONO --}}
                            <div class="text-center">

                                @if($caja->nombre === 'Doctores')
                                    <div style="font-size: 42px;">👨‍⚕️</div>

                                @elseif($caja->nombre === 'Empresa')
                                    <div style="font-size: 42px;">🏢</div>

                                @else
                                    <div style="font-size: 42px;">📦</div>
                                @endif

                            </div>


                            {{-- NOMBRE --}}
                            <h5 class="text-center mb-1">
                                {{ $caja->nombre }}
                            </h5>


                            {{-- SALDO --}}
                            <div class="text-center mb-3">

                                <small class="text-muted">
                                    Saldo disponible
                                </small>

                                <div class="fs-4 fw-bold">
                                    Bs. {{ number_format($caja->saldo, 2) }}
                                </div>

                            </div>


                            {{-- BOTONES --}}
                            <div class="row g-2">

                                {{-- INGRESO --}}
                                <div class="col-6">

                                    <a href="{{ route('cajas.ingreso', $caja->id) }}" class="btn btn-success btn-sm w-100">

                                        ➕ Ingreso

                                    </a>

                                </div>


                                {{-- EGRESO --}}
                                <div class="col-6">

                                    <a href="{{ route('cajas.egreso', $caja->id) }}" class="btn btn-danger btn-sm w-100">

                                        ➖ Egreso

                                    </a>

                                </div>


                                {{-- TRANSFERENCIA --}}
                                <div class="col-6">

                                    <a href="{{ route('cajas.transferencia', $caja->id) }}"
                                        class="btn btn-warning btn-sm w-100">

                                        🔄 Traspasar

                                    </a>

                                </div>


                                {{-- MOVIMIENTOS --}}
                                <div class="col-6">

                                    <a href="{{ route('cajas.movimientos', $caja->id) }}" class="btn btn-primary btn-sm w-100">

                                        📋 Movimientos

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">

                    <div class="alert alert-info text-center">
                        No existen cajas activas.
                    </div>

                </div>

            @endforelse

        </div>

    </div>

@endsection