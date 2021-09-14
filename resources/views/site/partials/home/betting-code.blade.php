<style>
    .row{
        font-size: 15px;
    }
</style>
<div class="sg-widget">
    <h3 style="font-weight:bolder; color:white;" class="widget-title bg-success">Today's Betting Codes</h3>
    <div class="card-body py-0">
        @if(Sentinel::check())
        <div class="text-right">
            <button type="button" class="btn btn-secondary mb-4" data-toggle="modal" data-target="#exampleModal">
                Submit Code
            </button>
        </div>
        @endif
        <div class="row mb-2 text-left">
            <div class="col-4">
                <h6>Company</h6>
            </div>
            <div class="col-5">
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
                    <div style="font-weight: bolder; overflow:hidden; text-overflow:ellipsis; white-space: nowrap;" class="col-4">{{ $code->name }}</div>
                    <div class="col-5">
                        {{ $code->code }}                        
                    </div>
                    <div class="col-3">
                        @if(Sentinel::check())
                            <button type="button" class="btn btn-secondary likebutton {{($code->likes->where('user_id', Sentinel::getUser()->id)->first()) ? 'clicked' : ''}}" id="like{{$code->id}}"><i class="fa fa-thumbs-up"></i>&nbsp;<span class="changeNumber{{$code->id}}">{{$code->likes->count()}}</span></button>
                            <button type="button" class="btn btn-secondary dislikebutton {{($code->dislikes->where('user_id', Sentinel::getUser()->id)->first()) ? 'clicked' : ''}}" id="dislike{{$code->id}}"><i class="fa fa-thumbs-down"></i>&nbsp;<span class="changeNumber{{$code->id}}">{{$code->dislikes->count()}}</span></button>
                        @else
                        <a href="{{route('site.login.form')}}" onclick="return confirm('Please login to proceed with this action')" class="badge badge-secondary">
                            <i class="fa fa-thumbs-up"></i>&nbsp;<span class="changeNumber{{$code->id}}">{{$code->likes->count()}}</span>
                        </a>
                        <a href="{{route('site.login.form')}}" onclick="return confirm('Please login to proceed with this action')" class="badge badge-secondary">
                            <i class="fa fa-thumbs-down"></i>&nbsp;<span class="changeNumber{{$code->id}}">{{$code->dislikes->count()}}</span>
                        </a>
                            
                            
                        @endif
                    </div>
                </div>

            </div>
        </div>
    @endforeach
    <a href="{{route('all-codes')}}" class="btn btn-secondary btn-block">View All</a>
</div>
