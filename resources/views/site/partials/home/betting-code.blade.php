<div class="sg-widget">
    <h3 style="font-weight:bolder; color:white;" class="widget-title bg-success">Today's Betting Codes</h3>
    <div class="card-body py-0">
        <div class="row mb-2 text-left">
            <div class="col-6">
                <h6>Company</h6>
            </div>
            <div class="col-6">
                <h6>
                    Betting Code
                </h6>
            </div>
        </div>
    </div>
    @foreach ($codes as $code)
        <div class="card bg-dark mb-1" style="color:white;">
            <div class="card-body py-2">
                <div class="row">
                    <div style="font-weight: bolder;" class="col-6">{{$code->name}}</div>
                    <div class="col-6">{{$code->code}}</div>
                </div>
                
            </div>
        </div>
    @endforeach

</div>