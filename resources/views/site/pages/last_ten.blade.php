@if(!empty($home_results) && !empty($away_results))
<div class="sg-breaking-news">
    <div class="container">
        <div class="breaking-content d-flex">
            <div class="section-title">
                <h1>Last Ten Matches</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                @if(!empty($home_results))
                        <div class="section-title">
                            <h1>{{$details['home_team']}}</h1>
                        </div>

                        <div class="text-center text-white mb-2">
                            <div class="mb-2">
                                @foreach($home_results as $key => $data)
                                    @if($data == 'W')
                                        <span class="badge badge-success p-3">W</span>
                                    @endif
                                    @if($data == 'L')
                                        <span class="badge badge-danger p-3">L</span>
                                    @endif
                                    @if($data == 'D')
                                        <span class="badge badge-warning p-3">D</span>
                                    @endif
                                @endforeach
                            </div>
                            <b class="text-white text-center">Goals Scored : </b>{{$last_ten_home['stats']['goals_scored']}}<br/>
                            
                            <b class="text-white text-center">Goals Conceived : </b>{{$last_ten_home['stats']['goals_conceived']}}
                        </div>
                        <div class="table-responsive">
                            <table  class="table">
                                <thead class="text-center">
                                    <tr>
                                        <th>Home</th>
                                        <th>Result</th>
                                        <th>Away</th>
                                    
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                @foreach($last_ten_home['encounters'] as $data)   
                                    @if($data['played_away'] == true)
                                        <tr class="text-center">
                                            <td>{{$data['played_against']}}</td>
                                            <td class="text-center">{{ $data['fulltime_result'] }}<br/> {{$data['start_date']}}</td>
                                            <td><b>{{$details['home_team']}}</b></td>
                                        </tr>
                                    @else
                                        <tr class="text-center">
                                            <td><b>{{$details['home_team']}}</b></td>
                                            <td class="text-center">{{ $data['fulltime_result'] }}<br/> {{$data['start_date']}}</td>
                                            <td>{{$data['played_against']}}</td>
            
                                        </tr>
                                    @endif
            
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                
                @if(!empty($home_results))
                    <div class="col-md-6">
                        <div class="section-title">
                            <h1>{{$details['away_team']}}</h1>
                        </div>

                        <div class="text-center text-white mb-2">
                            <div class="mb-2">
                                @foreach($away_results as $key => $data)
                                    @if($data == 'W')
                                        <span class="badge badge-success p-3">W</span>
                                    @endif
                                    @if($data == 'L')
                                        <span class="badge badge-danger p-3">L</span>
                                    @endif
                                    @if($data == 'D')
                                        <span class="badge badge-warning p-3">D</span>
                                    @endif
                                @endforeach
                            </div>
                            
                            <b class="text-white text-center">Goals Scored : </b>{{$last_ten_away['stats']['goals_scored']}}<br/>
                            
                            <b class="text-white text-center">Goals Conceived : </b>{{$last_ten_away['stats']['goals_conceived']}}
                        </div>
                        <div class="table-responsive">
                            <table  class="table">
                                <thead class="text-center">
                                    <tr>
                                        <th>Home</th>
                                        <th>Result</th>
                                        <th>Away</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @foreach($last_ten_away['encounters'] as $data)    
                                        @if($data['played_away'] == true)
                                            <tr class="text-center">
                                                <td>{{$data['played_against']}}</td>
                                                <td class="text-center">{{ $data['fulltime_result'] }}<br/> {{$data['start_date']}}</td>
                                                <td><b>{{$details['away_team']}}</b></td>
                                            </tr>
                                        @else
                                            <tr class="text-center">
                                                <td><b>{{$details['away_team']}}</b></td>
                                                <td class="text-center">{{ $data['fulltime_result'] }}<br/> {{$data['start_date']}}</td>
                                                <td>{{$data['played_against']}}</td>
            
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                @endif


        </div>
        
    </div>
</div>
@endif