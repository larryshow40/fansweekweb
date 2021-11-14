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

                @foreach ($groups as $key => $group)
                    <div class="section-title">
                        <h6 class="text-success">{{$key}}</h6>
                    </div>
                    <table class="table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Company</th>
                                    <th>Code</th>
                                    <th>Likes/Dislikes</th>
                                    {{-- <th>Date</th> --}}
                                </tr>
                            </thead>
                    @foreach ($group as $key => $code)
                            <tbody>
                                <tr>
                                    <td>
                                        @if ($code->user->id == 1)
                                            <b>Fansweek</b>
                                        @else
                                            {{ $code->user->first_name }}
                                        @endif
                                    </td>
                                    <td>{{ $code->name }}</td>
                                    <td>{{ $code->code }}</td>
                                    <td>
                                        @if (Sentinel::check())
                                            <span>
                                                <form>
                                                    <button type="button"
                                                        class="btn btn-secondary likebutton {{ $code->likes->where('user_id', Sentinel::getUser()->id)->first() ? 'clicked' : '' }}"
                                                        id="like{{ $code->id }}"><i
                                                            class="fa fa-thumbs-up"></i>&nbsp;<span>{{ $code->likes->count() }}</span></button>
                                                    <button type="button"
                                                        class="btn btn-secondary dislikebutton {{ $code->dislikes->where('user_id', Sentinel::getUser()->id)->first() ? 'clicked' : '' }}"
                                                        id="dislike{{ $code->id }}"><i
                                                            class="fa fa-thumbs-down"></i>&nbsp;<span>{{ $code->dislikes->count() }}</span></button>
                                                    <a href="{{ route('code.show', $code->id) }}"
                                                        class="btn btn-secondary"><i class="fa fa-comment"></i> &nbsp;
                                                        <span>{{ $code->comments->count() }}</span></a>
                                                </form>
                                            </span>
                                        @else
                                            <a href="{{ route('site.login.form') }}" class="btn btn-success">
                                                Login To View
                                            </a>

                                        @endif
                                    </td>
                                    {{-- <td>
                                        {{ $code->created_at->diffForHumans() }}
                                    </td> --}}
                                </tr>
                           
                    @endforeach
                                 </tbody>
                        </table>
                @endforeach


                {{ $codes->links() }}
            </div>


        </div>
    </div>

@endsection
