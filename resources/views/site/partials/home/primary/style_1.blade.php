@php
$blockPosts = $posts->take(4);
@endphp

<div class="sg-breaking-news">
    <div class="container">
        <div class="breaking-content d-flex">
            <span>{{ __('breaking_news') }}</span>
            <ul class="news-ticker">
                @foreach ($breakingNewss as $post)
                    <li id="display-nothing">
                        <a href="{{ route('article.detail', ['id' => $post->slug]) }}">{!! \Illuminate\Support\Str::limit($post->title, 100) !!}</a>
                    </li>\
                @endforeach
            </ul>
        </div>
    </div>
</div>


<div class="sg-home-section">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-lg-8 sg-sticky">
                <div class="theiaStickySidebar">
                   
                    <h2 id="prediction" style="font-weight:bolder; font-size:19px; color:white;" class="widget-title bg-danger">Free Daily Tips and Prediction</h2>
                    <div class="text-right">
                        <form action="" method="POST">
                            @csrf
                            <div class="btn-group mb-2" role="group" aria-label="Basic example">
                                <input type="submit" name="yesterday" value="Yesterday" class="btn {{request()->yesterday ? 'btn-danger' : 'btn-secondary'}}">
                                <input type="submit" name="today" value="Today" class="btn {{(!request()->tomorrow && !request()->yesterday) ? 'btn-danger' : 'btn-secondary'}}">
                                <input type="submit" name="tomorrow" value="Tomorrow" class="btn {{request()->tomorrow ? 'btn-danger' : 'btn-secondary'}}">

                            </div>

                        </form>
                    </div>

                    @foreach ($groups as $mainkey => $group)

                        @foreach ($group->groupBy('competition_name') as $key => $subgroup)
                            <div class="section-title">
                                <h6 class="text-danger">{{ $mainkey }} {{ $key }}</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead>
                                        <tr>
                                            <th>Time</th>
                                            <th>Home</th>
                                            <th>Away</th>
                                            <th>Prediction</th>
                                            <th>Odds</th>
                                            <th>Score</th>
                                            <th>Stats</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                        @foreach ($subgroup as $data)
                                            <tr class="table-active">
                                                <th scope="row">
                                                    {{ Carbon\Carbon::parse($data['start_date'])->format('h:i') }}</th>
                                                <td>
                                                    {{ $data['home_team'] }}
                                                </td>

                                                <td>
                                                    {{ $data['away_team'] }}
                                                </td>
                                                <td>{{ $data['prediction'] }}</td>
                                                <td>
                                                    {{ $data['odds'][$data['prediction']] ?? '-' }}
                                                </td>
                                                <td>
                                                    @if ($data['result'])
                                                        {{ $data['result'] }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('view.stats', $data['id']) }}"
                                                        class="btn btn-danger btn-sm"> Stats</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        @endforeach

                    @endforeach
                    <div class="text-center">
                        <a href="{{ route('predictions') }}" class="btn btn-danger">View All</a>
                    </div>


                </div>
            </div>
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-12">
                        @include('site.partials.home.betting-code')
                    </div>
                    {{-- @php dd($blockPosts); @endphp --}}
                    {{-- @foreach ($blockPosts as $post)
                        <div class="col-md-6">
                            <div class="sg-post">
                                <div class="entry-header">
                                    <div class="entry-thumbnail">
                                        <a href="{{ route('article.detail', ['id' => $post->slug]) }}">
                                            @if (isFileExist(@$post['image'], $result = @$post['image']->medium_image))
                                                <img src="{{ safari_check() ? basePath(@$post['image']) . '/' . $result : static_asset('default-image/default-358x215.png') }} "
                                                    data-original=" {{ basePath(@$post['image']) }}/{{ $result }} "
                                                    class="img-fluid lazy" alt="{!! $post->title !!}">
                                            @else
                                                <img src="{{ static_asset('default-image/default-358x215.png') }} "
                                                    class="img-fluid" alt="{!! $post->title !!}">
                                            @endif
                                        </a>
                                    </div>
                                    @if ($post->post_type == 'video')
                                        <div class="video-icon block">
                                            <img src="{{ static_asset('default-image/video-icon.svg') }} "
                                                alt="video-icon">
                                        </div>
                                    @elseif($post->post_type=="audio")
                                        <div class="video-icon block">
                                            <img src="{{ static_asset('default-image/audio-icon.svg') }} "
                                                alt="audio-icon">
                                        </div>
                                    @endif
                                    <div class="category">
                                        <ul class="global-list">
                                            @isset($post->category->category_name)
                                                <li>
                                                    <a
                                                        href="{{ url('category', $post['category']->slug) }}">{{ $post['category']->category_name }}</a>
                                                </li>
                                            @endisset
                                        </ul>
                                    </div>
                                </div>
                                <div class="entry-content block">
                                    <a href="{{ route('article.detail', ['id' => $post->slug]) }}">
                                        <p>{!! \Illuminate\Support\Str::limit($post->title, 40) !!}</p>
                                    </a>
                                    <div class="entry-meta">
                                        <ul class="global-list">
                                            <li><a
                                                    href="{{ route('article.date', date('Y-m-d', strtotime($post->updated_at))) }}">
                                                    {{ $post->updated_at->format('F j, Y') }}</a></li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach --}}
                </div>
                <div class="row">
                    <div class="col-12 d-md-block d-lg-block d-xl-block d-none">
                        {{-- @include('site.partials.home.betting-code') --}}
                        {{-- @php dd($blockPosts); @endphp --}}
                    <h2 id="prediction" style="font-weight:bolder; font-size:19px; color:white;" class="widget-title bg-danger">Latest News</h2>

                        @foreach ($blockPosts as $post)
                            <div class="col-12">
                                <div class="sg-post">
                                    <div class="entry-header">
                                        <div class="entry-thumbnail">
                                            <a href="{{ route('article.detail', ['id' => $post->slug]) }}">
                                                @if (isFileExist(@$post['image'], $result = @$post['image']->medium_image))
                                                    <img src="{{ safari_check() ? basePath(@$post['image']) . '/' . $result : static_asset('default-image/default-358x215.png') }} "
                                                        data-original=" {{ basePath(@$post['image']) }}/{{ $result }} "
                                                        class="img-fluid lazy" alt="{!! $post->title !!}">
                                                @else
                                                    <img src="{{ static_asset('default-image/default-358x215.png') }} "
                                                        class="img-fluid" alt="{!! $post->title !!}">
                                                @endif
                                            </a>
                                        </div>
                                        @if ($post->post_type == 'video')
                                            <div class="video-icon block">
                                                <img src="{{ static_asset('default-image/video-icon.svg') }} "
                                                    alt="video-icon">
                                            </div>
                                        @elseif($post->post_type=="audio")
                                            <div class="video-icon block">
                                                <img src="{{ static_asset('default-image/audio-icon.svg') }} "
                                                    alt="audio-icon">
                                            </div>
                                        @endif
                                        <div class="category">
                                            <ul class="global-list">
                                                @isset($post->category->category_name)
                                                    <li>
                                                        <a
                                                            href="{{ url('category', $post['category']->slug) }}">{{ $post['category']->category_name }}</a>
                                                    </li>
                                                @endisset
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="entry-content block">
                                        <a href="{{ route('article.detail', ['id' => $post->slug]) }}">
                                            <p>{!! \Illuminate\Support\Str::limit($post->title, 40) !!}</p>
                                        </a>
                                        <div class="entry-meta">
                                            <ul class="global-list">
                                                <li><a
                                                        href="{{ route('article.date', date('Y-m-d', strtotime($post->updated_at))) }}">
                                                        {{ $post->updated_at->format('F j, Y') }}</a></li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Mobile Code Here --}}
{{-- <div class="row">
    <div class="col-12 d-md-none d-lg-none d-xl-none d-block">
        @include('site.partials.home.betting-code')
    </div>
</div> --}}





