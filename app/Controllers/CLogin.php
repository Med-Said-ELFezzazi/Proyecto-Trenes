<?php
    namespace App\Controllers;

    use App\Models\ModeloClientes;
    use stdClass;

    class CLogin extends BaseController {

        protected $modeloClientes;

        public function __construct() {
            $this->modeloClientes = new ModeloClientes();
        }


        // Función que compruebe si un DNI esta bien escrito o no (de momento va a comprobar solo si tiene 8 nums y 1 letra)
        public function dniValido($dni) {
            if (strlen($dni) != 9) {
                return false;
            }
            // comprobar si tiene 8 nums y 1 letra
            if (!ctype_digit(substr($dni, 0, 8)) || !ctype_alpha(substr($dni, 8))) {
                return false;
            }
            return true;
        }

        // Función que comprueba si los datos de registro estan validos
        public function datosRegistroValidos($dni, $nombre, $email, $tele, $pwd) {
            // Comprobar si haya insertado todos los datos
            if (empty($dni) || empty($nombre) || empty($email) || empty($tele) || empty($pwd)) {
                return "Deberías rellenar todos los datos!";
            }
            // DNI
            if (!$this->dniValido($dni)) {
                return "DNI inválido! \t 'Tiene que tener 8 Números y 1 Letra'";
            }
            // Tele
            if (strlen($tele) > 13) {    // (de momento compruebo si la longitud es correcta o no)
                return "Número de telefono inválido!";
            }
            // Password
            if (strlen($pwd) < 8) {
                return "Contraseña debil! \t 'Tiene que tener exactamente 8 characteres'";
            }
            return "bien";
        }


        public function index() {
            // Al click login
            if ($this->request->getPost("submitLogin")) {
                $email = $this->request->getPost("loginEmail");
                $pwd = $this->request->getPost("loginPassword");
                // Caso de credenciales admin
                if ($email == 'admin@admin.com' && $pwd == 'admin') {
                    // Guardar en la session
                    $this->session->set('admin', 'administrador');
                    // Llevar a la vista admin
                    return redirect()->to(site_url('admin/home'));
                }
                // Primero compruebo si haya introducido algo en los campos
                if (empty($email) || empty($pwd)) {
                    // Envio el msg de error
                    $this->session->setFlashdata([
                        'error' => 'Debes introducir ambos campos!',
                        'email' => $email,                          // Guarda temporalmente los valores
                        'pwd' => $pwd
                    ]);
                    return redirect()->to(site_url('autenticacion'));
                } else {
                    // Compruebo si el cliente existe
                    $dniCliente = $this->modeloClientes->autenticacion($email, $pwd);
                    if ($dniCliente === null) {
                        // Envio el msg de error
                        $this->session->setFlashdata(['error' => 'Credenciales incorrectas!']);
                        return redirect()->to(site_url('autenticacion'));
                    } else {
                        // Si el cliente existe, guardo su DNI en la session
                        $this->session->set('dniCliente', $dniCliente);
                        // Cargo los nav y side bar con datos y cambio la vista principal a una de bienvenida
                        $datosCli = $this->modeloClientes->dameCliente($dniCliente);
                        $this->session->set('cliente', $datosCli);  // Guardo datos del cliente en la session
                        return redirect()->to(site_url('autenticacion'));
                    }
                }          
            }

            // Al click registrar
            if ($this->request->getPost("submitRegistrar")) {
                // Obtener los datos inputs
                $dni = $this->request->getPost("registroDni");
                $nombre = $this->request->getPost("registroNom");   
                $email = $this->request->getPost("registroEmail");  // Esto el Input type email que se encarga de ello 
                $tele = $this->request->getPost("registroTele");
                $pwd = $this->request->getPost("registroPwd");

                $msgValidacion = $this->datosRegistroValidos($dni, $nombre, $email, $tele, $pwd);
                if ($msgValidacion !== "bien") {
                    // Envio el msg de error
                    $this->session->setFlashdata([
                        'error' => $msgValidacion,
                        'dni' => $dni,
                        'nombre' => $nombre,
                        'email' => $email,
                        'tele' => $tele,
                        'pwd' => $pwd
                    ]);
                    return redirect()->to(site_url('autenticacion'));
                } else {
                    // Comprobar si el cliente ya existe en la BD 'tiene que ser el dni y el correo unico'
                    if ($this->modeloClientes->autenticacion($email, $pwd) != null || $this->modeloClientes->dniExiste($dni)) {
                        // Envio el msg de error
                        $this->session->setFlashdata(['error' => 'Cliente ya existe!']);
                        return redirect()->to(site_url('autenticacion'));
                    } else {
                        // Hacer el registro en la BD
                        $registro = $this->modeloClientes->agregarCliente($dni, $nombre, $email, $tele, $pwd);
                        if (!$registro) {
                            // Envio el msg de error
                            $this->session->setFlashdata(['error' => 'Error al registrar el nuevo cliente!']);
                            return redirect()->to(site_url('autenticacion'));
                        } else {
                            // Envio el msg de confirmación
                            $this->session->setFlashdata(['confirmacion' => 'El cliente '.$nombre.' ha sido registrado correctamente']);
                            return redirect()->to(site_url('autenticacion'));
                        }
                    }
                }
            }
            return view("v_login");
        }


        public function cargarHome() {
            // Comprobar si el cliente esta autenticado
            if (!session()->get('dniCliente')) {
                return redirect()->to(site_url('autenticacion'));
            }
            return view("v_home");
        }


        public function cerrarSession() {
            // Eliminar la variable de session 'cliente/admin'
            session()->remove('dniCliente');
            session()->remove('admin');
            // Redirigir a la página de autenticación
            return redirect()->to(site_url('autenticacion'));
        }

        
    }


?>