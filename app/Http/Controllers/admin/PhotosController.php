<?php

namespace App\Http\Controllers\Admin;


use App\Post;
use App\Photo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


class PhotosController extends Controller
{
    public function store(Post $post){

    	//validar los datos

    	$this->validate(request(), [
    		'photo' => 'required|image|max:2048',
    	]);



    	$photo = request()->file('photo')->store('public'); //carpeta en donde se guarda las imagenes

    	// $photoUrl =  Storage::url($photo);

        $post->photos()->create([
            'url' => Storage::url($photo)
        ]);

    	// Photo::create([

    	// 	'url' => Storage::url($photo),
    	// 	'post_id' => $post->id

    	// ]);



    }

    public function destroy(Photo $photo){


        $photo->delete();      

        return back()->with('flash', 'foto eliminada');

    }
}
