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

}
