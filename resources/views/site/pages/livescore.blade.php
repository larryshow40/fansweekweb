@extends('site.layouts.app')

@section('content')
    <div class="sg-main-content mb-4">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-lg-8 sg-sticky">
                    <div class="theiaStickySidebar">
                        <div class="breaking-content d-flex">
                            <div class="section-title">
                                <h1>Livescore</h1>
                            </div>
                        </div>
                        <div class="">
                        

                        @foreach($livescores as $key => $group)
            
                            <div class="section-title">
                                <h3 style="color:red;">{{$key}}</h3>
                            </div>
                            @foreach($group->groupBy('section') as $key => $subgroup) 
                                    <div class="section-title">
                                        <h6 style="color:red;">{{$key}}</h6>
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
                                                    <th scope="row">{{Carbon\Carbon::parse($data['start_at'])->format('h:i')}}</th>
                                                    <td>
                                                        {{$data['home_team'][$data['name']]}}
                                                    </td>
                                                
                                                    <td>
                                                    {{$data['away_team'][$data['name']]}}
                                                    </td>
                                                    <!-- <td>{{$data['prediction']}}</td> -->
                                                    <td>
                                                        <!-- {{$data['odds'][$data['prediction']] ?? '-'}} -->
                                                    </td>
                                                    <td>
                                                        <!-- @if($data['result'])
                                                            {{$data['result']}}
                                                        @else
                                                        -
                                                        @endif -->
                                                    </td>
                                                    <td>
                                                        <a href="{{route('view.stats', $data['id'])}}" class="btn btn-success btn-sm"> Stats</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    </div>
                            @endforeach                             
                            
                            @endforeach

                        {{ $livescores->links() }}

                    </div>
                </div>
                {{-- <div class="col-md-5 col-lg-4 sg-sticky">
                <div class="sg-sidebar theiaStickySidebar">
                    @include('site.partials.right_sidebar_widgets')
                </div>
            </div> --}}
            </div>
        </div>
    </div>
@endsection
