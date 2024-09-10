<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Dotenv\Validator as DotenvValidator;
use Illuminate\Http\Request;
use Illuminate\Container\Attributes\Auth as AttributesAuth;
use Illuminate\Support\Facades\Auth as Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator as Validator;

class PostController extends Controller
{
    public function index(){
        return PostResource::collection(Post::where('author_id',Auth::user()->id)->with(['comment','tags','categories'])->orderBy('id','DESC')->paginate(10));
    }
    
    // public function addTags(Request $request,Post $post){
    //     $post->tags()->syncWithoutDetaching($request->tags);
    //     return 'Attached' ;
    // }

    // store new Post into the database
    public function store(Request $request){
        $validators=Validator::make($request->all(),[
            'title'=>'required',
            'tags'=>'required',
            'categories'=>'required',
            'content'=>'required'
        ]);
    
            $Post=new Post();
            $Post->title=$request->title;
            $Post->slug=strtolower(implode('-',explode(' ',$request->title)));
            $Post->author_id=Auth::user()->id;
            $Post->content=$request->content;
            $Post->save();
            $Post->categories()->attach($request->categories);
            $Post->tags()->attach($request->tags);
            return Response::json(['success'=>'Post created successfully !']);
        
    }
    // show a specific Post by id
    public function show($id){   
        $post = Post::with('comment')->FindOrFail($id);     
   
            return new PostResource($post);
       
    }

    // update article using id
    public function update(Request $request,string $updateid){
        $validators=Validator::make($request->all(),[
            'title'=>'required',
            'categories'=>'required',
            'tag'=>'required',
            'content'=>'required'
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            $Post=Post::where('id',$updateid)->where('author_id',Auth::user()->id)->first();
            if($Post){
                $Post->title=$request->title;
                $Post->slug=strtolower(implode('-',explode(' ',$request->title)));
                $Post->author_id=Auth::user()->id;
                // $Post->category_id=$request->category;
                // $Post->tag_id=$request->tag;
                $Post->content=$request->content;
                $Post->save();
                $Post->categories()->sync($request->categories);
                $Post->tags()->sync($request->tags);
                return Response::json(['success'=>'Post updated successfully !']);
            }else{
                return Response::json(['error'=>'Post not found !']);
            }            
        }
    }
      // remove article using id
      public function destroy(Request $request,string $destroyid){
        try{
            $Post=Post::where('id',$destroyid)->where('author_id',Auth::user()->id)->first();
            $Post->categories()->detach();
            $Post->tags()->detach();
            if($Post){
                $Post->delete();
                return Response::json(['success'=>'Post removed successfully !']);
            }else{
                return Response::json(['error'=>'Post not found!']);
            }
        }catch(\Illuminate\Database\QueryException $exception){
            return Response::json(['error'=>'Post belongs to comment.So you cann\'t delete this Post!']);
        }        
    }

}
