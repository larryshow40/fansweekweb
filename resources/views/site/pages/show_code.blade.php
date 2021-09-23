@extends('site.layouts.app')

@section('content')
<div class="sg-breaking-news">
    <div class="container">
        <div class="breaking-content d-flex">
            <div class="section-title">
                <h1>Betting Code</h1>
            </div>
        </div>
    
        <div class="card bg-dark text-white p-3">
            <div class="col-md-12">
                <b>Code : </b> {{$code->code}}&nbsp;&nbsp;&nbsp;
                <b>Company Name : </b> {{$code->name}}&nbsp;&nbsp;&nbsp;
                <a href="#" class="badge badge-secondary likebutton {{($code->likes->where('user_id', Sentinel::getUser()->id)->first()) ? 'clicked' : ''}}" id="like{{$code->id}}"><i class="fa fa-thumbs-up"></i>&nbsp;<span class="changeNumber{{$code->id}}">{{$code->likes->count()}}</span></a>
                <a href="#" class="badge badge-secondary dislikebutton {{($code->dislikes->where('user_id', Sentinel::getUser()->id)->first()) ? 'clicked' : ''}}" id="dislike{{$code->id}}"><i class="fa fa-thumbs-down"></i>&nbsp;<span class="changeNumber{{$code->id}}">{{$code->dislikes->count()}}</span></a>          
            </div>
        </div>

        <div class="card bg-dark text-white p-3">
            <div class="col-md-12">
                <h4>Comments</h4>
  
                    @include('site.pages.commentsDisplay', ['comments' => $code->comments()->paginate(2), 'code_id' => $code->id])
   
                    <hr />
                    <h4>Add comment</h4><br/>
                    <form method="post" action="{{ route('comments.store'   ) }}">
                        @csrf
                        <div class="form-group">
                            <textarea class="form-control" name="body"></textarea>
                            <input type="hidden" name="company_code_id" value="{{ $code->id }}" />
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="Add Comment" />
                        </div>
                    </form>                 
            </div>
        </div>

    </div>
</div>

@endsection