<?php

namespace App\Http\Controllers\admin;

use App\Post;
use App\Category;
use App\Tag;
use Carbon\Carbon;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;

class PostController extends Controller
{
    public function index(){

    	// $posts = Post::where('user_id', auth()->id())->get();

        // $posts = Post::all();

        // $posts = auth()->user()->posts; // tiene el mismo resultado que la linea de arriba

        $posts = Post::allowed()->get();

    	return view('admin.posts.index', compact('posts'));
    	
    }

    // public function create(){
    // 	$categories = Category::all();
    // 	$tags = Tag::all();

    // 	return view('admin.posts.create', compact('categories', 'tags'));
    // }

    public function store(Request $request){

        $this->authorize('create', new Post);

        $this->validate($request, [
            'title' => 'required|min:3',
            'url'   => 'unique:posts'
        ]);

        // $post = Post::create($request->only('title'));
        $post = Post::create($request->all());

        return redirect()->route('admin.posts.edit', compact('post'));

    }

    public function edit(Post $post){

        $this->authorize('update', $post);

        // $categories = Category::all();
        // $tags = Tag::all();

        // return view('admin.posts.edit', compact('categories', 'tags', 'post'));        
        return view('admin.posts.edit', [
            'categories' => Category::all(), 
            'tags'       => Tag::all(), 
            'post'       => $post
        ]);        

    }

    public function update(StorePostRequest $request, Post $post){

        $this->authorize('update', $post);
    	
        $post->update($request->all());

        $post->syncTags($request->get('tags'));

    	return redirect()->route('admin.posts.edit', $post)->with('flash', 'La publicación ha sido guardada');

    }

    public function destroy(Post $post){

        // $post->tags()->detach();

        // $post->photos()->delete();

        // foreach ($post->photo as $photo) {
        //     $photo->delete();
        // }

        // $post->photos->each(function($photo){
        //     $photo->delete();
        // });

        // $post->photos->each->delete();

        $this->authorize('delete', $post);

        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('flash', 'La publicación ha sido Eliminada');
    }


}
