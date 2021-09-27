<?php

namespace App\Http\Controllers\Api;

use App\CompanyCode;
use App\CompanyCodeComment;
use App\Dislike;
use App\Http\Controllers\Controller;
use App\Like;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function storeCode(Request $request){
        if (CompanyCode::where('code', $request->code)->Where('name', $request->name)->exists()) {
            return redirect()->back()->with('error', 'Oops! Code exists already');;
        } else {
            $code = new CompanyCode;
            $code->user_id = Sentinel::getUser()->id;
            $code->name = $request->name;
            $code->code = $request->code;
            $code->save();
            return response()->json([
                "status" => true,
                'message'=> "Added Successfully",
                "data" => $code
            ]);
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
                $like = new Like();
                $like->user_id = Sentinel::getUser()->id;
                $like->company_code_id =  $request->code_id ?? $_POST['code_id'];
                $like->save();
            }
        }

        $getLiked = Like::where('company_code_id', $request->code_id)->where('user_id', Sentinel::getUser()->id)->exists();

        return response()->json([
            "status" => true,
            'message' => "Liked",
            "data" => $getLiked
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
            "status" => true,
            'message' => "Disiked",
            "data" => $getdisLiked
        ]);
    }

    public function storeComment(Request $request)
    {
        if (empty($request->body)) {
            return redirect()->back()->with('error', 'Please fill the comment field');
        }

        $input = $request->all();
        $input['user_id'] = Sentinel::getUser()->id;

        $comment = CompanyCodeComment::create($input);

        return response()->json([
            "status" => true,
            'message' => "Added Successfully",
            "data" => $comment
        ]);
    }

    public function listCodes()
    {
        $codes = CompanyCode::paginate(10);
        return response()->json([
            "status" => true,
            'message' => "COdes Retrieved Successfully",
            "data" => $codes
        ]);
    }
}
