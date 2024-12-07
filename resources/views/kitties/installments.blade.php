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
            <h1 class="mb-0">Kitties Installments</h1>
        </div>
        <div class="card mt-2 table-responsive">
            <div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Due Date</th>
                            <th>Action</th>
                            <th>Winner of Month</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($response['installment_data'] as $key => $installment)
                        <tr>
                            <td><span>{{ \Carbon\Carbon::parse($installment['due_date'])->monthName }}</span></td>
                            <td><span>{{ $installment['due_date'] }}</span></td>
                            <td>
                                <span>
                                    <a href="#" class="btn btn-xs btn-info">
                                        <i class="fa fa-eye"></i>View Installments
                                    </a>
                                </span>
                            </td>
                            <td>
                                @php
                                $badge = '<span class="badge bg-success">No selected winner</span>'; // Default badge
                                @endphp

                                @foreach($response['kitty_members'] as $kitty_member)
                                @if($kitty_member['has_won'] == 1 && isset($installment['won_month']))
                                @php
                                $badge = '<span class="badge bg-success">' . e($kitty_member['name']) . '</span>';
                                @endphp
                                @break
                                <!-- Stop the loop once the winner is found -->
                                @endif
                                @endforeach
                                {!! $badge !!}
                            </td>
                        </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>

    </div>
    @endcan
    @endcomponent



</section>
<!-- /.content -->
@stop
@section('javascript')
@endsection
