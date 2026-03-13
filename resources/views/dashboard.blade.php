<x-app-layout>

<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
Panel de Control
</h2>
</x-slot>

<div class="container mt-4">

<div class="row g-4">

<!-- Pacientes -->

<div class="col-md-3">

<div class="card shadow-sm text-center">

<div class="card-body">

<h5 class="card-title">
👨 Pacientes
</h5>

<h3>
120
</h3>

<p class="text-muted">
Registrados
</p>

<a href="#" class="btn btn-primary btn-sm">
Ver pacientes
</a>

</div>

</div>

</div>

<!-- Citas -->

<div class="col-md-3">

<div class="card shadow-sm text-center">

<div class="card-body">

<h5 class="card-title">
📅 Citas
</h5>

<h3>
32
</h3>

<p class="text-muted">
Programadas hoy
</p>

<a href="#" class="btn btn-success btn-sm">
Ver citas
</a>

</div>

</div>

</div>

<!-- Consultas -->

<div class="col-md-3">

<div class="card shadow-sm text-center">

<div class="card-body">

<h5 class="card-title">
🩺 Consultas
</h5>

<h3>
18
</h3>

<p class="text-muted">
Realizadas hoy
</p>

<a href="#" class="btn btn-warning btn-sm">
Ver consultas
</a>

</div>

</div>

</div>

<!-- Doctores -->

<div class="col-md-3">

<div class="card shadow-sm text-center">

<div class="card-body">

<h5 class="card-title">
👨‍⚕️ Doctores
</h5>

<h3>
5
</h3>

<p class="text-muted">
Activos
</p>

<a href="#" class="btn btn-info btn-sm">
Ver doctores
</a>

</div>

</div>

</div>

</div>

<!-- segunda fila -->

<div class="row mt-4">

<div class="col-md-6">

<div class="card shadow-sm">

<div class="card-body">

<h5 class="card-title">
📋 Últimas consultas
</h5>

<table class="table">

<thead>

<tr>
<th>Paciente</th>
<th>Doctor</th>
<th>Fecha</th>
</tr>

</thead>

<tbody>

<tr>
<td>Juan Pérez</td>
<td>Dr. Gómez</td>
<td>12/03/2026</td>
</tr>

<tr>
<td>María López</td>
<td>Dra. Ramos</td>
<td>12/03/2026</td>
</tr>

</tbody>

</table>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow-sm">

<div class="card-body">

<h5 class="card-title">
📅 Próximas citas
</h5>

<table class="table">

<thead>

<tr>
<th>Paciente</th>
<th>Hora</th>
<th>Doctor</th>
</tr>

</thead>

<tbody>

<tr>
<td>Carlos Díaz</td>
<td>10:30</td>
<td>Dr. Gómez</td>
</tr>

<tr>
<td>Ana Ruiz</td>
<td>11:00</td>
<td>Dra. Ramos</td>
</tr>

</tbody>

</table>

</div>

</div>

</div>

</div>

</div>

</x-app-layout>