<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
  // show all the categories
  public function index(){
    return CategoryResource::collection(Category::orderBy('id','DESC')->paginate(10));
}


// show a specific category by id
    public function show($id)
    {        
      if(Category::where('id',$id)->first()){
        return new CategoryResource(Category::findOrFail($id));
        }else{
        return Response::json(['error'=>'Category not found!']);
        }
    }

// store new category into the database
public function store(Request $request){
    $validators=Validator::make($request->all(),[
        'title'=>'required|unique:categories',
    
    ]);
    if($validators->fails()){
        return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
    }else{
        $category=new Category();
        $category->title=$request->title;
        $category->slug=strtolower(implode('-',explode(' ',$request->title)));
        $category->save();
        return Response::json(['success'=>'Category created successfully !']);
    }
}

    // update category using id
    public function update(Request $request,String $updateid){
        $validators=Validator::make($request->all(),[
            'title'=>['required',Rule::unique('categories')->ignore($updateid)],
            'slug'=>['required',Rule::unique('categories')->ignore($updateid)]
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            $category=Category::findOrFail($updateid);
            $category->title=$request->title;
            $category->slug=strtolower(implode('-',explode(' ',$request->slug)));
            $category->save();
            return Response::json(['success'=>'Category updated successfully !']);
        }
    }

    // remove category using id
    public function destroy(Request $request,String $destroyid){
        try{
            $category=Category::where('id',$destroyid)->first();
            if($category){
                $category->delete();
                return Response::json(['success'=>'Category removed successfully !']);
            }else{
                return Response::json(['error'=>'Category not found!']);
            }
        }catch(\Illuminate\Database\QueryException $exception){
            return Response::json(['error'=>'Category belongs to an Post.So you cann\'t delete this category!']);
        }        
    }

}
