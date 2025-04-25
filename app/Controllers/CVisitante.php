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
        
        // Obtener datos de rutas
        $fechaSeleccionada = $this->request->getPost('fecha') ?? date('Y-m-d');
        $ciudadOrgSel = $this->request->getPost('origenSel') ?? '';
        $ciudadDesSel = $this->request->getPost('destinoSel') ?? '';

        $destinosPorOrigen = [];
        $datosRutas = [];
        $msgError = '';

        if (!empty($ciudadOrgSel) && $ciudadOrgSel !== '0'){
            $destinosPorOrigen = $this->modeloRutas->destinosPorOrigen($ciudadOrgSel);
        }

        if ($this->request->getPost('consultar')) {
            if ($ciudadOrgSel == '0' || $ciudadOrgSel == '') {
                $msgError .= "Seleccione ciudad de origen!<br>";
            }
            if ($ciudadDesSel == '0' || $ciudadDesSel == '') {
                $msgError .= "Seleccione ciudad de destino!<br>";
            }
            if (empty($msgError)){
                $datosRutas = $this->modeloRutas->datosRutas($fechaSeleccionada, $ciudadOrgSel, $ciudadDesSel);
            }
        }

        return view('v_visitante', [
            'ciudadesOrg' => $ciudadesOrg,
            'datosRutas' => $datosRutas,
            'destinosPorOrigen' => $destinosPorOrigen,
            'msgError' => $msgError,
            'fechaSeleccionada' => $fechaSeleccionada,
            'ciudadOrgSel' => $ciudadOrgSel,
            'ciudadDesSel' => $ciudadDesSel
        ]);
    }


    public function tarifas() {
        $datosTarifas = $this->modeloRutas->datosTarifas();
        return view('v_visitante', ['datosTarifas' => $datosTarifas]);
    }

}
