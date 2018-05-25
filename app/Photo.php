<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    protected $guarded = []; //desabilitamos la proteccion


    //metodo para que cuando se elemine una imagen de la base de datos tambien se borre de la carpeta

    protected static function boot(){
    	parent::boot();

    	static::deleting(function($photo){
    		
    		$photoPath = str_replace('storage', 'public', $photo->url);

        	Storage::delete($photoPath);	

    	});

    }

}
