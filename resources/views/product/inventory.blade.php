@extends('layouts.app')
@section('title', __('Inventory'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('Inventory')
    </h1>
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __('Inventory List')])
    <table class="table table-bordered table-striped" id="warranty_table">
        <thead>
            <tr>
                <th>@lang('Company Name')</th>
                <th>@lang('ITEM NAME')</th>
                <th>@lang('PACK/SIZE')</th>
                <th>@lang('CLOSING STOCK')</th>
                <th>@lang('CLOSING VALUE(BASIC RATE)')</th>
                <th>@lang('CLOSING VALUE(MRP)')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $key => $value)
            <tr>
                <td>{{@$value->brand}}</td>
                <td>{{@$value->product_code}}</td>
                <td>{{@$value->unit}}</td>
                <td>{{@$value->quantity}}</td>
                <td>{{@$value->additional_cost}}</td>
                <td>{{@$value->price}}</td>
            </tr>
        @endforeach
            
        </tbody>
    </table>
    @endcomponent

</section>
<!-- /.content -->
@stop
