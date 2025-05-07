<?php
    namespace App\Models;
    use CodeIgniter\Model;

    class ModeloRutas extends Model {

    protected $table      = 'rutas';
    protected $primaryKey = 'id_ruta';

    protected $useAutoIncrement = true;

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['num_serie', 'origen', 'destino', 'hora_salida', 'hora_llegada', 'tarifa', 'fecha'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Obtener todas ciudades de origen
    public function ciudadesOrg() {
        $ciudadesOrg = $this
            ->select('id_ruta, origen')
            ->groupBy('origen')
            ->findAll();
        return $ciudadesOrg;
    }

    // Obtener todas ciudades de destino
    public function ciudadesDes() {
        $ciudadesDes = $this->select('id_ruta, destino')
                    ->groupBy('destino')
                    ->findAll();
        return $ciudadesDes;
    }

    // Obtener destinos disponibles dependiendo del origen seleccionado
    public function destinosPorOrigen($origen) {
        return $this->select('destino')
                    ->where('origen', $origen)
                    ->groupBy('destino')
                    ->findAll();
    }

    // Obtener datos de rutas con datos seleccionados
    public function datosRutas($fechaSeleccionada, $ciudadOrigin = null, $ciudadDestino = null) {
        $builder = $this->where('hora_salida >=', $fechaSeleccionada)->orderBy('hora_salida', 'ASC');
    
        if ($ciudadOrigin) {
            $builder->where('origen', $ciudadOrigin);
        }
    
        if ($ciudadDestino) {
            $builder->where('destino', $ciudadDestino);
        }
    
        return $builder->findAll();
    }
    
    // Obtener datos de tarifas segund ciudad origen
    public function datosTarifas() {
        $datosTarifas = $this
            ->distinct()
            ->select('origen, destino, tarifa')
            ->orderBy('origen', 'ASC')
            ->findAll();
        return $datosTarifas;
    }


    // Función que obtiene la num_serie del tren de la ruta pasada en el param
    public function num_serieRuta($id_ruta) {
        $num_serie = $this
            ->select('num_serie')
            ->where('id_ruta', $id_ruta)
            ->first();
        return $num_serie->num_serie;
    }


    // Función que obtiene datos de una ruta pasandole id_ruta
    public function dameDatosRuta($id_ruta) {
        $datosRuta = $this
            ->where('id_ruta', $id_ruta)
            ->first();
        return $datosRuta;
    }


    // Función que devuelve si un tren y esta en uso en viajes futuros 
    public function trenEnUso($num_serie) {
        $count = $this
            ->where('num_serie', $num_serie)
            ->where('hora_salida >=', date('Y-m-d'))
            ->countAllResults();
        return $count > 0;
    }

    // Función que comprueba si un tren ha sido usado en el pasado y no tiene ningún registro en hora_salida >= hora_salida actual
    public function trenUsadoPasado($num_serie) {
        $countPasado = $this
            ->where('num_serie', $num_serie)
            ->where('hora_salida <', date('Y-m-d'))
            ->countAllResults();
        return $countPasado > 0 && !$this->trenEnUso($num_serie);
    }


    // Función que obtiene las rutas que tiene un tren pasandole su num_serie
    public function dameRutasTren($num_serie) {
        $rutas = $this
                ->where('num_serie', $num_serie)
                ->findAll();
        return !empty($rutas) ? $rutas : null;
    }


    // Función que devuelve todas las rutas que hay en BD
    public function todasRutas() {
        return $this->findAll();
    }


    // Función que obtiene todas las distintas ciudades 'origin y destino'
    public function todasCiudades() {
        $ciudades = $this
        ->select('origen, destino')
        ->distinct()
        ->groupBy('origen, destino')
        ->findAll();
        return $ciudades;
    }


    // Función que devuelve datos de rutas seguna los filtros pasados
    public function datosRutasFiltrados($num_serie, $ciudad, $hSalida, $hLlegada, $tarifaMin, $tarifaMax) {
        $consulta = $this;
        if ($num_serie != '') {
            $consulta->where('num_serie', $num_serie);
        }
   
        if ($ciudad != '0') {
            $consulta->groupStart() // Agrupa las condiciones OR
                     ->where('origen', $ciudad)
                     ->orWhere('destino', $ciudad)
                     ->groupEnd();
        }
        
        if ($hSalida != '') {
            $consulta->where('hora_salida', $hSalida);
        }   

        if ($hLlegada != '') {
            $consulta->where('hora_llegada', $hLlegada);
        }   

        if ($tarifaMin != '') {
            $consulta->where('tarifa >=', $tarifaMin);
        }

        if ($tarifaMax != '') {
            $consulta->where('tarifa <=', $tarifaMax);
        }

        $datos = $consulta->findAll();
        return $datos;
    }   

    // Función que elimna todas las rutas que tiene la num_serie pasada en param
    public function eliminarRutasNum_serie($num_serie) {
        $this->where('num_serie', $num_serie)->delete();
    }


    // función que inserta a la BD una nueva ruta
    public function insertarRuta($num_serie, $ciudadOrigen, $ciudadDestino, $horaSalida, $horaLlegada, $tarifa) {
        $insertado = $this->insert([
            'num_serie' => $num_serie,
            'origen' => $ciudadOrigen,
            'destino' => $ciudadDestino,
            'hora_salida' => $horaSalida,
            'hora_llegada' => $horaLlegada,
            'tarifa' => $tarifa
            ]);
        
        return $insertado;
    }


    // Función que elimina una ruta pasandole su id
    public function eliminarRuta($id) {
        return $this->where('id_ruta', $id)->delete();
    }

    // Función que actualiza una ruta pasandole los nuevos valores por param y su id   
    public function actualizarRuta($id_ruta, $num_serie, $ciudad_origen, $ciudad_destino, $hora_salida, $hora_llegada, $tarifa) {
        $data = [
            'num_serie' => $num_serie,
            'origen' => $ciudad_origen,
            'destino' => $ciudad_destino,
            'hora_salida' => $hora_salida,
            'hora_llegada' => $hora_llegada,
            'tarifa' => $tarifa
        ];
    
        // Usa el método update() de CodeIgniter
        return $this->update($id_ruta, $data);
    }
    


    // Función que conprueba si hay alguna ruta con una num_serie y datos hora_salida y hora igual in BD
    public function comprobarNum_serieExiste($num_serie, $horaSalida){
        $count = $this->where('num_serie', $num_serie)
                ->where('hora_salida', $horaSalida)
            ->countAllResults();
        return $count == 1;

    }

    
    public function destinosPorOrigen2($origen) {
        $builder = $this->db->table('rutas');
        $builder->select('destino');
        $builder->where('origen', $origen);
        $builder->groupBy('destino');
        $query = $builder->get();
    
        $destinos = [];
        foreach ($query->getResult() as $row) {
            $destinos[] = $row->destino;
        }
        return $destinos;
    }
    
    public function rutasPosteriores($origen, $destino, $fecha){
    return $this->where('origen', $origen)
                ->where('destino', $destino)
                ->where('hora_salida >', $fecha)
                ->orderBy('hora_salida', 'ASC')
                ->findAll();
    }

    public function getPrecioRuta($idRuta)
    {
        $ruta = $this->find($idRuta);
        return $ruta ? $ruta->tarifa : null;
    }

    public function datosRutas2($fecha, $origen, $destino, $fechaHoraMinima = null)
    {
        $builder = $this->where('origen', $origen)
                        ->where('destino', $destino);
    
        if ($fechaHoraMinima !== null) {
            // Filtrar por hora_salida >= fechaHoraMinima (fecha y hora)
            $builder->where('hora_salida >=', $fechaHoraMinima);
        } else {
            // Si no hay fechaHoraMinima, filtrar solo por fecha (sin hora)
            $builder->where('DATE(hora_salida)', $fecha);
        }
    
        return $builder->orderBy('hora_salida', 'ASC')->findAll();
    }
}