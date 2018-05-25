<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function home(){

        $query = Post::published();

        if(request('month')){
            $query->whereMonth('published_at', request('month'));
        }

        if(request('year')){
            $query->whereYear('published_at', request('year'));
        }

    	$posts = $query->paginate();

        // return $posts;

	    return view('pages.home', compact('posts'));	
    }


    public function about(){
    	return view('pages.about');
    }

    public function archive(){

        // \DB::statement("SET lc_time_names = 'es_ES'" );//pone los mensajes de los meses en español

        $archive =  Post::selectRaw('year(published_at) year')//devuelve el año de la publicacion
                ->selectRaw('month(published_at) month')//devuelve el mes de la publicacion
                ->selectRaw('monthname(published_at) monthname')//devuelve el mes de la publicacion
                ->selectRaw('count(*) posts')// un contador de posts 
                ->groupBy('year','month', 'monthname') //agrupamos los años iguales
                // ->orderBy('published_at')
                ->get();

        // $archive = Post::byYearAndMonth()->get();

    	return view('pages.archive', [
            'authors'    => User::latest()->take(4)->get(),
            'categories' => Category::take(7)->get(),
            'posts'      => Post::latest('published_at')->take(5)->get(),
            'archive'    => $archive
        ]);
    }

    public function contact(){
    	return view('pages.contact');
    }
}
