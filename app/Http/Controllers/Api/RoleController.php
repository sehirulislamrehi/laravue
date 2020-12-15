<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\Add;
use App\Http\Requests\Role\Update;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->per_page;
        return response()->json(['roles'=> Role::paginate($per_page)],200);
    }


    public function show($item){
        $roles = Role::where("name","Like","%$item%")->paginate();
        return response()->json(["roles" => $roles], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Add $request)
    {
        $role = new Role();
        $role->name  = $request->name;
        if($role->save()):
            return response()->json(['role'=>$role], 200);
        endif;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $id)
    {
        $role = Role::find($id);
        $role->name = $request->name;
        if($role->save()):
            return response()->json(['role'=>$role],200);
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
        $role = Role::find($id);

        if($role->delete()):
            return response()->json(['role'=>$role], 200);
        endif;

    }

    public function delete_all(Request $request){
        $roles = Role::whereIn('id',$request->roles)->delete();
        if( $roles ){
            return response()->json(['roles'=> $roles], 200);
        }
    }
    
}
