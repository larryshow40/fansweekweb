<style>
    .row {
        font-size: 15px;
    }
</style>
<div class="sg-widget">
    <h3 style="font-weight:bolder; font-size:19px;  color:white;" class="widget-title mb-0 bg-danger">Today's Prediction
        Codes
    </h3>
    <div class="card-body px-0 pb-0 pt-1">
        <div class="d-flex justify-content-between">
            @php
            $mytime = Carbon\Carbon::now();
            @endphp
            <div>
                {{ $mytime->format('l jS F Y ') }}
            </div>
            <button type="button" class="btn btn-sm btn-success mb-4" data-toggle="modal" data-target="#exampleModal">
                Share Code
            </button>
        </div>
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
    @foreach ($codeGroups as $key => $group)
    {{-- <div class="section-title my-2">
        <h6 class="text-danger">{{$key}}</h6>
    </div> --}}
    @foreach ($group as $code)
    <div class="card bg-dark mb-1" style="color:white;">
        <div class="card-body py-2">
            <div class="row">
                <div style="font-weight: bolder; overflow:hidden; text-overflow:ellipsis; white-space: nowrap;"
                    class="col-4">{{ $code->name }}<br /></div>
                <div class="col-4">
                    <a style="text-decoration: none; color:lightblue;" href="{{ route('code.show', $code->id) }}">{{
                        $code->code }}<br /><i style="color: white;">{{ $code->user->first_name }}</i></a>
                </div>
                <div class="col-4 px-1">
                    @if (Sentinel::check())
                    <button type="button"
                        class="btn btn-sm btn-secondary likebutton {{ $code->likes->where('user_id', Sentinel::getUser()->id)->first() ? 'clicked' : '' }}"
                        id="like{{ $code->id }}"><i class="fa fa-thumbs-up"></i>&nbsp;<span>{{ $code->likes->count()
                            }}</span></button>
                    <button type="button"
                        class="btn btn-sm btn-secondary dislikebutton {{ $code->dislikes->where('user_id', Sentinel::getUser()->id)->first() ? 'clicked' : '' }}"
                        id="dislike{{ $code->id }}"><i class="fa fa-thumbs-down"></i>&nbsp;<span>{{
                            $code->dislikes->count() }}</span></button>
                    @else
                    <a href="{{ route('site.login.form') }}"
                        onclick="return confirm('Please login to proceed with this action')"
                        class="badge badge-secondary">
                        <i class="fa fa-thumbs-up"></i>&nbsp;<span>{{ $code->likes->count() }}</span>
                    </a>
                    <a href="{{ route('site.login.form') }}"
                        onclick="return confirm('Please login to proceed with this action')"
                        class="badge badge-secondary">
                        <i class="fa fa-thumbs-down"></i>&nbsp;<span>{{ $code->dislikes->count() }}</span>
                    </a>


                    @endif
                </div>
            </div>

        </div>
    </div>
    @endforeach
    @endforeach
    <a href="{{ route('all-codes') }}" class="btn btn-secondary btn-block">View All</a>
</div>
