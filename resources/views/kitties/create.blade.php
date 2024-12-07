@extends('layouts.app')

@section('title', __( 'kitties.add_kitties' ))

@section('content')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang( 'kitties.add_kitties' )</h1>
</section>

<!-- Main content -->
<section class="content">
    {!! Form::open(['url' => action([\App\Http\Controllers\KittyGroupAPIController::class, 'store']), 'method' => 'post', 'id' => 'user_add_form' ]) !!}
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
                <hr>
                <h4>Create Kitty Group:</h4>
            </div>
            <div class="form-group col-md-3">
                {!! Form::label('name', __( 'Name') . ':') !!}
                {!! Form::text('kitty_detail[name]', !empty($kitty_detail['name']) ? $kitty_detail['name'] : null , ['class' => 'form-control', 'id' => 'name', 'placeholder' => __( 'lang_v1.name') ]); !!}
            </div>
            <div class="form-group col-md-3">
                {!! Form::label('total_amount', __( 'Total Amount') . ':') !!}
                {!! Form::number('kitty_detail[total_amount]', !empty($kitty_detail['total_amount']) ? $kitty_detail['total_amount'] : null, ['class' => 'form-control', 'id' => 'total_amount', 'placeholder' => __( 'Total Amount') ]); !!}
            </div>
            <div class="form-group col-md-3">
                {!! Form::label('start_month', __( 'Start Month:') . ':') !!}
                {!! Form::date('kitty_detail[start_month]', !empty($kitty_detail['start_month']) ? $kitty_detail['start_month'] : null, ['class' => 'form-control', 'id' => 'start_month', 'placeholder' => __( 'Start Month:') ]); !!}
            </div>
            <div class="form-group col-md-3">
                {!! Form::label('customers', __( 'Select Customers') . ':') !!}
                {!! Form::select('kitty_detail[customers][]',
                $users->pluck('name', 'id')->toArray(),
                !empty($kitty_detail['customers']) ? $kitty_detail['customers'] : null,
                ['class' => 'form-control', 'id' => 'customers', 'multiple' => 'multiple', 'placeholder' => __( 'Select Customers:')]) !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-dw-btn-lg tw-text-white" id="submit_user_button">@lang( 'messages.save' )</button>
        </div>
    </div>
    {!! Form::close() !!}
    @stop
    @section('javascript')
    <script type="text/javascript">

    $(document).ready(function() {
        $('#bank_code').select2({
            tags: true, // Allows for custom tags if needed
            placeholder: 'Select Customers',
            tokenSeparators: [',', ' ']
        });
    });


    </script>
    @endsection
