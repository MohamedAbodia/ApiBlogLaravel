<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    public function index(){
        return TagResource::collection(Tag::orderBy('id','DESC')->paginate(10));
    }
    
    
    // show a specific Tag by id
        public function show($id)
        {        
          if(Tag::where('id',$id)->first()){
            return new TagResource(Tag::findOrFail($id));
            }else{
            return Response::json(['error'=>'Tag not found!']);
            }
        }
    
    // store new Tag into the database
    public function store(Request $request){
        $validators=Validator::make($request->all(),[
            'title'=>'required|unique:categories',
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            $Tag=new Tag();
            $Tag->title=$request->title;
            $Tag->save();
            return Response::json(['success'=>'Tag created successfully !']);
        }
    }
    
        // update Tag using id
        public function update(Request $request,String $updateid){
            $validators=Validator::make($request->all(),[
                'title'=>['required',Rule::unique('categories')->ignore($updateid)],
            ]);
            if($validators->fails()){
                return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
            }else{
                $Tag=Tag::findOrFail($updateid);
                $Tag->title=$request->title;
                $Tag->save();
                return Response::json(['success'=>'Tag updated successfully !']);
            }
        }
    
        // remove Tag using id
        public function destroy(Request $request,String $destroyid){
            try{
                $Tag=Tag::where('id',$destroyid)->first();
                if($Tag){
                    $Tag->delete();
                    return Response::json(['success'=>'Tag removed successfully !']);
                }else{
                    return Response::json(['error'=>'Tag not found!']);
                }
            }catch(\Illuminate\Database\QueryException $exception){
                return Response::json(['error'=>'Tag belongs to an Post.So you cann\'t delete this Tag!']);
            }        
        }
    
}
