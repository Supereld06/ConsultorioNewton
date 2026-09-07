@extends('layouts.app')

@section('content')

        <h3 class="mb-0">
            🔄 Transferencia entre cajas
        </h3>

    <div class="container">

        <div class="card shadow-sm">

            <div class="card-body">

                <form method="POST"
                      action="{{ route('cajas.transferencia.store') }}">

                    @csrf


                    {{-- Caja origen --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Caja origen
                        </label>

                        <select name="caja_origen_id"
                                class="form-select"
                                required>

                            <option value="">
                                Seleccione una caja
                            </option>

                            @foreach($cajas as $caja)

                                <option value="{{ $caja->id }}">

                                    {{ $caja->nombre }}
                                    -
                                    Bs. {{ number_format($caja->saldo, 2) }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Caja destino --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Caja destino
                        </label>

                        <select name="caja_destino_id"
                                class="form-select"
                                required>

                            <option value="">
                                Seleccione una caja
                            </option>

                            @foreach($cajas as $caja)

                                <option value="{{ $caja->id }}">

                                    {{ $caja->nombre }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Monto --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Monto
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                Bs.
                            </span>

                            <input type="number"
                                   name="monto"
                                   class="form-control"
                                   step="0.01"
                                   min="0.01"
                                   required>

                        </div>

                    </div>


                    {{-- Observación --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Observación
                        </label>

                        <textarea name="observacion"
                                  class="form-control"
                                  rows="3"></textarea>

                    </div>


                    {{-- Errores --}}

                    @if($errors->any())

                        <div class="alert alert-danger">

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <div class="d-flex justify-content-between">

                        <a href="{{ route('cajas.index') }}"
                           class="btn btn-secondary">

                            Cancelar

                        </a>


                        <button type="submit"
                                class="btn btn-success">

                            🔄 Realizar transferencia

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
@endsection