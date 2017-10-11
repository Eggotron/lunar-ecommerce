<?php

namespace Lunar\Http\Controllers\Admin;

use Session;
use Auth;
use Illuminate\Http\Request;
use Lunar\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboard()
	{

        $clientes = User::count();
        $clientes_nuevos = User::where('created_at', '>=', Carbon::now()->subWeek())->count();
        $crm = Matriz::count();
        $proyectos = Proyecto::count();

		return view('back.dashboard')->with('clientes', $clientes)->with('clientes_nuevos', $clientes_nuevos)->with('crm', $crm)->with('proyectos', $proyectos);
	}

    public function profile()
    {
        $admin = Auth::guard('admin')->user();

        $tareas = Tarea::where('admin_id' , $admin->id )->get();

        return view('back.auth.profile')->with('admin', $admin)->with('tareas', $tareas);
    }

    public function editProfile($id)
    {
        $admin = Auth::guard('admin')->user();

        return view('back.auth.edit')->with('admin', $admin);
    }

    public function updateProfile(Request $request, $id)
    {
        //Validar
        $this -> validate($request, array(
            'name' => 'required|max:255',
        ));

        // Guardar datos en la base de datos
        $admin = Auth::guard('admin')->user();

        $admin->name = $request->name;
        $admin->puesto = $request->puesto;
        $admin->departamento = $request->departamento;
        $admin->correo_2 = $request->correo_2;
        $admin->tel_cel = $request->tel_cel;
        $admin->tel_fijo = $request->tel_fijo;

        $admin->save();

        Session::flash('exito', 'Tu perfil ha sido editado exitosamente.');

        return redirect()->route('admin.perfil');
    }
}
