<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Ads\Entities\AdLocation;
use Modules\Appearance\Entities\ThemeSection;
use Modules\Post\Entities\Post;
use LaravelLocalization;
use App\VisitorTracker;
use Illuminate\Support\Facades\Input;
use Sentinel;
use DB;
use Modules\Post\Entities\Category;
use App\Action\Prediction\AllPredictions;
use App\Action\Prediction\SinglePrediction;
use App\Action\Prediction\HeadToHead;
use App\Action\Prediction\LastTenHome;
use App\Action\Prediction\LastTenAway;
use Illuminate\Http\Request;
use App\CompanyCode;
use App\CompanyCodeComment;
use App\Dislike;
use App\Like;
use Carbon\Carbon;
use App\Helpers\PaginationHelper;

use Exception;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $data = (new AllPredictions())->run($request);
        $primeData = [];

        $codes = CompanyCode::latest()->where('end_date', '>=', Carbon::now()->toDateTimeString())->take(7)->get();

        $codeGroups = $codes->groupBy(function ($date) {
            return Carbon::parse($date->updated_at)->format('d M, Y');
        });

        // $firstData = $data['Germany'];
        //  return $Data;
    $showPerPage = 100;


    // $primePredictions = PaginationHelper::paginate($primeData, $showPerPage);
   

         

        // return $data;

        $groups = $data ? $data->groupBy('competition_cluster')->take(5) : [];


        $primeData['Germany'] = $groups['Germany']?? [];
        $primeData['England'] = $groups["England"]?? [];

        // return $groups;

        // return $collection;
        $primarySection             = Cache::rememberForever('primarySection', function () {
            return ThemeSection::where('is_primary', 1)->first();
        });


        if (Sentinel::check()) :

            if ($primarySection->status == 1) :

                $primarySectionPosts    = Cache::remember('primarySectionPostsAuth', $seconds = 1200, function () {
                    return Post::with(['category', 'image', 'user'])
                        ->where('visibility', 1)
                        ->where('status', 1)
                        ->where('slider', '!=', 1)
                        ->where('language', LaravelLocalization::setLocale() ?? settingHelper('default_language'))
                        ->orderBY('id', 'desc')
                        ->limit(10)->get();
                });
            else :

                $primarySectionPosts = [];

            endif;

            $sliderPosts            = Cache::remember('sliderPostsAuth', $seconds = 1200, function () {
                return  Post::with(['category', 'image', 'user'])
                    ->where('visibility', 1)
                    ->where('slider', 1)
                    ->where('status', 1)
                    ->where('language', LaravelLocalization::setLocale() ?? settingHelper('default_language'))
                    ->orderBY('id', 'desc')
                    ->limit(5)->get();
            });

            $categorySections       = Cache::remember('categorySectionsAuth', $seconds = 1200, function () {
                return ThemeSection::with('ad')
                    ->with(['category'])
                    ->where('is_primary', '<>', 1)->orderBy('order', 'ASC')
                    ->where(function ($query) {
                        $query->where('language', LaravelLocalization::setLocale() ?? settingHelper('default_language'))->orWhere('language', null);
                    })
                    ->get();
            });

            $categorySections->each(function ($section) {
                $section->load('post');
            });

            $video_posts     = Cache::remember('video_postsAuth', $seconds = 1200, function () {
                return Post::with('category', 'image', 'user')
                    ->where('post_type', 'video')
                    ->where('visibility', 1)
                    ->where('status', 1)
                    ->orderBy('id', 'desc')
                    ->where('language', LaravelLocalization::setLocale() ?? settingHelper('default_language'))
                    ->limit(8)
                    ->get();
            });

            $latest_posts       = Cache::remember('latest_postsAuth', $seconds = 1200, function () {
                return Post::with('category', 'image', 'user')
                    ->where('visibility', 1)
                    ->where('status', 1)
                    ->orderBy('id', 'desc')
                    ->where('language', LaravelLocalization::setLocale() ?? settingHelper('default_language'))
                    ->limit(6)
                    ->get();
            });

            $totalPostCount     = Cache::remember('totalPostCountAuth', $seconds = 1200, function () {
                return Post::where('visibility', 1)
                    ->where('status', 1)
                    ->orderBy('id', 'desc')
                    ->where('language', LaravelLocalization::setLocale() ?? settingHelper('default_language'))
                    ->count();
            });

        else :
            if ($primarySection->status == 1) :

                $primarySectionPosts    = Cache::remember('primarySectionPosts', $seconds = 1200, function () {
                    return Post::with(['category', 'image', 'user'])
                        ->where('visibility', 1)
                        ->where('status', 1)
                        ->where('slider', '!=', 1)
                        ->where('language', LaravelLocalization::setLocale() ?? settingHelper('default_language'))
                        ->orderBY('id', 'desc')
                        ->when(Sentinel::check() == false, function ($query) {
                            $query->where('auth_required', 0);
                        })
                        ->limit(10)->get();
                });
            else :

                $primarySectionPosts = [];

            endif;

            $sliderPosts            = Cache::remember('sliderPosts', $seconds = 1200, function () {
                return  Post::with(['category', 'image', 'user'])
                    ->where('visibility', 1)
                    ->where('slider', 1)
                    ->where('status', 1)
                    ->where('language', LaravelLocalization::setLocale() ?? settingHelper('default_language'))
                    ->when(Sentinel::check() == false, function ($query) {
                        $query->where('auth_required', 0);
                    })
                    ->orderBY('id', 'desc')
                    ->limit(5)->get();
            });

            $categorySections       = Cache::remember('categorySections', $seconds = 1200, function () {
                return ThemeSection::with('ad')
                    ->with(['category'])
                    ->where('is_primary', '<>', 1)->orderBy('order', 'ASC')
                    ->where(function ($query) {
                        $query->where('language', LaravelLocalization::setLocale() ?? settingHelper('default_language'))->orWhere('language', null);
                    })
                    ->get();
            });

            $categorySections->each(function ($section) {
                $section->load('post');
            });

            $video_posts     = Cache::remember('video_posts', $seconds = 1200, function () {
                return Post::with('category', 'image', 'user')
                    ->where('post_type', 'video')
                    ->where('visibility', 1)
                    ->where('status', 1)
                    ->when(Sentinel::check() == false, function ($query) {
                        $query->where('auth_required', 0);
                    })
                    ->orderBy('id', 'desc')
                    ->where('language', LaravelLocalization::setLocale() ?? settingHelper('default_language'))
                    ->limit(8)
                    ->get();
            });

            $latest_posts       = Cache::remember('latest_posts', $seconds = 1200, function () {
                return Post::with('category', 'image', 'user')
                    ->where('visibility', 1)
                    ->where('status', 1)
                    ->when(Sentinel::check() == false, function ($query) {
                        $query->where('auth_required', 0);
                    })
                    ->orderBy('id', 'desc')
                    ->where('language', LaravelLocalization::setLocale() ?? settingHelper('default_language'))
                    ->limit(6)
                    ->get();
            });

            $totalPostCount     = Cache::remember('totalPostCount', $seconds = 1200, function () {
                return Post::where('visibility', 1)
                    ->where('status', 1)
                    ->when(Sentinel::check() == false, function ($query) {
                        $query->where('auth_required', 0);
                    })
                    ->orderBy('id', 'desc')
                    ->where('language', LaravelLocalization::setLocale() ?? settingHelper('default_language'))
                    ->count();
            });
        endif;
        $tracker                 = new VisitorTracker();
        $tracker->page_type      = \App\Enums\VisitorPageType::HomePage;
        $tracker->url            = \Request::url();
        $tracker->source_url     = \url()->previous();
        $tracker->ip             = \Request()->ip();
        $tracker->agent_browser  = UserAgentBrowser(\Request()->header('User-Agent'));
        $tracker->save();
        return view('site.pages.home', compact('codes', 'primePredictions', 'codeGroups', 'groups', 'primarySection', 'primarySectionPosts', 'categorySections', 'sliderPosts', 'video_posts', 'latest_posts', 'totalPostCount'));
    }

    public function companyCodes()
    {
        $codes = CompanyCode::where('end_date', '>=', Carbon::today()->toDateTimeString())->latest()->paginate(10);
        $groups = $codes->groupBy(function ($date) {
            return Carbon::parse($date->updated_at)->format('d M, Y');
        });
        return view('site.pages.codes', compact('groups', 'codes'));
    }
    public function companyCodeShow($id)
    {
        if (Sentinel::check()) {
            $code = CompanyCode::findOrFail($id);
            return view('site.pages.show_code', compact('code'));
        } else {
            return redirect()->route('site.login.form');
        }
    }

    public function likeCode(Request $request)
    {
        if (Dislike::where('company_code_id', $request->code_id)->where('user_id', Sentinel::getUser()->id)->first()) {
            $getLiked = false;
        } else {
            if ($like = Like::where('company_code_id', $request->code_id)->where('user_id', Sentinel::getUser()->id)->first()) {
                $like->delete();
            } else {
                $like = new Like;
                $like->user_id = Sentinel::getUser()->id;
                $like->company_code_id =  $request->code_id ?? $_POST['code_id'];
                $like->save();
            }
        }

        $getLiked = Like::where('company_code_id', $request->code_id)->where('user_id', Sentinel::getUser()->id)->exists();

        return response()->json([
            'liked' => $getLiked
        ]);
    }

    public function dislikeCode(Request $request)
    {
        if (Like::where('company_code_id', $request->code_id)->where('user_id', Sentinel::getUser()->id)->first()) {
            $getdisLiked = false;
        } else {
            if ($dislike = Dislike::where('company_code_id', $request->code_id)->where('user_id', Sentinel::getUser()->id)->first()) {
                $dislike->delete();
            } else {
                $dislike = new Dislike;
                $dislike->user_id = Sentinel::getUser()->id;
                $dislike->company_code_id =  $request->code_id;
                $dislike->save();
            }
            $getdisLiked = Dislike::where('company_code_id', $request->code_id)->where('user_id', Sentinel::getUser()->id)->exists();
        }
        return response()->json([
            'disliked' => $getdisLiked
        ]);
    }

    public function storeComment(Request $request)
    {
        // $request->validate([
        //     'body' => 'required',
        // ]);

        if (empty($request->body)) {
            return redirect()->back()->with('error', 'Please fill the comment field');
        }

        $input = $request->all();
        $input['user_id'] = Sentinel::getUser()->id;

        CompanyCodeComment::create($input);

        return back()->with('success', 'Comment Added Successfully');
    }



    public function stats($id)
    {

        $head_to_head = (new HeadToHead())->run($id);
        $last_ten_away = (new LastTenAway())->run($id);
        $last_ten_home = (new LastTenHome())->run($id);
        $details = (new SinglePrediction())->run($id);
        if (!empty($last_ten_away)) {
            $away_results = str_split($last_ten_away['stats']['results']);
        } else {
            $away_results = [];
        }

        if (!empty($last_ten_home)) {
            $home_results = str_split($last_ten_home['stats']['results']);
        } else {
            $home_results = [];
        }

        if (empty($head_to_head)) {
            $home_results = [];
        }
        // return $details;

        return view('site.pages.stats', compact('head_to_head', 'last_ten_away', 'last_ten_home', 'details', 'away_results', 'home_results'));
    }
}
