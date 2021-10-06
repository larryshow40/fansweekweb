<?php

namespace App\Http\Controllers\Api;

use App\CompanyCode;
use App\CompanyCodeComment;
use App\Dislike;
use App\Http\Controllers\Controller;
use App\Like;
use Modules\User\Entities\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use carbon\carbon;
use Activation;

class ApiController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255'],
            'password'      => ['required', 'string', 'min:6'],
            'phone'         => ['min:11', 'max:14'],
            'dob'           => 'required',
            'gender'        => 'required',
        ]);
        try {

            $user = User::where('email', $request->email)->first();

            if (!blank($user)) {
                if ($user->withActivation == "") {

                    $user->password             = bcrypt($request->password);
                    $user->first_name           = $request->first_name;
                    $user->last_name            = $request->last_name;
                    $user->dob                  = $request->dob;
                    $user->phone                = $request->phone;
                    $user->gender               = $request->gender;
                    $user->is_password_set      = 1;
                    $user->save();

                    $activation         = Activation::create($user);

                    sendMail($user, $activation->code, 'activate_account', $request->password);

                    return $this->returnToken($user, $request);
                } else {
                    return response()->json([
                        "status" => false,
                        'message' => "Email already exists.",
                    ]);
                }
            }

            $user               = Sentinel::register($request->all());
            $role               = Sentinel::findRoleBySlug('user');

            $role->users()->attach($user);

            $activation         = Activation::create($user);

            sendMail($user, $activation->code, 'activate_account', $request->password);

            return $this->returnToken($user, $request);
        } catch (\Exception $e) {
            return $this->resourceError($e->getMessage());
        }
    }
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email'         => ['required', 'string', 'email', 'max:255'],
                'password'      => ['required', 'string'],
            ]);
            $user = User::where('email', $request->email)->first();

            if (blank($user)) {
                return response()->json([
                    "status" => false,
                    'message' => "Invalid Credentials",
                ]);
            } elseif ($user->is_user_banned == 0) {
                return response()->json([
                    "status" => false,
                    'message' => "Your account has been banned.",
                ]);
            }

            if (!Hash::check($request->get('password'), $user->password)) {
                return response()->json([
                    "status" => false,
                    'message' => "Invalid Credentials",
                ]);
            }

            $user = Sentinel::authenticate($request->all());

            return $this->returnToken($user, $request);
        } catch (\Exception $e) {
            return $this->resourceError($e->getMessage());
        }
    }
    public function storeCode(Request $request)
    {
        return Sentinel::getUser()->id;
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
                'message' => "Added Successfully",
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
        $codes = CompanyCode::where('end_date', '<=', Carbon::now()->toDateTimeString())->paginate(10);
        return response()->json([
            "status" => true,
            'message' => "Codes Retrieved Successfully",
            "data" => $codes
        ]);
    }
}
