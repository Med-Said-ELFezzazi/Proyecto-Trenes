<?php

namespace App\Controllers;

use App\Models\ModeloAverias;
// use App\Models\ModeloBuses;
// use stdClass;

class CAverias extends BaseController {

    // protected $modeloBuses;
    protected $modeloAverias;

    public function __construct()
    {
        // $this->modeloBuses = new ModeloBuses();
        $this->modeloAverias = new ModeloAverias();
    }


    public function gestionAverias() {
        $datosAverias = $this->modeloAverias->datosAverias();
        
        return view('v_home', ['datosAverias' => $datosAverias]);
    }

}
