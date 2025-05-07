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

    // Mostrar todas las rutas disponibles
    public function gestionRutas()
    {
        $filtros = $this->request->getPost();

        $query = $this->modeloRutas;

        if (!empty($filtros['origen'])) {
            $query->where('origen', $filtros['origen']);
        }

        if (!empty($filtros['destino'])) {
            $query->where('destino', $filtros['destino']);
        }

        if (!empty($filtros['fecha'])) {
            $query->where('fecha', $filtros['fecha']);
        }

        if (!empty($filtros['num_serie'])) {
            $query->where('num_serie', $filtros['num_serie']);
        }

        if (!empty($filtros['precio_min'])) {
            $query->where('tarifa >=', $filtros['precio_min']);
        }
    
        if (!empty($filtros['precio_max'])) {
            $query->where('tarifa <=', $filtros['precio_max']);
        }

        $rutas = $query->findAll();

        $precioMinimo = $this->modeloRutas->selectMin('tarifa')->get()->getRow()->tarifa ?? 0;
        $precioMaximo = $this->modeloRutas->selectMax('tarifa')->get()->getRow()->tarifa ?? 0;

        return view('v_gestionRutas', [
            'rutas' => $rutas,
            'precioMinimo' => $precioMinimo,
            'precioMaximo' => $precioMaximo
        ]);
    }

    // Modificar una ruta
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

    // Eliminar una ruta
    public function eliminarRuta($id = null) {
        if ($this->modeloRutas->delete($id)){
            session()->setFlashdata('success', 'La ruta se eliminó correctamente.');
        } else {
            session()->setFlashdata('error', 'No se pudo eliminar la ruta. Intenta nuevamente.');
        }
        return redirect()->to('/admin/rutas');
    }

    // Añadir una ruta nueva
    public function aniadirRuta() {
        if ($this->request->getPost("submitNuevaRuta")) {
            $reglas = [
                'origen' => 'permit_empty',
                'destino' => 'permit_empty',
                'nuevo_origen' => 'permit_empty|alpha_space|min_length[2]|max_length[50]',
                'nuevo_destino' => 'permit_empty|alpha_space|min_length[2]|max_length[50]',
                'hora_salida' => 'required',
                'hora_llegada' => 'required',
                'tarifa' => 'required|decimal',
                'fecha' => 'required',
            ];
    
            // Valida los datos
            if (!$this->validate($reglas)) {
                $opcionesNumSerie = $this->obtenerOpcionesNumSerie();
                return view('v_aniadirRuta', [
                    'validation' => $this->validator,
                    'opcionesNumSerie' => $opcionesNumSerie,
                    'opcionesCiudades' => $this->obtenerCiudades()
                ]);
            }
    
            // Procesar origen y destino
            $origen = $this->request->getPost('nuevo_origen') ?: $this->request->getPost('origen');
            $destino = $this->request->getPost('nuevo_destino') ?: $this->request->getPost('destino');
    
            if (!$origen || !$destino) {
                $validationErrors = [
                    'origen' => 'Debe seleccionar o ingresar un origen válido.',
                    'destino' => 'Debe seleccionar o ingresar un destino válido.'
                ];
                return view('v_aniadirRuta', [
                    'validation' => $validationErrors,
                    'opcionesNumSerie' => $this->obtenerOpcionesNumSerie(),
                    'opcionesCiudades' => $this->obtenerCiudades()
                ]);
            }
    
            // Prepara los datos para guardar
            $data = $this->request->getPost();
            $data['origen'] = $origen;
            $data['destino'] = $destino;
    
            // Validaciones adicionales
            $hoy = date('Y-m-d');
            if ($data['fecha'] <= $hoy) {
                return view('v_aniadirRuta', [
                    'validation' => ['fecha' => 'La fecha debe ser posterior a hoy.'],
                    'opcionesNumSerie' => $this->obtenerOpcionesNumSerie(),
                    'opcionesCiudades' => $this->obtenerCiudades()
                ]);
            }
    
            if (substr($data['hora_salida'], 0, 10) !== $data['fecha']) {
                return view('v_aniadirRuta', [
                    'validation' => ['hora_salida' => 'La hora de salida debe coincidir con la fecha.'],
                    'opcionesNumSerie' => $this->obtenerOpcionesNumSerie(),
                    'opcionesCiudades' => $this->obtenerCiudades()
                ]);
            }
    
            // Inserta los datos
            if ($this->modeloRutas->insert($data)) {
                session()->setFlashdata('success', 'La nueva ruta se añadió correctamente.');
            } else {
                session()->setFlashdata('error', 'Hubo un problema al añadir la ruta.');
            }
    
            return redirect()->to('/admin/rutas');
        }
    
        return view('v_aniadirRuta', [
            'opcionesNumSerie' => $this->obtenerOpcionesNumSerie(),
            'opcionesCiudades' => $this->obtenerCiudades()
        ]);
    }

    // Sacar todos los numeros de serie de los trenes
    private function obtenerOpcionesNumSerie() {
        $trenes = $this->modeloTrenes->findAll();
        $opciones = ['' => 'Seleccionar Número de Serie'];
        foreach ($trenes as $tren) {
            $opciones[$tren->num_serie] = $tren->num_serie;
        }
        return $opciones;
    }

    private function obtenerCiudades() {
        $rutas = $this->modeloRutas->findAll();
        $ciudades = ['' => 'Seleccionar Ciudad'];
    
        foreach ($rutas as $ruta) {
            if (!empty($ruta->origen)) {
                $ciudades[$ruta->origen] = $ruta->origen;
            }
            if (!empty($ruta->destino)) {
                $ciudades[$ruta->destino] = $ruta->destino;
            }
        }
    
        return array_unique($ciudades);
    }

}
