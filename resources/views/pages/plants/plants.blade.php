@extends('layouts.app')

@section('content')
  <x-common.page-breadcrumb pageTitle="Pohon" />
  <div class="space-y-6">
    {{-- <x-common.component-card title="Data Pohon">
    </x-common.component-card> --}}
    <x-tables.table />
  </div>
@endsection