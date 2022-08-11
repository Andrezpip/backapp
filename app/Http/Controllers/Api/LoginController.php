<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use App\Mail\RegistroMailable;
use Illuminate\Support\Facades\Mail;
use Exception;

class LoginController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() //trae todos los usuarios
    {
        try{
            $users = User::all();
            return $users;
        }

        catch (Exception $e){
            return ["error:",$e->getMessage()];
        } 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) // guarda un usuario
    {
        try{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->phone = $request->phone;
    
            $user->save();
    
            $correo = new RegistroMailable;
        
            Mail::to($request->email)->send($correo);
            
            return $request->email;
        }
        catch (Exception $e){
            return ["error:",$e->getMessage()];
        }      

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) //muestra un solo usuario
    {
        try{
            $user = User::find($id);
            return $user;   
        }
       
        catch (Exception $e){
            return ["error:",$e->getMessage()];
        } 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) //actualiza un usuario
    {
        try{
            $user = User::findOrFail($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->phone = $request->phone;
    
            $user->save();
            return $user;
        }
        catch (Exception $e){
            return ["error:",$e->getMessage()];
        } 
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) //eliminar un usuario
    {
        try{
            $user = User::findOrFail($id);
            if (!empty($user)){
                $user = User::destroy($id);
                return ["message: El usuario fue borradó exitosamente"];
            }
        }
        catch (Exception $e){
            return ["error:",$e->getMessage()];
        } 
    }

    public function login(Request $request)
    {
        try{
            $user =  User::where('email', $request->email)->first();
            if(!$user || ($request->password != $user->password))
            {
                return ["error" => "usuario o contraseña incorrectos"];
            }
            return $user;
        }
       
        catch (Exception $e){
            return ["error:",$e->getMessage()];
        } 
    }
}
