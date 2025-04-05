<?php
namespace App\Controllers;

use App\Models\ModeloRutas;

class CVisitante extends BaseController {

    protected $modeloRutas;

    public function __construct() {
        $this->modeloRutas = new ModeloRutas();
    }

    public function modoVisitante() {
        session()->remove('dniCliente');
        return view("v_visitante");
    }

    public function lineasHorarios() {
        $ciudadesOrg = $this->modeloRutas->ciudadesOrg();
        $ciudadesDes = $this->modeloRutas->ciudadesDes();
        // Obtener datos de rutas
        $fechaSeleccionada = $_POST['fecha'] ?? date('Y-m-d');
        $ciudadOrgSel = $_POST['origenSel'] ?? '';
        $ciudadDesSel = $_POST['destinoSel'] ?? '';
        $datosRutas = $this->modeloRutas->datosRutas($fechaSeleccionada, $ciudadOrgSel, $ciudadDesSel);
        return view('v_visitante', [
            'ciudadesOrg' => $ciudadesOrg,
            'ciudadesDes' => $ciudadesDes,
            'datosRutas' => $datosRutas
        ]);
    }


    public function tarifas() {
        $datosTarifas = $this->modeloRutas->datosTarifas();
        return view('v_visitante', ['datosTarifas' => $datosTarifas]);
    }
}
