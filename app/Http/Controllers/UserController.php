<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('login.registro');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

       
        $credentials =  $request->validate([
            'usuario_email' => ['required', 'email'],
            'password' => ['required'],
        ],[
            'usuario_email.required' => 'Capture email',
            'usuario_email.email'    => 'Formato de email no válido',
            'password.required'      => 'Capture su contraseña'
        ]);

         //request()->only('usuario_email','password');
  
       if (Auth::attempt($credentials)) {
            request()->session()->regenerate();
 
            return redirect()->route('index');
            //redirect()->intended('dashboard');
        }else{
            return back()->with('credenciales','Credenciales no existentes');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'usuario_nombre'    => 'required',
            'usuario_email'     => 'required|unique:users|email',
            'password'          => 'required|confirmed',
        ],[
            'usuario_email.unique:users' => 'El correo ya existe',
        ]);

        $usuario = new User;
        $usuario->usuario_nombre = $request->usuario_nombre;
        $usuario->usuario_email = $request->usuario_email;
        $usuario->password = Hash::make($request->password);
        $usuario->save();
        
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show( Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
