@extends('site.layouts.app')

@section('content')
    <div class="sg-main-content mb-4">
        <div class="container">
            <div class="row">
                <div class="col-md- col-lg-24 sg-sticky">
                    <div class="theiaStickySidebar">
                        <div class="breaking-content d-flex">
                            <div class="section-title">
                                <h1>Livescore</h1>
                            </div>
                        </div>
                        <div class="">
                        

                        {{-- @foreach($livescores as $key => $group) --}}
                       
            
                            @foreach($livescores->groupBy('section.name') as $key => $subgroup) 
                                    <div class="section-title">
                                            <h5 style="color:red;">
                                    @php
                                        $image = $subgroup[0]["league"]['logo'] ?? null;
                                        $section = $subgroup[0]["section"]['name'] ?? null;
                                        $league = $subgroup[0]["league"]['name'] ?? null;
                                        echo '<img src='.$image.' alt="" border=3 height=30 width=30></img> ' .$section . ' | ' . $league;
                                    @endphp
                                    </h5>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table ">
                                            <thead>
                                                <tr>
                                                    <th>Time</th>
                                                    <th>Home</th>
                                                    <th>Score</th>
                                                    <th>Away</th>
                                                    <th>1</th>
                                                    <th>X</th>
                                                    <th>2</th>
                                                    <!-- <th>Score</th> -->
                                                    {{-- <th>Stats</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                        
                                            @foreach ($subgroup as $data)
                                                <tr class="table-active">
                                                    <th scope="row">{{Carbon\Carbon::parse($data['start_at'])->format('h:i')}} - {{$data['status']}}</th>
                                                    <td>
                                                    {{$data['home_team']['name']}}
                                                    </td>
                                                
                                                    <th scope="row">
                                                    {{$data['home_score']['current'] ?? ''}} -  {{$data['away_score']['current'] ?? ''}}

                                                    </th>
                                                    <!-- {{$data['status']}} -->

                                                    <td>
                                                    {{$data['away_team']['name']}}

                                                    </td>
                                                    <td>
                                                    {{$data['main_odds']['outcome_1']['value'] ??'-'}}
                                                       
                                                    </td><td>
                                                    {{$data['main_odds']['outcome_x']['value'] ??'-'}}
                                                       
                                                    </td><td>
                                                    {{$data['main_odds']['outcome_2']['value'] ??'-'}}
                                                       
                                                    </td>
                                                    {{-- <td>
                                                        <a href="{{route('view.stats', $data['id'])}}" class="btn btn-success btn-sm"> Stats</a>
                                                    </td> --}}
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    </div>
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
