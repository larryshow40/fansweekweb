@extends('common::layouts.master')

@section('subscription')
active
@endsection
@section('content')
<div class="container-fluid  dashboard-content">
    <h3 class="text-center">Subscriptions</h3>
    <!-- Button trigger modal -->

    <div class="col-md-12 table-responsive">
      <table class="table">
        <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Code</th>
              <th scope="col">User</th>
              <th scope="col">Amount</th>
              <th scope="col">Next Payment Date</th>
              <th scope="col">Status</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
              @foreach ($subscriptions as $key => $subscription)
                <tr>
                    <th scope="row">{{++$key}}</th>
                    <td>{{$subscription->subscription_code}}</td>
                    <td>{{$subscription->user->first_name}} {{$subscription->user->last_name}}</td>
                    <td>{{$subscription->amount}}</td>
                    <td>{{$subscription->next_payment_date}}</td>
                    <td>{{$subscription->subscription_status}}</td>
                    <td>
                      <a class="btn btn-danger" href="{{route('cancel.subscription', $subscription->id)}}"> Cancel </a>
                    </td>
                </tr>
              @endforeach
          </tbody>
    </table>
    </div>
    
</div>
@endsection
