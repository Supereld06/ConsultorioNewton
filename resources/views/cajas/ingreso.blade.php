@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-6 col-md-8">

                <div class="card shadow-sm">

                    {{-- ENCABEZADO --}}
                    <div class="card-header">

                        <h5 class="mb-0">
                            ➕ Registrar ingreso
                        </h5>

                    </div>


                    <div class="card-body">

                        {{-- CAJA --}}
                        <div class="alert alert-light border py-2 mb-3">

                            <div class="small text-muted">
                                Caja
                            </div>

                            <strong>
                                {{ $caja->nombre }}
                            </strong>

                            <div class="small text-muted">
                                Saldo actual:
                                <strong>
                                    Bs. {{ number_format($caja->saldo, 2) }}
                                </strong>
                            </div>

                        </div>


                        {{-- ERRORES --}}
                        @if($errors->any())

                            <div class="alert alert-danger py-2">

                                <ul class="mb-0">

                                    @foreach($errors->all() as $error)

                                        <li>{{ $error }}</li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif


                        <form action="{{ route('cajas.ingreso.store', $caja->id) }}" method="POST">

                            @csrf


                            {{-- MONTO --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Monto
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        Bs.
                                    </span>

                                    <input type="number" name="monto" class="form-control" step="0.01" min="0.01"
                                        value="{{ old('monto') }}" required>

                                </div>

                            </div>


                            {{-- CONCEPTO --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Concepto
                                </label>

                                <input type="text" name="concepto" class="form-control" value="{{ old('concepto') }}"
                                    maxlength="255" placeholder="Ej. Aporte de caja" required>

                            </div>


                            {{-- OBSERVACIÓN --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Observación
                                </label>

                                <textarea name="observacion" class="form-control" rows="2" maxlength="1000"
                                    placeholder="Observación opcional">{{ old('observacion') }}</textarea>

                            </div>


                            {{-- BOTONES --}}
                            <div class="d-flex justify-content-between">

                                <a href="{{ route('cajas.movimientos', $caja->id) }}" class="btn btn-secondary">

                                    ← Cancelar

                                </a>

                                <button type="submit" class="btn btn-success">

                                    💾 Registrar ingreso

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection