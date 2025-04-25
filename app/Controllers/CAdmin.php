<?php

namespace App\Controllers;

use App\Models\ModeloTrenes;
use App\Models\ModeloRutas;

class CAdmin extends BaseController {

    protected $modeloTrenes;
    protected $modeloRutas;

    public function __construct()
    {
        $this->modeloTrenes = new ModeloTrenes();
        $this->modeloRutas = new ModeloRutas();
    }


    public function index()
    {
        return view('v_home');
    }

    public function gestionRutas()
    {
        $hoy = date('Y-m-d');
        $rutas = $this->modeloRutas->where('fecha >=', $hoy)->findAll();
        return view('v_gestionRutas', ['rutas' => $rutas]);
    }

    public function modificarRuta($id = null)
    {
        $ruta = $this->modeloRutas->find($id);

        if (!$ruta) {
            session()->setFlashdata('error', 'La ruta no existe.');
            return redirect()->to('/admin/rutas');
        }

        if ($this->request->getPost("submitModificarRutas")) {
            $data = $this->request->getPost();

            if ($this->modeloRutas->update($id, $data)) {
                session()->setFlashdata('success', 'La ruta se ha modificado correctamente.');
            } else {
                session()->setFlashdata('error', 'Hubo un problema al modificar la ruta. Intenta nuevamente.');
            }
    
            return redirect()->to('/admin/rutas');
        }
    
        return view('v_modificarRutas', ['ruta' => $ruta]);
    }

}
