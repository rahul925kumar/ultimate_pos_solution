@extends('layouts.app')
@section('title', __( 'kitties.kitties' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang( 'kitties.kitties' )
        <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang( 'kitties.manage_kitties' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( '' )])
    @can('user.create')
    @slot('tool')
    <div class="box-tools">
        <a class="tw-dw-btn tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full" href="{{action([\App\Http\Controllers\KittyGroupAPIController::class, 'index'])}}">@lang( 'Back' )
        </a>
    </div>
    @endslot
    @endcan
    @can('user.view')
    <div class="container-fluid">
        <div class="d-md-flex align-items-center justify-content-between mb-5">
            <h1 class="mb-0">Kitty Group Details</h1>
        </div>
        <div class="card mt-2 table-responsive">
            <div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Group Name</th>
                            <th>Start Month</th>
                            <th>Total Amount</th>
                            <th>Amount Per Person</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span>{{$kittyGroup->name}}</span></td>
                            <td><span>{{$kittyGroup->start_month}}</span></td>
                            <td><span>{{$kittyGroup->total_amount}}</span></td>
                            <td><span>{{$kittyGroup->total_amount / count($kittyGroup->members) }}</span></td>
                            <td>
                                <a href="{{ route('kitties.installments', ['id' => $kittyGroup->id]) }}" class="btn btn-xs btn-info">
                                    <i class="fa fa-eye"></i> Detailed View
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <div class="d-md-flex align-items-center justify-content-between mb-5">
                <h1 class="mb-0">Group Member's List</h1>
                <div class="text-end mt-4 mt-md-0"></div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($kittyGroup->members))
                    @foreach($kittyGroup->members as $key => $member)
                    <tr>
                        <td>{{$member->customer->name}}</td>
                        <td>{{$member->customer->email}}</td>
                        <td>{{$member->customer->mobile }}</td>
                    </tr>
                    @endforeach

                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @endcan
    @endcomponent



</section>
<!-- /.content -->
@stop
@section('javascript')
@endsection
