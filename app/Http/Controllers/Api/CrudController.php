<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Crud;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class CrudController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'name' => 'required',
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
            return response(['crud',$crud],200);
        endif;


    }
}
