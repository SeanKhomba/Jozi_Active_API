@extends('adminlte::page')

@section('title', 'Manage Payments')

@section('content_header')
    <h1 class="pull-left" style="margin-right: 20px">Manage Payments</h1>
    
 

    <div class="clearfix"></div>
@endsection

@section('content')
    @include('admin.partials.status')
    <div class="card">
            <table id="example1" class="table">
                <thead>
                <tr>
                    <th>Amount</th>
                    <th>Payment Type</th>
                    <th>Transaction Id</th>
                    <th># Booking</th>
                    <th>User</th>
                    <th>Date Created</th>
                    <th>Action</th>
                </tr>
            </thead>
                <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td>R {{ $payment->amount ?? ''}}</td>
                        <td>{{ $payment->paymentType->name ?? ''}}</td>
                        <td>{{ $payment->transaction_id ?? ''}}</td>
                        <td>{{ $payment->booking_id ?? 'N/A'}}</td>
                        <td>
                            <a href="{{ url('/admin/user/readOnly') . '/' . $payment->user_id }}">
                                 {{ $payment->user->first_name . ' ' . $payment->user->surname ?? ''}}
                            </a>
                        </td>
                        <td>{{ $payment->created_at ?? ''}}</td>
                        <td>
                            <a href="{{ url('/admin/payment/delete') . '/' . $payment->id }}" role="button"
                               name="deletePayment" id="deletePayment" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>Amount</th>
                    <th>Payment Type</th>
                    <th>Transaction Id</th>
                    <th># Booking</th>
                    <th>User</th>
                    <th>Date Created</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        </div>
    <div>{{ $payments->appends(\Request::except('page'))->render()}}</div>
@endsection


