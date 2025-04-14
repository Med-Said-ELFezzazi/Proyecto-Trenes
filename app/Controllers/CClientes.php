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
            if (session()->get('dniCliente') == null) {
                return redirect()->to(site_url('autenticacion'));
            } else {
                $dniCliente = session()->get("dniCliente");     // dni de la sesión
                $clienteObj = $this->modeloClientes->dameCliente($dniCliente);
                
                // Comprueba si haya dado al btotón de modificar
                if ($this->request->getPost("submitModificar")) {
                    $newNom = $this->request->getPost("modNom");
                    $newEmail = trim($this->request->getPost("modEmail"));
                    $newTele = $this->request->getPost("modTele");
                    $newPwd = $this->request->getPost("modPwd");

                    $msgErr = '';
                    // Validaciones de nuevos datos
                    if ((substr($newTele, 0, 1) != '6' && substr($newTele, 0, 1) != '9') || strlen($newTele) > 9) {
                        $msgErr .= "Número de telefono inválido!<br>";
                    }
                    if (strlen($newPwd) != 0 && strlen($newPwd) < 8) {
                        $msgErr .= "Contraseña débil. Debe tener exactamente 8 caracteres!<br>";
                    }
                    // Validar el correo
                    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                        $msgErr .= "El correo no es valido!<br>";
                    }
                    // Ya existe un correo igual perteneciendo a un cliente en la BD
                    if ($this->modeloClientes->emailExiste($newEmail) && $newEmail != $clienteObj->email) {
                        $msgErr .= 'El correo electrónico ya está registrado!<br>';
                    }

                    // Si hay errores, redirige con los mensajes
                    if (!empty($msgErr)) {
                        return redirect()->to(site_url('modificarCliente'))->with('msgErr', $msgErr);
                    }

                    // Actualizar solo si no hubo errores
                    $actualizado = $this->modeloClientes->actualizarCliente($dniCliente, $newNom, $newEmail, $newTele, $newPwd);

                    $msg = $actualizado ? "Datos modificados correctamente." : "¡Error al modificar los datos!";
                    return redirect()->to(site_url('modificarCliente'))->with('msg', $msg);
                }
        
                // Recupera el mensaje si existe
                $msg = session()->getFlashdata('msg') ?? "";
                $msgErr = session()->getFlashdata('msgErr') ?? "";
        
                return view("v_modificarCliente", [
                    'clienteObj' => $clienteObj,
                    'msg' => $msg,
                    'msgErr' => $msgErr
                ]);
            }
        }
        

    }
?>