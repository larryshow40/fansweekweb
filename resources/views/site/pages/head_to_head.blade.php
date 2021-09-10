<div class="sg-breaking-news">
    <div class="container">
        <div class="breaking-content d-flex">
            <div class="section-title">
                <h1>Head To Head</h1>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead class="text-center">
                    <tr>
                        <th>Home</th>
                        <th>Result</th>
                        <th>Away</th>
                       
                    </tr>
                </thead>
                <tbody>
                    
                @foreach($head_to_head as $data)    
                    <tr class="text-center">
                        <td>{{$data['home_team']}}</td>
                        <td class="text-center">{{ $data['fulltime_result'] }}<br/> {{$data['start_date']}}</td>
                        <td>{{$data['away_team']}}</td>
                       
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        
    </div>
</div>