@extends('layouts.app')

@section('content')
  <x-common.page-breadcrumb pageTitle="Pohon" />
  <div class="space-y-6">
    {{-- <x-common.component-card title="Data Pohon">
    </x-common.component-card> --}}
    <x-tables.table 
      :rows="$plants" 
      :attrib="[
      [
        'inputName' => 'plant_name',
        'columns' => 'Nama Pohon',
        'placeholder' => 'Masukkan Nama Pohon...',
        'className' => 'col-span-2'
      ],
      [
        'inputName' => 'unit',
        'columns' => 'Satuan',
        'placeholder' => 'Misal: Kg, Batang, Pohon...',
        'className' => 'col-span-2 lg:col-span-1'
      ],
      [
        'inputName' => 'harvest_time',
        'columns' => 'Waktu Panen',
        'placeholder' => 'Misal: 90 Hari...',
        'className' => 'col-span-2 lg:col-span-1'
      ]
    ]" 
    {{-- url ada 3: add, edit, delete --}}
    url='/plants'
    method='POST' 
    />
  </div>
@endsection