<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Ads\Entities\AdLocation;
use Modules\Appearance\Entities\ThemeSection;
use App\Action\Prediction\AllPredictions;
use App\Action\Prediction\ListMarkets;
use App\Action\Prediction\ListFederations;
use App\Helpers\PaginationHelper;
use Illuminate\Http\Request;

class PredictionController extends Controller
{ 
  public function index(){
    $primeData = [];
    $data = (new AllPredictions())->run();
    $data =  $data->groupBy('competition_cluster' );
    // $firstData = $data['Germany'];

    $primeData['Germany'] = $data['Germany']?? [];
    $primeData['England'] = $data["England"]?? [];
    return $primeData;
    
    // $data = $data->groupBy('competition_name"');

    $federations = (new ListFederations())->run();
    $markets = (new ListMarkets())->run();

    $showPerPage = 100;

    $predictions = PaginationHelper::paginate($data, $showPerPage);
    $primePredictions = PaginationHelper::paginate($primeData, $showPerPage);

    //Test here 
  

            return $predictions ?? [];

    

    return view('site.pages.predictions', compact('predictions', 'markets', 'federations', 'primePredictions'));
 }

 public function filter(Request $request){
   $data = (new AllPredictions())->filter($request);
   $data =  $data->groupBy('competition_cluster');

   $federations = (new ListFederations())->run();

   $markets = (new ListMarkets())->run();

   $showPerPage = 10;

   $predictions = PaginationHelper::paginate($data, $showPerPage);
   
   return view('site.pages.prediction-filter', compact('predictions', 'markets', 'federations', 'request'));

 }
}