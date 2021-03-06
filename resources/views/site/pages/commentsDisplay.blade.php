@foreach($comments as $comment)
    <div class="display-comment" @if($comment->parent_id != null) style="margin-left:40px;" @endif>
        <strong>{{ $comment->user->first_name }}-</strong>
        <span>{{ $comment->body }}</span>
        <br/>
        <small>{{$comment->created_at->diffForHumans()}}</small>
        <a href="" id="reply"></a>
        <form class="mt-3" method="post" action="{{ route('comments.store') }}">
            @csrf
            <div class="form-group">
                <input type="text" name="body" class="form-control" />
                <input type="hidden" name="company_code_id" value="{{ $code->id }}" />
                <input type="hidden" name="parent_id" value="{{ $comment->id }}" />
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Reply" />
            </div>
        </form>
        @include('site.pages.commentsDisplay', ['comments' => $comment->replies])
    </div>
@endforeach

{{-- {{$comments}} --}}