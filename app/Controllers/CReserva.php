<?php
    namespace App\Controllers;

    use App\Models\ModeloReservas;
    use App\Models\ModeloRutas;
    use App\Models\ModeloTrenes;
    use App\Models\ModeloClientes;
    use Config\Services;

    class CReserva extends BaseController {

        protected $modeloReservas;
        protected $modeloRutas;
        protected $modeloTrenes;
        protected $modeloClientes;

        public function __construct() {
            $this->modeloReservas = new ModeloReservas();
            $this->modeloRutas = new ModeloRutas();
            $this->modeloTrenes = new ModeloTrenes();
            $this->modeloClientes = new ModeloClientes();
        }

        // Función que devuelve un array de todas las ciudades pasandole tipo 'origen/destino'
        private function obtenerCiudades($tipo) {
            $ciudades = ($tipo === 'origen') 
                ? $this->modeloRutas->ciudadesOrg()
                : $this->modeloRutas->ciudadesDes();
            
            $resultado = [];
            foreach ($ciudades as $ciudad) {
                $campo = ($tipo === 'origen') ? 'ciudad_origin' : 'ciudad_destino';
                $resultado[$ciudad->$campo] = $ciudad->$campo;
            }
            return $resultado;
        }
        

        // Función que pasa todas las ciudades 'origen/destino' para cargar en la v_reserva
        public function reservar() {
            // Pasar todas las distintas ciudades origen y destino
            $ciudadesOrg = $this->obtenerCiudades('origen');
            $ciudadesDes = $this->obtenerCiudades('destino');

            return view("v_home", [
            'ciudadesOrg' => $ciudadesOrg,
            'ciudadesDes' => $ciudadesDes,
            ]);
        }

        
        public function servicios() {
            // Obtener datos a buscar
            // $fechaIda = $_POST['fecha_ida'];
            // $fechaVuelta = $_POST['fecha_vuelta'];
            $fecha = $_POST['fecha_ida'];
            $origen = $_POST['ciudad_origen'];
            $destino = $_POST['ciudad_destino'];
            $Numbilletes = $_POST['Numbilletes'];            
            
            // Para rellenar los campos de origen y destino
            $ciudadesOrg = $this->obtenerCiudades('origen');
            $ciudadesDes = $this->obtenerCiudades('destino');
            // DAtos de rutas segun los filtros seleccionados
            $datosRuta = $this->modeloRutas->datosRutas($fecha, $origen, $destino);
            $servicios = [];

            foreach ($datosRuta as $datos) {
                $id_ruta = $datos->id_ruta;
        
                // Disponibilidad de plazas
                $num_serieTrenRuta = $this->modeloRutas->num_serieRuta($id_ruta);
                $capacidadMaxTren = $this->modeloTrenes->capacidadTren($num_serieTrenRuta);
                $cantidadReservas = $this->modeloReservas->numeroReservas($id_ruta);
        
                $hayPlazas = ($cantidadReservas + $Numbilletes <= $capacidadMaxTren);
        
                // Preparar datos a enviar en la variable servicios
                $servicios[] = [
                    'id_ruta' => $id_ruta,
                    'hora_salida' => $datos->hora_salida,
                    'hora_llegada' => $datos->hora_llegada,
                    'precio' => $datos->tarifa,
                    'plazas_libres' => $capacidadMaxTren - $cantidadReservas,
                    'hayPlazas' => $hayPlazas
                ];
            }

            return view("v_home", [
                'ciudadesOrg' => $ciudadesOrg,
                'ciudadesDes' => $ciudadesDes,
                'servicios' => $servicios
            ]);
        }
       

        // Función que envía un correo al cliente con los detalles de la compra
        public function enviarEmailCompra($emailCliente, $fechaIda, $horaSalidaIda, $origen, $destino, $arrTicketAsiento) {
            // Formatear la hora a H:i
            $horaSalidaIda = date('H:i', strtotime($horaSalidaIda));
        
            // Arrays para almacenar los valores de id_ticket y num_asiento
            $idTickets = [];
            $asientos = [];
        
            // Rellenar los arrays
            foreach ($arrTicketAsiento as $ticket) {
                $idTickets[] = $ticket['id_ticket'];
                $asientos[] = $ticket['num_asiento'];
            }
        
            // Convertir los arrays a cadenas separadas por coma
            $idTicketsStr = implode(', ', $idTickets);
            $asientosStr = implode(', ', $asientos);
        
            // Cuerpo del mensaje
            $cuerpo = "<h1 style='color: green;'>Compra realizada correctamente</h1>
                <p>Datos de la reserva:</p><br><br>
                <div style='border: 2px dashed black; width: 450px; padding: 10px;'>
                    <table border='0' style='border-collapse: collapse; width: 100%;'>
                    <tbody>
                        <tr>
                            <td align='center' colspan='100'><b><i>IDA</i></b></td>
                        </tr>
                        <tr>
                            <td>FECHA</td>
                            <td align='left'><b>{$fechaIda}</b></td>
                            <td>&nbsp;</td>
                            <td>HORA</td>
                            <td align='left'><b>{$horaSalidaIda}</b></td>
                        </tr>
                        <tr>
                            <td>SERVICIO</td>
                            <td align='left' colspan='100'><b>{$origen} - {$destino}</b></td>
                        </tr>
                        <tr>
                            <td>NUM.ASIENTO</td>
                            <td align='left'><b>{$asientosStr}</b></td>
                            <td>NUM.TICKET</td>
                            <td align='left'><b>{$idTicketsStr}</b></td>
                        </tr>
                    </tbody>
                    </table>
                </div>
                <br>
                <b><i>Muchas gracias por la compra ¡Buen viaje!</i></b>
            ";
        
            $emailService = Services::emailService();
            $resultado = $emailService->sendEmail(
                $emailCliente,
                'Confirmacion de compra',
                $cuerpo
            );
            return $resultado;
        }
        

        // Función que genera uno o varios números de asiento random
        public function generarAsientoRandom($id_ruta, $numBilletes) {
            // Obtener la capacidad del Tren
            $num_serieTrenRuta = $this->modeloRutas->num_serieRuta($id_ruta);
            $capacidadMaxTren = $this->modeloTrenes->capacidadTren($num_serieTrenRuta);
        
            // Obtener los asientos ya reservados
            $asientosReservados = $this->modeloReservas->asientosReservadosRuta($id_ruta); // Array de objetos
        
            $arrAsientosLibres = []; // Array para los asientos libres
        
            // Convertir los objetos de asientos reservados a un array simple de números
            $arrAsientosReservados = [];
            foreach ($asientosReservados as $asiento) {
                $arrAsientosReservados[] = $asiento->num_asiento;
            }
        
            // Generar asientos aleatorios
            for ($i = 1; $i <= $numBilletes; $i++) {
                do {
                    $asientoRandom = rand(1, $capacidadMaxTren);
                } while (in_array($asientoRandom, $arrAsientosReservados) || in_array($asientoRandom, $arrAsientosLibres));
        
                $arrAsientosLibres[] = $asientoRandom;
            }
        
            return $arrAsientosLibres;
        }
        

        // Función que registra la compra en la BD y manada correo al cliente
        public function realizarCompra() {
            // Comprobar si haya seleccionado un radio dishablitado o habiltado
            if (!isset($_POST['servicioSel'])) {
                // Vuelvo a la vista con mensaje de error
                $msgErrorLleno = "Lo sentimos, el tren está lleno! <br> Considera bajar la cantidad de billetes.";
                // PAsar ciudades origen y destino para q no da error
                $ciudadesOrg = $this->obtenerCiudades('origen');
                $ciudadesDes = $this->obtenerCiudades('destino');
                return view('v_home', ['ciudadesOrg' => $ciudadesOrg,
                                    'ciudadesDes' => $ciudadesDes,
                                    'msgErrorLleno' => $msgErrorLleno]);

            } else {    // Radio button habilitado
                // Obtener datos de la compra
                $id_ruta = $_POST['servicioSel'];

                $numBilletesSel = session()->get('numBilletes');
                $asiento = session()->get('numAsientoInsertado'); // NULL/Numero
                $arrAsientosRandom = [];

                if ($asiento == null) {     // Generar asientos random
                    // Generar asiento random
                    $arrAsientosRandom = $this->generarAsientoRandom($id_ruta, $numBilletesSel);
                } else {
                    $ciudadesOrg = $this->obtenerCiudades('origen');
                    $ciudadesDes = $this->obtenerCiudades('destino');
                    // Verificar si el asiento proporcionado ya está ocupado
                    if ($this->modeloReservas->asientoOcupado($id_ruta, $asiento)) {
                        // Mostrar mensaje de error si el asiento está ocupado
                        $msgErrorAsiento = "El asiento {$asiento} ya está ocupado. Por favor, selecciona otro o elige la opción de asignar asiento aleatorio";
                        return view('v_home', ['ciudadesOrg' => $ciudadesOrg,
                                                'ciudadesDes' => $ciudadesDes,
                                                'msgErrorAsiento' => $msgErrorAsiento]);
                    }

                    // Comprobar si el numero de asiento insertado es mayor que la capacidad del Tren
                    $num_serie = $this->modeloRutas->dameDatosRuta($id_ruta)->num_serie;
                    $capaTren = $this->modeloTrenes->capacidadTren($num_serie);

                    if ($asiento > $capaTren) {
                        return view('v_home', ['ciudadesOrg' => $ciudadesOrg,
                                            'ciudadesDes' => $ciudadesDes,
                                            'msgErrorAsiento' => 'Número de asiento mayor que la capacidad maxima del Tren!']);
                    } else {
                        // Si el asiento está disponible, meter el asiento insertado por el cliente en el arrayRandom
                        $arrAsientosRandom = [$asiento];
                    }
                }
                // Insertar la reserva en la BD
                $reservaGrabada = $this->modeloReservas->agregarReserva(
                    session()->get('dniCliente'), $id_ruta, $arrAsientosRandom);
    
                // Enviar correo al cliente 'methodo enviarcorreo
                $datosRuta = $this->modeloRutas->dameDatosRuta($id_ruta);
    
                $emailCliente = $this->modeloClientes->dameCliente(session()->get('dniCliente'))->email;
                $fechaIda = $datosRuta->fecha;
                $horaSalidaIda = $datosRuta->hora_salida;
                $origen = $datosRuta->ciudad_origin;
                $destino = $datosRuta->ciudad_destino;
                $arrNumTicket = $this->modeloReservas->dameIdTicket(session()->get('dniCliente'), $id_ruta, date('Y-m-d'));
                
                $emailEnviado = $this->enviarEmailCompra($emailCliente, $fechaIda, $horaSalidaIda, 
                                $origen, $destino,$arrNumTicket);
    
    
                return view('v_home', ['compraOk' => $reservaGrabada,
                            'emailOk' => $emailEnviado]);
            }
        }    


        // Cargar vista Opinion
        public function opinar() {
            $datos = [];

            // Obtener los datos necesarios desde la BD
            $dniCli = $this->session->get('dniCliente');

            $reservasSinOpin = $this->modeloReservas->reservasSinOpinionCli($dniCli);

            // Comprobar si el cliente no tiene ningúna reserva
            if (empty($reservasSinOpin)) {
                return view('v_home', ['datosOpinion' => 'nada',
                                        'msgNoReservas' => 'No tienes ningúna reserva transcurrida!']);
            }

            foreach ($reservasSinOpin as $reserva) {
                $ruta = $this->modeloRutas->dameDatosRuta($reserva->id_ruta);
                // Obtener datos solo de las rutas transcurridas
                if ($ruta->fecha < date('Y-m-d') || ($ruta->fecha == date('Y-m-d') && $ruta->hora_llegada < date('H:i:s'))) {
                    // Obtener la imagen del Tren que hizo la ruta
                    $imgTren = $this->modeloTrenes->dameDatosTren($ruta->num_serie);
                    
                    // Recoger los datos a enviar
                    $datos[] = ['id_ticket' => $reserva->id_ticket,
                            'id_ruta' => $reserva->id_ruta,
                            'cOrigen' => $ruta->ciudad_origin,
                            'cDestino' => $ruta->ciudad_destino,
                            'hLlegada' => $ruta->hora_llegada,
                            'imagen' => $imgTren->imagen
                            ];
                }
            }
            return view('v_home', ['datosOpinion' => $datos]);
        }

        
        // Insertar opinión
        public function insertarOpinion() {
            // Obtener las reservas sin opinion de BD 'para cargar la vista'
            $dniCli = $this->session->get('dniCliente');
            $reservasSinOpin = $this->modeloReservas->reservasSinOpinionCli($dniCli);
            $datos = [];

            // Comprobar si el cliente no tiene ningúna reserva
            if (empty($reservasSinOpin)) {
                return view('v_home', ['datosOpinion' => 'nada',
                                        'msgNoReservas' => 'No tienes ningúna reserva transcurrida!']);
            }

            foreach ($reservasSinOpin as $reserva) {
                $ruta = $this->modeloRutas->dameDatosRuta($reserva->id_ruta);
                // Obtener datos solo de las rutas transcurridas
                if ($ruta->fecha < date('Y-m-d') || ($ruta->fecha == date('Y-m-d') && $ruta->hora_llegada < date('H:i:s'))) {
                    // Obtener la imagen del Tren que hizo la ruta
                    $imgTren = $this->modeloTrenes->dameDatosTren($ruta->num_serie);
                    
                    // Recoger los datos a enviar
                    $datos[] = ['id_ticket' => $reserva->id_ticket,
                            'id_ruta' => $reserva->id_ruta,
                            'cOrigen' => $ruta->ciudad_origin,
                            'cDestino' => $ruta->ciudad_destino,
                            'hLlegada' => $ruta->hora_llegada,
                            'imagen' => $imgTren->imagen
                            ];
                }
            }

            // Obtener las reservas seleccionadas
            $msgErrOpin = '';
            if (!isset($_POST['reservasSel']) || empty($_POST['reservasSel'])) {
                $msgErrOpin = 'No has seleccionado ningúna reserva! <br>';
            }
            

            // Comprobar si haya introducido un texto
            $opinion = $_POST['opinion'];
            if ($opinion == '') {
                $msgErrOpin .= 'No has introducido tu opinión!';
            }

            if ($msgErrOpin != '') {
                return view('v_home', ['datosOpinion' => $datos,
                                    'msgErrOpin' => $msgErrOpin]);
            } else {
                // Insertar la opinión
                $insertados = $this->modeloReservas->insertarOpinion($_POST['reservasSel'], $opinion, $dniCli);
                if ($insertados) {
                    return view('v_home', ['datosOpinion' => $datos,
                                            'msgInfoOpi' => 'Gracias por tu opinión <br> Tu opinión ha sido guardado correctamente']);
                } else {
                    return view('v_home', ['datosOpinion' => $datos,
                                            'msgErrOpin' => 'ERROR de inserción en la BD!']);
                }
            }
        }

      
    } 


?>