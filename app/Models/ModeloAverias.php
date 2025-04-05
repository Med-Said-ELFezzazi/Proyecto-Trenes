<?php
    namespace App\Models;
    use CodeIgniter\Model;

    class ModeloAverias extends Model {

    protected $table      = 'averias';
    protected $primaryKey = 'id_averia';

    protected $useAutoIncrement = false;

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['matricula', 'descripcion', 'fecha', 'coste', 'reparada'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    
    // Función que obtiene todos los datos de averias
    public function datosAverias() {
        return $this->findAll();
    }
    

}