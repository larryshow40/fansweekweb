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
           
            <div class="col-md-6">
                <div class="card text-white border-primary mb-3">
                    <div class="card-header bg-primary text-center">Regular</div>
                    <div class="card-body text-primary">
                        <div class="card-text">
                            <h5>Features</h5>
                            <li>This</li>
                            <li>This</li>
                            <li>This</li>
                            <li>This</li>
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
                            <h5>Features</h5>
                            <li>This</li>
                            <li>This</li>
                            <li>This</li>
                            <li>This</li>
                            <span>NGN 1,000</span><br/>
                            <form action="{{route('site.subscription.subscribe')}}"  method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Subscribe</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

    </div>
</div>

@endsection