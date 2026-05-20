@extends('layouts.app')

@section('content')
  <div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12 space-y-6">
      <x-ecommerce.ecommerce-metrics :tanam="$dataCultivate['total']" :tanamPersen="$dataCultivate['persen']" :tanamNaik="$dataCultivate['naik']" :panen="$dataHarvest['total']"
        :panenPersen="$dataHarvest['persen']" :panenNaik="$dataHarvest['naik']" />
      <x-ecommerce.monthly-sale :data="$chartData['harvest']" :categories="$chartData['categories']" />
    </div>

    <div class="col-span-12">
      <x-ecommerce.statistics-chart :dataCul="$chartData['cultivate']" :dataHar="$chartData['harvest']" :categories="$chartData['categories']"/>
    </div>
  </div>
@endsection