<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
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
        $roles = Role::all();
        return view('login.registro',compact('roles'));
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
            'role_usuario'      => 'required',
            'password'          => 'required|confirmed',
        ],[
            'usuario_email.unique:users' => 'El correo ya existe',
            'usuario_nombre.required'    => 'Agregue un nombre de usuario',
            'usuario_email.required'     => 'Agregue un email válido',
            'password.required'          => 'Agregue una contraseña',
            'password.confirmed'         => 'Las contraseñas no coinciden',

        ]);

        $usuario = new User;
        $usuario->usuario_nombre = $request->usuario_nombre;
        $usuario->usuario_email = $request->usuario_email;
        $usuario->role_id = $request->role_usuario;
        $usuario->password = Hash::make($request->password);
        $usuario->save();
        return redirect('/');
    
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

    public function adminUser()
    {
        $roles = Role::all();
        $usuarios = User::all();
        return view('login.administrar',compact('roles','usuarios'));
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles = Role::all();
        $usuarios = User::all();
        $usuRol = User::join('roles','roles.id','=','users.role_id')
                            ->select('users.id as identificadorUsuario','roles.id as identificadorRol','users.usuario_nombre',
                                    'users.usuario_email','roles.descripcion_rol','users.usuario_status')
                            ->where('users.id',$id)
                            ->first();
        
        return view('login.administrar',compact('roles','usuarios','usuRol'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        $request->validate([
            'usuario_nombre'    => 'required',
            'usuario_email'     => 'required|email',
            'role_usuario'      => 'required',
            'password'          => 'confirmed',
            'usuario_status'    => 'required' 
        ],[
            'usuario_email.unique:users' => 'El correo ya existe',
            'usuario_nombre.required'    => 'Agregue un nombre de usuario',
            'usuario_email.required'     => 'Agregue un email válido',
            'password.confirmed'         => 'Las contraseñas no coinciden',
            'usuario_status.required'    => 'Seleccione el status del suaurio',

        ]);
        $fechaInsert = now();

        $email = User::where('usuario_email',$request->usuario_email)->get();

        if($email->count() > 1){
            if(is_null($request->password)){
                User::where('id',$id)
                    ->update([
                    'usuario_nombre' => $request->usuario_nombre,                                                
                    'role_id' => $request->role_usuario,
                    'usuario_status' => $request->usuario_status,
                    'updated_at' => $fechaInsert
                ]);
            }else{
                User::where('id',$id)
                    ->update([
                    'usuario_nombre' => $request->usuario_nombre,                                                
                    'role_id' => $request->role_usuario,
                    'password' => Hash::make($request->password),
                    'usuario_status' => $request->usuario_status,
                    'updated_at' => $fechaInsert
                ]);
            }
        }else{
            if(is_null($request->password)){
                User::where('id',$id)
                ->update([
                    'usuario_nombre' => $request->usuario_nombre,                                                
                    'usuario_email' =>$request->usuario_email,
                    'role_id' => $request->role_usuario,
                    'usuario_status' => $request->usuario_status,
                    'updated_at' => $fechaInsert
                ]);
            }else{
                User::where('id',$id)
                ->update([
                    'usuario_nombre' => $request->usuario_nombre,                                                
                    'usuario_email' =>$request->usuario_email,
                    'role_id' => $request->role_usuario,
                    'password' => Hash::make($request->password),
                    'usuario_status' => $request->usuario_status,
                    'updated_at' => $fechaInsert
                ]);
            }
           
        }

        return redirect()->route('index');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('usuarios.administrar');
    }
}
