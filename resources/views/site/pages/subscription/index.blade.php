@extends('site.layouts.app')

@section('content')
<div class="sg-breaking-news">
    <div class="container">
        <div class="breaking-content d-flex">
            <div class="section-title">
                <h1>Subscriptions</h1>
            </div>
        </div>

        <div class="row">
            @if($subscriptions->count() > 0)
            <div class="col-md-12">
                <div class="section-title">
                    <h1>History</h1>
                </div>

                <div class="table-reponsive">
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Subscription Code</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Status</th>
                            <th scope="col">Next Payment Date</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach ($subscriptions as $subscription)
                                <tr>
                                    <th scope="row">{{$subscriptions->perPage()*($subscriptions->currentPage()-1)+$loop->iteration}}</th>
                                    <td>{{$subscription->subscription_code}}</td>
                                    <td>{{$subscription->amount}}</td>
                                    <td>{{$subscription->status == 1 ? 'Active' : 'Cancelled'}}</td>
                                    <td>{{$subscription->next_payment_date}}</td>
                                </tr>
                           @endforeach
                        </tbody>
                    </table>
                    {{$subscriptions->links()}}
                </div>
            </div>
            @endif
           @if ($activeSubscription === 0)               
            <div class="col-md-6">
                <div class="card text-white border-primary mb-3">
                    <div class="card-header bg-primary text-center">Regular</div>
                    <div class="card-body text-primary">
                        <div class="card-text mb-2 ">
                            <span>FREE</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card text-white border-success mb-3">
                    <div class="card-header bg-success text-center">Premium</div>
                    <div class="card-body text-success">
                        <div class="card-text">
                            <form action="{{route('site.subscription.subscribe')}}"  method="POST">
                                @csrf
                                <span>NGN 1,000</span>&nbsp;
                                <button type="submit" class="btn btn-sm btn-success">Subscribe</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>


        

    </div>
</div>

@endsection