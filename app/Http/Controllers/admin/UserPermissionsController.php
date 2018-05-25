<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UserPermissionsController extends Controller
{
    public function update(Request $request, User $user){

    	$user->permissions()->detach();

    	if($request->filled('permissions')){

    		$user->givePermissionTo($request->permissions);

    	}


    	return back()->withFlash('Los permisos han sido actualizados');

    }
}
