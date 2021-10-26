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
            @if ($activeSubscription === 0)               
            <div class="col-md-6">
                <div class="card text-white border-primary mb-3">
                    <div class="card-header bg-primary text-center">FREE - Regular</div>
                    <div class="card-body text-primary">
                        <div class="card-text mb-2 ">
                            <span>&#9989; Betting codes </span><br>
                            <span>&#9989; Access to 1X2 Markets </span><br>
                            <span>&#9989; Share Betting Codes </span><br>
                            <span>&#9989; Access to 1X2 Markets </span><br>
                            <span>&#10060;Email Notification  </span><br>
                            <span>&#10060; SMS</span><br>
                            <span>&#10060; OVER 0.5</span><br>
                            <span>&#10060; OVER 1.5</span><br>
                            <span>&#10060; OVER 2.5</span><br>
                            <span>&#10060; GG</span><br>
                            {{-- <span>&#10060; </span><br> --}}








                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card text-white border-success mb-3">
                    <div class="card-header bg-success text-center">N1,000 - Premium</div>
                    <div class="card-body text-success">
                        <div class="card-text">
                            <form action="{{route('site.subscription.subscribe')}}"  method="POST">
                                @csrf
                                <span>&#9989; Access to 20 League Games </span><br>
                            <span>&#9989; Betting codes </span><br>
                            <span>&#9989; Access to 1X2 Markets </span><br>
                            <span>&#9989; Share Betting Codes </span><br>
                            <span>&#9989; Access to 1X2 Markets </span><br>
                            <span>&#9989;Email Notification  </span><br>
                            <span>&#9989; SMS</span><br>
                            <span>&#9989; OVER 0.5</span><br>
                            <span>&#9989; OVER 1.5</span><br>
                            <span>&#9989; OVER 2.5</span><br>
                            <span>&#9989; GG</span><br> <br>
                            {{-- <span>&#9989; </span><br> --}}


                            


                                {{-- <span>NGN 1,000</span>&nbsp; --}}
                                <button type="submit" class="btn btn-sm btn-success">Subscribe Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            
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
                            <th scope="col">Action</th>
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
                                    <td>
                                        @if ($subscription->status == 1)
                                            <a class="btn btn-danger" href="{{route('cancel.subscription', $subscription->id)}}"> Cancel </a>
                                        @else
                                            <span class="text-success">Cancelled</span>
                                        @endif
                                    </td>
                                </tr>
                           @endforeach
                        </tbody>
                    </table>
                    {{$subscriptions->links()}}
                </div>
            </div>
            @endif
           
        </div>


        

    </div>
</div>

@endsection