<?php
    namespace App\Models;
    use CodeIgniter\Model;

    class ModeloClientes extends Model {

    protected $table      = 'clientes';
    protected $primaryKey = 'dni';

    protected $useAutoIncrement = false;    // Es un dni

    protected $returnType     = 'object';   // Array de obj, como se le pasan o devuelve las filas de la tabla
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre', 'email', 'telefono', 'password'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;


    // Función que obtiene el dni del cliente pasandolo el email y pwd sino null
    public function autenticacion($email, $pwd) {
        $cliente = $this
            ->select('dni')
            ->where('email', $email)
            ->where('password', $pwd)
            ->first();
        return $cliente ? $cliente->dni : null;   // Devuelve el id, sino existe null
    }

    // Función que añade un cliente a la BD
    public function agregarCliente($dni, $nom, $email, $tele, $pwd) {
        return $this->insert([
                        'dni' => strtoupper($dni),
                        'nombre' => $nom,
                        'email' => $email,
                        'telefono' => $tele,
                        'password' => $pwd
                    ]);
    }

    // Función que verifica si un dni ya existe en la BD o no
    public function dniExiste($dni) {
        $cliente = $this
            ->select('dni')
            ->where('dni', $dni)
            ->first();
        return $cliente ? true : false;
    }


    // Función que devuelve datos de un cliente formato objeto pasandole su dni
    public function dameCliente($dni) {
        $cliente = $this
        ->select('nombre, email, telefono, password')
        ->where('dni', $dni)
        ->first();
        return $cliente;
    }


    // Función que actualiza datos de un cliente
    public function actualizarCliente($dni, $nom, $email, $tele, $pwd) {
        $datosActualizados = [];
        // Solo agregar al array los campos que no esten vacios
        if (!empty($nom)) {
            $datosActualizados['nombre'] = $nom;
        }
        if (!empty($email)) {
            $datosActualizados['email'] = $email;
        }
        if (!empty($tele)) {
            $datosActualizados['telefono'] = $tele;
        }
        if (!empty($pwd)) {
            $datosActualizados['password'] = $pwd;
        }
        // Verificar si hay campos para actualizar
        if (!empty($datosActualizados)) {
            $actualizado = $this
                ->where('dni', $dni)
                ->set($datosActualizados)
                ->update();
            return $actualizado;
        }
        // Si no hay datos para actualizar
        return false;
    }
    
}
?>