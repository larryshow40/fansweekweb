<div class="sg-widget">
    <h3 style="font-weight:bolder; color:white;" class="widget-title bg-success">Today's Betting Codes</h3>
    <div class="card-body py-0">
        <div class="text-right">
        <button type="button" class="btn btn-secondary mb-4" data-toggle="modal" data-target="#exampleModal">
            Submit Code
        </button>
    </div>
        <div class="row mb-2 text-left">
            <div class="col-5">
                <h6>Company</h6>
            </div>
            <div class="col-4">
                <h6>
                    Betting Code
                </h6>
            </div>
            <div class="col-3">
                <h6>
                    
                </h6>
            </div>
        </div>
    </div>
    @foreach ($codes as $code)
        <div class="card bg-dark mb-1" style="color:white;">
            <div class="card-body py-2">
                <div class="row">
                    <div style="font-weight: bolder;" class="col-5">{{ $code->name }}</div>
                    <div class="col-4">
                        {{ $code->code }}                        
                    </div>
                    <div class="col-3">
                        @if(Sentinel::check())
                            <span>
                                <span class="fa fa-thumbs-up"></span>
                                <span class="fa fa-thumbs-down"></span>
                            </span>
                        @else
                            {{-- <a style="text-decoration: underline;" href="{{route('site.login.form')}}">Login To View</a> --}}
                        @endif
                    </div>
                </div>

            </div>
        </div>
    @endforeach
    <a href="{{route('all-codes')}}" class="btn btn-secondary btn-block">View All</a>
</div>
