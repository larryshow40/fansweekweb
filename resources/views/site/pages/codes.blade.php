@extends('site.layouts.app')

@section('content')
<div class="sg-breaking-news">
    <div class="container">
        <div class="breaking-content d-flex">
            <div class="section-title">
                <h1>Betting Codes</h1>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
            <thead>
                <tr>
                    <th scope="col">User</th>
                    <th scope="col">Company</th>
                    <th scope="col">Code</th>
                    <th scope="col">Likes/Dislikes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($codes as $key => $code)
                    <tr>
                        <td>{{ $code->user->first_name ?? 'N/A'}}</td>
                        <td>{{ $code->name }}</td>
                        <td>{{ $code->code }}</td>
                        <td>
                            @if(Sentinel::check())
                            <span>
                                <form>
                                    <button type="button" class="btn btn-secondary likebutton {{($code->likes->where('user_id', Sentinel::getUser()->id)->first()) ? 'clicked' : ''}}" id="like{{$code->id}}"><i class="fa fa-thumbs-up"></i>&nbsp;<span class="changeNumber{{$code->id}}">{{$code->likes->count()}}</span></button>
                                    <button type="button" class="btn btn-secondary dislikebutton {{($code->dislikes->where('user_id', Sentinel::getUser()->id)->first()) ? 'clicked' : ''}}" id="dislike{{$code->id}}"><i class="fa fa-thumbs-down"></i>&nbsp;<span class="changeNumber{{$code->id}}">{{$code->dislikes->count()}}</span></button>
                                </form>
                            </span>
                            @else
                                <a href="{{route('site.login.form')}}" onclick="return confirm('Please login to proceed with this action')" class="badge badge-secondary">
                                    <i class="fa fa-thumbs-up"></i>&nbsp;<span class="changeNumber{{$code->id}}">{{$code->likes->count()}}</span>
                                </a>
                                <a href="{{route('site.login.form')}}" onclick="return confirm('Please login to proceed with this action')" class="badge badge-secondary">
                                    <i class="fa fa-thumbs-down"></i>&nbsp;<span class="changeNumber{{$code->id}}">{{$code->dislikes->count()}}</span>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{$codes->links()}}
        </div>
        

    </div>
</div>

@endsection