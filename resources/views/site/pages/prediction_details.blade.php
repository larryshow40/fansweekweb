<div class="sg-breaking-news">
    <div class="container">
        <div class="breaking-content d-flex">
            <div class="section-title">
                <h1>Prediction Details</h1>
            </div>
        </div>

        <div class="row text-white bg-dark p-3">
            <div class="col-md-4 text-center">
                <h5>{{$details['home_team']}}</h5>

                @if(round($details['home_strength'], 2) < 1)
                    Strength: <span class="badge badge-danger p-1">{{round($details['home_strength'], 2)}}</span><br/>
                @endif

                @if(round($details['home_strength'], 2) > 1 && round($details['home_strength'], 2) < 1.5)
                    Strength: <span class="badge badge-warning p-1">{{round($details['home_strength'], 2)}}</span><br/>
                @endif

                @if(round($details['home_strength'], 2) > 1.5)
                    Strength: <span class="badge badge-success p-1">{{round($details['home_strength'], 2)}}</span><br/>
                @endif

                <span class="badge badge-light text-success p-2 mt-2"><b>Home Team</b></span>
            </div>
            <div class="col-md-4 text-center">
                <h5>{{$details['competition_cluster']}}</h5>

                <h2>{{$details['result']}}</h2>

                <h6>{{$details['season']}} {{$details['competition_name']}}</h6>

                <h6>{{Carbon\Carbon::parse($details['start_date'])->toDayDateTimeString()}}</h6>

                @if($details['distance_between_teams'] != null)
                    <span>Distance Between Teams: {{$details['distance_between_teams']}}KM</span>
                @endif

                @if($details['stadium_capacity'])
                    <span>Stadium Capacity: {{$details['stadium_capacity']}}</span>
                @endif

            </div>
            <div class="col-md-4 text-center">
                <h5>{{$details['away_team']}}</h5>

                @if(round($details['away_strength'], 2) < 1)
                    Strength: <span class="badge badge-danger p-1">{{round($details['away_strength'], 2)}}</span><br/>
                @endif

                @if(round($details['away_strength'], 2) > 1 && round($details['away_strength'], 2) < 1.5)
                    Strength: <span class="badge badge-warning p-1">{{round($details['away_strength'], 2)}}</span><br/>
                @endif

                @if(round($details['away_strength'], 2) > 1.5)
                    Strength: <span class="badge badge-success p-1">{{round($details['away_strength'], 2)}}</span><br/>
                @endif

                <span class="badge badge-light text-info p-2 mt-2"><b>Away Team</b></span>
            </div>
        </div>

    </div>
</div>
