<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User as ResourcesUser;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();

        if (Hash::check($request->password, $user->password)) :
            if ($user) :  
                $token = Str::random(80);
                $user->api_token = $token;
                $user->save();
                return response()->json(['token' => $token, 'user'=>$user], 200);
            endif;
        // if( Auth::user() ):
        //     Auth::user()->api_token = Str::random(80);
        //     Auth::user()->save();
        //     return response()->json(['token'=>$token], 200);
        // endif;
        endif;


        return response()->json(['status' => 'User email or password wrong'], 403);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:users,name',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed|min:3',
        ]);

        if( $validator->fails() ){
            return response()->json(['error' => $validator->errors()], 422);
        }else{
            $user = new User();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            if ($user->save()) :
                return response()->json(['status', 'Registration successfully done'], 200);
            else :
                return response()->json(['status', 'Registration Failed'], 403);
            endif;
        }
        
    }

    public function verify(Request $request){
        return $request->user()->only('name','email');
    }


    public function index(Request $request)
    {
        $per_page = $request->per_page;
        return response()->json(['users'=> User::paginate($per_page)],200);
    }


    public function show($item){
        $users = User::where("name","Like","%$item%")->paginate();
        return response()->json(["users" => $users], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->name  = $request->name;
        $user->email  = $request->email;
        if($user->save()):
            return response()->json(['user'=>$user], 200);
        endif;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->email  = $request->email;
        if($user->save()):
            return response()->json(['user'=>$user],200);
        endif;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if($user->delete()):
            return response()->json(['user'=>$user], 200);
        endif;

    }



    public function index(Request $request)
    {
        $per_page = $request->per_page;
        return response()->json(['users'=> ResourcesUser::collection(User::paginate($per_page)), 'roles' => Role::pluck('name')->all() ],200);
    }


    public function show($item){
        $users = User::where("name","Like","%$item%")->paginate();
        return response()->json(["users" => $users], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->name  = $request->name;
        if($user->save()):
            return response()->json(['user'=>$user], 200);
        endif;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        if($user->save()):
            return response()->json(['user'=>$user],200);
        endif;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if($user->delete()):
            return response()->json(['user'=>$user], 200);
        endif;

    }

    public function delete_all(Request $request){
        $users = User::whereIn('id',$request->users)->delete();
        if( $users ){
            return response()->json(['users'=> $users], 200);
        }
    }
}
