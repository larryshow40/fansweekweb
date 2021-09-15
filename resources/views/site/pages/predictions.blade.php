@extends('site.layouts.app')

@section('content')
    <div class="sg-main-content mb-4">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-lg-8 sg-sticky">
                    <div class="theiaStickySidebar">
                        <div class="breaking-content d-flex">
                            <div class="section-title">
                                <h1>Prediction</h1>
                            </div>
                        </div>
                        <div class="">
                        <form action=" {{ route('filter.predictions') }}"
                            method="GET">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="inputPassword4">Federations:</label>
                                    <select class="custom-select mr-sm-2" name="federation" id="inlineFormCustomSelect">
                                        <option value=" " selected>Federations</option>
                                        @foreach ($federations as $data)
                                            <option value="{{ $data }}">{{ $data }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="inputPassword4">Date:</label>
                                    <input type="date" class="form-control text-white" name="date">
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="inputPassword4">Markets:</label><br />
                                    @foreach ($markets as $data)
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="{{ $data }}" value="{{ $data }}"
                                                name="market" class="custom-control-input"
                                                {{ $data == 'classic' ? 'checked' : '' }}>
                                            <label class="custom-control-label mb-2" for="{{ $data }}">

                                                @if ($data == 'classic')
                                                    1X2
                                                @endif

                                                @if ($data == 'away_over_05')
                                                    Away(Over 0.5)
                                                @endif

                                                @if ($data == 'btts')
                                                    Both Teams To Score
                                                @endif

                                                @if ($data == 'over_25')
                                                    Away(Over 2.5)
                                                @endif

                                                @if ($data == 'home_over_05')
                                                    Home(Over 0.5)
                                                @endif

                                                @if ($data == 'home_over_15')
                                                    Home(Over 1.5)
                                                @endif

                                                @if ($data == 'away_over_15')
                                                    Away(Over 1.5)
                                                @endif

                                                @if ($data == 'over_35')
                                                    Over 3.5
                                                @endif

                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>


                            <div class="form-row">


                            </div>




                            <button type="submit" class="btn btn-success mb-2">Filter</button>
                            </form>
                        </div>
       

                        @foreach($predictions as $key => $group)
            
                            <div class="section-title">
                                <h3 style="color:red;">{{$key}}</h3>
                            </div>
                            @foreach($group->groupBy('competition_name') as $key => $subgroup) 
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
                                                    <th scope="row">{{Carbon\Carbon::parse($data['start_date'])->format('h:i')}}</th>
                                                    <td>
                                                        {{$data['home_team']}}
                                                    </td>
                                                
                                                    <td>
                                                        {{$data['away_team']}}
                                                    </td>
                                                    <td>{{$data['prediction']}}</td>
                                                    <td>
                                                        {{$data['odds'][$data['prediction']] ?? '-'}}
                                                    </td>
                                                    <td>
                                                        @if($data['result'])
                                                            {{$data['result']}}
                                                        @else
                                                        -
                                                        @endif
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

                        {{ $predictions->links() }}

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
