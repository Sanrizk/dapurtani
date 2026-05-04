@extends('layouts.app')

@section('content')
  <x-common.page-breadcrumb pageTitle="Pohon" />
  <div class="space-y-6">
    <x-common.component-card title="contoh table">
      <x-tables.table />
    </x-common.component-card>
  </div>
@endsection