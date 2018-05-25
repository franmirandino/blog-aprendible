<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{

	protected $fillable = [
        'title', 'body', 'iframe', 'excerpt', 'published_at', 'category_id', 'user_id'
    ];

    protected $dates = ['published_at'];

    // protected $with = ['category', 'tags', 'owner', 'photos'];


    protected static function boot(){
        parent::boot();

        static::deleting(function($post){
            
            $post->tags()->detach();
            $post->photos->each->delete();   

        });

    }

    //sobreescribimos la ruta del post
    public function getRouteKeyName(){
        return 'url';
    }

    public function category(){
    	return $this->belongsTo(Category::class);
    }

    public function photos(){
        return $this->hasMany(Photo::class);
    }

    public function owner(){
        return $this->belongsTo(User::class, 'user_id');
    }


    public function tags(){
    	return $this->belongsToMany(Tag::class);
    }

    public function scopePublished($query){

    	$query->with(['category', 'tags', 'owner', 'photos'])
                ->whereNotNull('published_at')
    			->where('published_at', '<=', Carbon::now())
    			->latest('published_at');
    			

    }

    public function scopeAllowed($query){

        if( auth()->user()->can('view', $this)){

            return $query;


        }else{
            
            return $query->where('user_id', auth()->id());
        }

    }

    public function escopeByYearAndMonth($query){

        return $query->selectRaw('year(published_at) year')//devuelve el año de la publicacion
                ->selectRaw('month(published_at) month')//devuelve el mes de la publicacion
                ->selectRaw('monthname(published_at) monthname')//devuelve el mes de la publicacion
                ->selectRaw('count(*) posts')// un contador de posts 
                ->groupBy('year','month', 'monthname'); //agrupamos los años iguales
                // ->orderBy('published_at')
                
    }

    public function isPublished(){
        return ! is_null($this->pusblished_at) && $this->published_at < today();
    }

    public static function create(array $attributes = []){

        $attributes['user_id'] = auth()->id();

        $post = static::query()->create($attributes);

        $post->generarUrl();

        return $post;

    }

    public function generarUrl(){

        $url = str_slug($this->title);

        if($this->whereUrl($url)->exists()){

            $url =  "{$url}-{$this->id}";

        }

        $this->url = $url;


        $this->save();
    }

    // public function setTitleAttribute($title){

    //     $this->attributes['title'] = $title;

    //     $url = str_slug($title);

    //     $duplicateUrlCount = Post::where('url', 'LIKE', "{$url}%")->count();

    //     if($duplicateUrlCount){

    //         $url .= "-" . ++$duplicateUrlCount;

    //     }

    //     $this->attributes['url'] = $url;

    // }

    public function setPublishedAtAttribute($published_at){

        $this->attributes['published_at'] = $published_at
                                ? Carbon::parse($published_at)
                                : null;

    }

    public function setCategoryIdAttribute($category){

        $this->attributes['category_id'] = Category::find($category)
                                ? $category
                                : Category::create(['name' => $category])->id;

    }

    public function syncTags($tags){
        $tagIds = collect($tags)->map(function($tag){
            return Tag::find($tag) ? $tag : Tag::create(['name' => $tag])->id;
        });      

        return $this->tags()->sync($tagIds);//añadimos las etiquetas
    }

    public function viewType($home = ''){

        if($this->photos->count() === 1):

            return('posts.photo');

        elseif($this->photos->count() > 1):
        
            return $home === 'home' ? 'posts.carousel-preview' : 'posts.carousel';

        elseif($this->iframe):

            return('posts.iframe');

        else:

            return 'posts.text';

        endif;

    }

}
