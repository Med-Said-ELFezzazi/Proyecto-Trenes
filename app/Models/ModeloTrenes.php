<?php
    namespace App\Models;
    use CodeIgniter\Model;

    class ModeloTrenes extends Model {

    protected $table      = 'trenes';
    protected $primaryKey = 'num_serie';

    protected $useAutoIncrement = false;

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['capacidad', 'modelo', 'bagones', 'imagen'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Función que obtiene la capacidad maxima de un tren
    public function capacidadTren($num_serie) {
        $capacidad = $this
            ->select('capacidad')
            ->where('num_serie', $num_serie)
            ->first();
        return $capacidad ? $capacidad->capacidad : null;
    }

    
    // Función que obtiene todos los datos de trenes en formato obj
    public function datosTrenes() {
        return $this->findAll();
    }
    

    // Función que inserta un nuevo tren
    public function insertarTren($num_serie, $capacidad, $modelo, $bagones, $img) {
        $datos = [
            'num_serie' => trim(strtoupper($num_serie)),
            'capacidad' => $capacidad,
            'modelo' => $modelo,
            'bagones' => $bagones,
            'imagen' => $img
        ];

        if ($this->insert($datos)) {
            return true;
        } else {
            return false;
        }
    }

    // Función que elimina un Tren pasandole el num_serie
    public function eliminarTren($num_serie) {
        $eliminado = $this->where('num_serie', $num_serie)->delete();
        return $eliminado;
    }

    // Función que devuelve datos de un tren pasandole su num_serie
    public function dameDatosTren($num_serie) {
        $tren = $this->where('num_serie', $num_serie)->first();
        return $tren;
    }
    

    // Función que modifica los datos de un tren
    public function actualizarTren($num_serie, $capacidad, $modelo, $bagones, $imagen = null) {
        // Datos básicos que siempre se actualizan
        $datos = [
            'capacidad' => $capacidad,
            'modelo' => $modelo,
            'bagones' => $bagones
        ];
        
        if (!is_null($imagen)) {
            $datos['imagen'] = $imagen;
        }

        return $this->update($num_serie, $datos);
    }



}