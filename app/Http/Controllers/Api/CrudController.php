<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Crud;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class CrudController extends Controller
{
    public function all(){
        $crud = Crud::all();
        return response()->json(['crud'=> $crud],200);
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required|unique:cruds,name,',
            'image' => 'required',
        ]);

        $crud = new Crud();

        $crud->name = $request->name;

        if($request->image):
            $image = $request->image;
            $img = rand(0,100) .'.'. $image->getClientOriginalExtension();
            $location =  public_path('images/crud/'. $img);
            Image::make($image)->save($location);
            $crud->image = $img;
        endif;

        if($crud->save()):
            return response(['crud' =>$crud],200);
        endif;
    }

    public function update( Request $request ,$id){
        
        
        $request->validate([
            'name' => 'required|unique:cruds,name,'.$id,
            'image' => 'required',
        ]);

        $crud = Crud::find($id);

        $crud->name = $request->name;
        if($request->file('image')):
            if(File::exists(public_path('images/crud/'.$crud->image))):
                File::delete(public_path('images/crud/'.$crud->image));
            endif;
            $image = $request->image;
            $img = rand(0,100) .'.'. $image->getClientOriginalExtension();
            $location =  public_path('images/crud/'. $img);
            Image::make($image)->save($location);
            $crud->image = $img;
        endif;

        if($crud->save()):
            return response(['crud'=>$crud], 200);
        endif;
    }
}