{{-- <div class="sg-main-content mb-4">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-lg-8 sg-sticky">
                <div class="theiaStickySidebar">
                   
                    <h2 id="prediction" style="font-weight:bolder; font-size:19px; color:white;" class="widget-title bg-danger">Free Daily Tips and Prediction</h2>
                    <div class="text-right">
                        <form action="" method="POST">
                            @csrf
                            <div class="btn-group mb-2" role="group" aria-label="Basic example">
                                <input type="submit" name="yesterday" value="Yesterday" class="btn {{request()->yesterday ? 'btn-danger' : 'btn-secondary'}}">
                                <input type="submit" name="today" value="Today" class="btn {{(!request()->tomorrow && !request()->yesterday) ? 'btn-danger' : 'btn-secondary'}}">
                                <input type="submit" name="tomorrow" value="Tomorrow" class="btn {{request()->tomorrow ? 'btn-danger' : 'btn-secondary'}}">

                            </div>

                        </form>
                    </div>

                    @foreach ($groups as $mainkey => $group)

                        @foreach ($group->groupBy('competition_name') as $key => $subgroup)
                            <div class="section-title">
                                <h6 class="text-danger">{{ $mainkey }} {{ $key }}</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead>
                                        <tr>
                                            <th>Time</th>
                                            <th>Home</th>
                                            <th>Away</th>
                                            <th>Prediction</th>
                                            <th>Odds</th>
                                            <th>Score</th>
                                            <th>Stats</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                        @foreach ($subgroup as $data)
                                            <tr class="table-active">
                                                <th scope="row">
                                                    {{ Carbon\Carbon::parse($data['start_date'])->format('h:i') }}</th>
                                                <td>
                                                    {{ $data['home_team'] }}
                                                </td>

                                                <td>
                                                    {{ $data['away_team'] }}
                                                </td>
                                                <td>{{ $data['prediction'] }}</td>
                                                <td>
                                                    {{ $data['odds'][$data['prediction']] ?? '-' }}
                                                </td>
                                                <td>
                                                    @if ($data['result'])
                                                        {{ $data['result'] }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('view.stats', $data['id']) }}"
                                                        class="btn btn-danger btn-sm"> Stats</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        @endforeach

                    @endforeach
                    <div class="text-center">
                        <a href="{{ route('predictions') }}" class="btn btn-danger">View All</a>
                    </div>


                </div>
            </div>
            <div class="col-md-5 col-lg-4 sg-sticky">
                <div class="sg-sidebar theiaStickySidebar">
                    <div class="d-md-block d-lg-block d-xl-block d-none">
                    </div>
                    @include('site.partials.right_sidebar_widgets')
                </div>
            </div>
        </div>
    </div>
</div> --}}

@include('common::addcodemodal');
