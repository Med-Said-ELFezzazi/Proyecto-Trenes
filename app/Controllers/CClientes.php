<?php
    namespace App\Controllers;

    use App\Models\ModeloClientes;
    use App\Models\ModeloRutas;
    use stdClass;

    class CClientes extends BaseController {

        protected $modeloClientes;
        protected $modeloRutas;

        public function __construct() {
            $this->modeloClientes = new ModeloClientes();
            $this->modeloRutas = new ModeloRutas();
        }


        // Función que modifica los datos del cliente logeado
        public function modificarCliente() {
            // Compruebo se haya logeado el cliente o si no al login 'extra precaucion'
            if (session()->get('dniCliente') == null) {
                return redirect()->to(site_url('autenticacion'));
            } else {
                $dniCliente = session()->get("dniCliente"); // dni de la sesión
                $clienteObj = $this->modeloClientes->dameCliente($dniCliente);
                $msg = "";      // Msg de confirmacio/error de modificación
                // Comprueba si haya dado al btotón de modificar
                if ($this->request->getPost("submitModificar")) {
                    // Obtener los datos de los nuevos campos
                    $newNom = $this->request->getPost("modNom");
                    $newEmail = $this->request->getPost("modEmail");
                    // quitar los espacios al principio y al final del email
                    $newEmail = trim($newEmail);
                    $newTele = $this->request->getPost("modTele");
                    $newPwd = $this->request->getPost("modPwd");
                    // Hace la actualización en la BD
                    $actualizado = $this->modeloClientes->actualizarCliente($dniCliente ,$newNom, $newEmail, $newTele, $newPwd);
                    $msg .= $actualizado ? "Datos modificados correctamente" : "Error al modificar los datos!";
                }
                // Siempre es obligatorio pasar datos dentro un array asociativo
                return view("v_modificarCliente", 
                            ['clienteObj' => $clienteObj,
                            'msg' => $msg]);
            }
        }

    }
?>