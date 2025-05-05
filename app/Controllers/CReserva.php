<?php
    namespace App\Controllers;

    use App\Models\ModeloReservas;
    use App\Models\ModeloRutas;
    use App\Models\ModeloTrenes;
    use App\Models\ModeloClientes;
    use Config\Services;
    use DateTime;

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
                $campo = ($tipo === 'origen') ? 'origen' : 'destino';
                $resultado[$ciudad->$campo] = $ciudad->$campo;
            }
            return $resultado;
        }
        

        // Función que pasa todas las ciudades 'origen/destino' para cargar en la v_reserva
        public function reservar() {

            $origenSeleccionado = $this->request->getPost('origen');
            // Pasar todas las distintas ciudades origen y destino
            $ciudadesOrg = $this->obtenerCiudades('origen');
            //$ciudadesDes = $this->obtenerCiudades('destino');

            if(!empty($origenSeleccionado)){
                // Llama al modelo para obtener los destinos posibles
                $ciudadesDes = $this->modeloRutas->destinosPorOrigen2($origenSeleccionado);
                $ciudadesDes = array_combine($ciudadesDes, $ciudadesDes);
                return view("v_home", [
                    'ciudadesOrg' => $ciudadesOrg,
                    'ciudadesDes' => $ciudadesDes,
                    'origenSeleccionado' => $origenSeleccionado
                ]);
            }


            return view("v_home", [
                'ciudadesOrg' => $ciudadesOrg,
                'origenSeleccionado' => $origenSeleccionado
                //'ciudadesDes' => $ciudadesDes,
            ]);
        }

        
        public function servicios()
        {
            // 1. Recoger TODOS los datos del POST
            $postData = [
                'fecha_ida' => $this->request->getPost('fecha_ida'),
                'origen' => $this->request->getPost('origen'),
                'destino' => $this->request->getPost('destino'),
                'Numbilletes' => $this->request->getPost('Numbilletes'),
                'fecha_vuelta' => $this->request->getPost('fecha_vuelta')
            ];

            $asientoAleatorio = $this->request->getPost('asientoAleatorio');
            if(!isset($asientoAleatorio)){
                session()->set('asientoAleatorio', false);
            }else{
                session()->set('asientoAleatorio', true);
            }

            $soloIda = $this->request->getPost('soloIda');

            $Numbilletes = $this->request->getPost('Numbilletes');
            session()->set('Numbilletes',$Numbilletes);
            $origenSeleccionado = $this->request->getPost('origen');
            $destino =  $this->request->getPost('destino');
            // **Nueva validación de ciudades**
            if (empty($origenSeleccionado) || empty($destino)) {
                session()->setFlashdata('error', 'Debes seleccionar una ciudad de origen y una ciudad de destino');
                return redirect()->back();
            }

            // 2. Guardar en sesión ANTES de validar
            session()->set('reserva_data', $postData);

            // 4. Procesar fecha de ida
            $fecha_ida = $this->formatearFecha($postData['fecha_ida']);

            $fechaHoraMinima = null;
            if ($fecha_ida === date('Y-m-d')) {
                $fechaHoraMinima = date('Y-m-d H:i:s'); // fecha y hora actual
            }

            $rutas_ida = $this->modeloRutas->datosRutas2($fecha_ida, $origenSeleccionado, $destino, $fechaHoraMinima);
            $servicios_ida = $this->dameServicios($rutas_ida, $Numbilletes);

            $servicios_vuelta = [];

            if(!isset($soloIda)){
                session()->set('soloIda', false);
                $fecha_vuelta = $this->request->getPost('fecha_vuelta');

                // Validación de fecha vuelta
                if(empty($fecha_vuelta)){
                    return redirect()->back()->withInput()->with('error', 'Debes seleccionar una fecha de vuelta');
                }

                // Conversión de formato fecha vuelta
                if(strpos($fecha_vuelta, '/') !== false){
                    $fechaObj = DateTime::createFromFormat('d/m/Y', $fecha_vuelta);
                    $fecha_vuelta = $fechaObj ? $fechaObj->format('Y-m-d') : $fecha_vuelta;
                }

                $rutas_vuelta = [];
                if (!empty($fecha_vuelta) && !empty($origenSeleccionado) && !empty($destino)) {
                    $rutas_vuelta = $this->modeloRutas->datosRutas2($fecha_vuelta, $destino, $origenSeleccionado);
                }

                $servicios_vuelta= $this->dameServicios($rutas_vuelta,$Numbilletes);
            }else{
                session()->set('soloIda', true);
            }

            // 7. Mostrar vista
            return view("v_servicios", [
                'ciudadOrg' => $origenSeleccionado,
                'ciudadDes' => $destino,
                'servicios_ida' => $servicios_ida,
                'servicios_vuelta' => $servicios_vuelta,
                'fecha_ida' => $fecha_ida,
                'fecha_vuelta' => $fecha_vuelta ?? null // Pasar null si no está definida
            ]);
        }

        private function formatearFecha($fecha)
        {
            if(strpos($fecha, '/') !== false){
                $fechaObj = DateTime::createFromFormat('d/m/Y', $fecha);
                return $fechaObj ? $fechaObj->format('Y-m-d') : date('Y-m-d');
            }
            return $fecha;
        }
    
    
        public function dameServicios($rutas, $Numbilletes){
    
            $servicios=[];
    
            foreach ($rutas as $datos) {
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
    
            return $servicios;
        }      
        
        // Función que envía un correo al cliente con los detalles de la compra
        public function enviarEmailCompra($emailCliente, $fechaIda, $horaSalidaIda, $origen, $destino, $arrTicketAsiento,$vuelta) {
            // Formatear la hora a H:i
            $horaSalidaIda = date('H:i', strtotime($horaSalidaIda));
        
            // Arrays para almacenar los valores de id_ticket y num_asiento
            $idTickets = [];
            $asientos = [];
            // Rellenar los arrays
            foreach ($arrTicketAsiento as $ticket) {
                $idTickets[] = $ticket[0]['id_ticket'];
                $asientos[] = $ticket[0]['num_asiento'];
            }
        
            // Convertir los arrays a cadenas separadas por coma
            $idTicketsStr = implode(', ', $idTickets);
            $asientosStr = implode(', ', $asientos);
            $fechaIdaFormateada = $fechaIda->format('d-m-Y');

            
            if($vuelta){
                $direccion="<tr>
                    <td align='center' colspan='100'><b><i>VUELTA</i></b></td>
                </tr>";
            
            }else{
                $direccion="<tr>
                    <td align='center' colspan='100'><b><i>IDA</i></b></td>
                </tr>";
            }
                        
        
            // Cuerpo del mensaje
            $cuerpo = "<h1 style='color: green;'>Compra realizada correctamente</h1>
                <p>Datos de la reserva:</p><br><br>
                <div style='border: 2px dashed black; width: 450px; padding: 10px;'>
                    <table border='0' style='border-collapse: collapse; width: 100%;'>
                    <tbody>
                        {$direccion}
                        <tr>
                            <td>FECHA</td>
                            <td align='left'><b>{$fechaIdaFormateada}</b></td>
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
            // Obtener el número de serie del tren asociado a la ruta
            $num_serieTrenRuta = $this->modeloRutas->num_serieRuta($id_ruta);
            // Obtener la capacidad total del tren
            $capacidadMaxTren = $this->modeloTrenes->capacidadTren($num_serieTrenRuta);
        
            // Obtener los asientos reservados (array de objetos)
            $asientosReservados = $this->modeloReservas->asientosReservadosRuta($id_ruta);
        
            // Convertir a array simple de números de asiento
            $arrAsientosReservados = [];
            foreach ($asientosReservados as $asientoObj) {
                $arrAsientosReservados[] = $asientoObj->num_asiento;
            }
        
            // Array para almacenar los asientos aleatorios seleccionados
            $arrAsientosAleatorios = [];
        
            // Generar exactamente $numBilletes asientos aleatorios y NO repetidos
            for ($i = 0; $i < $numBilletes; $i++) {
                do {
                    $asiento = rand(1, $capacidadMaxTren);
                } while (in_array($asiento, $arrAsientosReservados) || in_array($asiento, $arrAsientosAleatorios));
        
                $arrAsientosAleatorios[] = $asiento;
            }
        
            return $arrAsientosAleatorios;
        }
        
   
        public function elegirAsiento(){
            // Cargar datos de sesión
            $data = session()->get('reserva_data');

            // Validar sesión
            if (!$data) {
                return redirect()->to('/reserva')->with('error', 'La sesión ha expirado.');
            }

            // Obtener los ID de ruta seleccionados
            $idRutaIda = $this->request->getPost('servicioSel');
            $idRutaVuelta = $this->request->getPost('servicioVueltaSel');

            // Preparar los datos para pasar a la vista
            $data['idRutaIda'] = $idRutaIda;
            $data['idRutaVuelta'] = $idRutaVuelta;
            $data['numBilletes'] = $data['Numbilletes']; 
            $data['asientos_ida'] = $this->getDetallesAsientos($idRutaIda); 
            $data['asientos_vuelta'] = $idRutaVuelta ? $this->getDetallesAsientos($idRutaVuelta) : null;

            // Cargar la vista para elegir asientos
            return view('v_elegir_asiento', $data);
        }

        private function getDetallesAsientos($idRuta)
        {
            // Obtener el número de serie del tren asociado a la ruta
            $num_serieTrenRuta = $this->modeloRutas->num_serieRuta($idRuta);

            // Obtener la información del tren
            $tren = $this->modeloTrenes->find($num_serieTrenRuta);

            // Si no se encuentra el tren, retornar un array vacío
            if (!$tren) {
                return [];
            }

            $asientosOcupados = $this->modeloReservas->asientosOcupados($idRuta);

            // Inicializar el array de asientos
            $asientos = [];

            // Calcular la cantidad de asientos por vagón
            $asientosPorVagon = ceil($tren->capacidad / $tren->vagones);

            // Generar la estructura de asientos por vagón
            for ($vagon = 1; $vagon <= $tren->vagones; $vagon++) {
                $asientos[$vagon] = [];
                for ($asiento = 1; $asiento <= $asientosPorVagon; $asiento++) {
                    $numeroAsiento = ($vagon - 1) * $asientosPorVagon + $asiento;
                    if ($numeroAsiento <= $tren->capacidad) {
                        $asientos[$vagon][] = [
                            'numero' => $numeroAsiento,
                            'ocupado' => in_array($numeroAsiento, $asientosOcupados)
                        ];
                    }
                }
            }

            return $asientos;
        }

        public function revisarCompra()
        {
            // Cargar datos de sesión
            $data = session()->get('reserva_data');

            // Validar sesión
            if (!$data) {
                return redirect()->to('/reserva')->with('error', 'La sesión ha expirado.');
            }

            // Obtener los ID de ruta seleccionados
            $idRutaIda = $this->request->getPost('servicioSel');
            $idRutaVuelta = $this->request->getPost('servicioVueltaSel');
            session()->set('idRutaIda',$idRutaIda);
            session()->set('idRutaVuelta',$idRutaVuelta);
            
            // Obtener los servicios seleccionados
            $data['servicio_ida'] = $this->modeloRutas->find($idRutaIda);
            if ($idRutaVuelta) {
                $data['servicio_vuelta'] = $this->modeloRutas->find($idRutaVuelta);
            }

            // Recuperar los asientos seleccionados
            $asientosIda = $this->request->getPost('asientos_ida');
            $asientosVuelta = $this->request->getPost('asientos_vuelta');

            // Guardar los asientos en la sesión
            session()->set('asientos_ida', $asientosIda);
            session()->set('asientos_vuelta', $asientosVuelta);

            // Calcular el total del precio
            $total = $this->calcularPrecioTotal($idRutaIda, $idRutaVuelta, $data['Numbilletes'], session()->get('asientoAleatorio'), $asientosIda, $asientosVuelta);
            $data['total'] = $total;

            // Pasar los asientos a la vista
            $data['asientosIda'] = $asientosIda;
            $data['asientosVuelta'] = $asientosVuelta;

            return view('v_revisar_compra', $data);
        }
        

        private function calcularPrecioTotal($idRutaIda, $idRutaVuelta, $numBilletes, $asientoAleatorio, $asientosIda = null, $asientosVuelta = null)
        {
            $precioIda = $this->modeloRutas->getPrecioRuta($idRutaIda);
            $precioVuelta = $idRutaVuelta ? $this->modeloRutas->getPrecioRuta($idRutaVuelta) : 0;
            $precioTotal = ($precioIda + $precioVuelta) * $numBilletes;

            // Si asientoAleatorio está desactivado, añadir 5€ por billete
            if (!$asientoAleatorio && ($asientosIda || $asientosVuelta)) {
                $precioTotal += 5 * $numBilletes;
            }

            return $precioTotal;
        }

        // Función que registra la compra en la BD y manada correo al cliente
        public function realizarCompra() {
            // Verificar selección de servicio de ida
            $postData=session()->get('reserva_data');
            //RUTAS IDA
            $id_ruta_ida = session()->get('idRutaIda');
            $datosRutaIda = $this->modeloRutas->dameDatosRuta($id_ruta_ida);
            $fecha_ida= $this->formatearFecha($postData['fecha_ida']);
            //RUTAS VUELTA
            $id_ruta_vuelta=session()->get('idRutaVuelta');
            $datosRutaVuelta = $this->modeloRutas->dameDatosRuta($id_ruta_vuelta);
            $fecha_vuelta= $this->formatearFecha($postData['fecha_vuelta']);
            //FECHA IDA, NUMBILLETES, ASIENTO ALETORIO
            $fechaIda = new DateTime($fecha_ida);
            $numBilletes = session()->get('Numbilletes');
            $asientoAleatorio=session()->get('asientoAleatorio');
            $arrAsientosRandomIda = [];
            $arrAsientosRandomVuelta = [];
            var_dump($numBilletes);
            // Validar datos de ida
            if (!property_exists($datosRutaIda, 'hora_salida')) {
                log_message('error', 'Datos incompletos en ruta de ida ID: '.$id_ruta_ida);
                return view('v_home', ['error' => 'Datos técnicos incompletos del viaje']);
            }

            // Validar viaje de vuelta
            if (isset($_POST['servicioVueltaSel'])) {
                $id_ruta_vuelta = $_POST['servicioVueltaSel'];
                $datosRutaVuelta = $this->modeloRutas->dameDatosRuta($id_ruta_vuelta);
                $fecha_vuelta=new DateTime($datosRutaVuelta->hora_salida);

                // Validar datos de vuelta
                if (!property_exists($datosRutaVuelta, 'hora_salida')) {
                    log_message('error', 'Datos incompletos en ruta de vuelta ID: '.$id_ruta_vuelta);
                    return view('v_home', ['error' => 'Datos técnicos incompletos del viaje de vuelta']);
                }

                // Comparar fechas y horarios
                if ($fecha_ida->format('Y-m-d') === $fecha_vuelta->format('Y-m-d')) {
                    $horaLlegadaIda = strtotime($datosRutaIda->hora_llegada);
                    $horaSalidaVuelta = strtotime($datosRutaVuelta->hora_salida);

                    if ($horaLlegadaIda >= $horaSalidaVuelta) {
                        $msgErrorHorario = "ERROR: La hora de llegada de la ida (".date('H:i', $horaLlegadaIda).") 
                                        es posterior a la salida de la vuelta (".date('H:i', $horaSalidaVuelta).")";
                        return view('v_home', [
                            'ciudadesOrg' => $this->obtenerCiudades('origen'),
                            'ciudadesDes' => $this->obtenerCiudades('destino'),
                            'error' => $msgErrorHorario
                        ]);
                    }
                }
            }

            if($asientoAleatorio){
                var_dump("ENTRO");
                $arrAsientosRandomIda = $this->generarAsientoRandom($id_ruta_ida, $numBilletes);
                if($id_ruta_vuelta!=null){
                    $arrAsientosRandomVuelta = $this->generarAsientoRandom($id_ruta_vuelta, $numBilletes);
                }                
            }else{
                $arrAsientosRandomIda=session()->get('asientos_ida');
                if($id_ruta_vuelta!=null){
                    $arrAsientosRandomVuelta=session()->get('asientos_vuelta');
                }
            }

            // Registrar reserva ida
            $this->modeloReservas->agregarReserva(
                session()->get('dniCliente'), 
                $id_ruta_ida, 
                $arrAsientosRandomIda
            );

            if($id_ruta_vuelta!=null){
                // Registrar reserva vuelta
                $this->modeloReservas->agregarReserva(
                    session()->get('dniCliente'), 
                    $id_ruta_vuelta, 
                    $arrAsientosRandomVuelta
                );
            }
            // Enviar email
            $emailCliente = $this->modeloClientes->dameCliente(session()->get('dniCliente'))->email;
            $arrNumTicket = $this->modeloReservas->dameIdTicket(session()->get('dniCliente'), $arrAsientosRandomIda, date('Y-m-d'));
            
            $emailEnviado = $this->enviarEmailCompra(
                $emailCliente, 
                $fechaIda,
                $datosRutaIda->hora_salida, 
                $datosRutaIda->origen, 
                $datosRutaIda->destino,
                $arrNumTicket,
                false
            );

            if($id_ruta_vuelta!=null){
                $emailCliente = $this->modeloClientes->dameCliente(session()->get('dniCliente'))->email;
                $arrNumTicket = $this->modeloReservas->dameIdTicket(session()->get('dniCliente'), $arrAsientosRandomVuelta, date('Y-m-d'));
                $fechaVuelta = new DateTime($fecha_vuelta);
                $emailEnviado = $this->enviarEmailCompra(
                    $emailCliente, 
                    $fechaVuelta,
                    $datosRutaVuelta->hora_salida, 
                    $datosRutaVuelta->origen, 
                    $datosRutaVuelta->destino,
                    $arrNumTicket,
                    true
                );
            }
            return view('v_home', [
                'compraOk' => $arrAsientosRandomIda,
                'emailOk' => $emailEnviado
            ]);
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
                            'cOrigen' => $ruta->origen,
                            'cDestino' => $ruta->destino,
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
                            'cOrigen' => $ruta->origen,
                            'cDestino' => $ruta->destino,
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
                    return redirect()->to('/opinion')->with('msgInfoOpi', 'Gracias por tu opinión <br> Tu opinión ha sido guardado correctamente');
                } else {
                    return redirect()->to('/opinion')->with('msgErrOpin', 'ERROR de inserción en la BD!');
                }
            }
        }

      
        // Función que obtiene todas las reservas que haya hecho el cliente logueado 
        public function getReservasCli() {
            $dniCli = session()->get('dniCliente');
            if ($dniCli == null) {
                return redirect()->to('/autenticacion');
            }

            $datosReservas = $this->modeloReservas
                ->where('dni', $dniCli)
                ->findAll();

            // lo que necesito -> id_ticket, num_Asiento, ciudadorg, ciudaddes, fecha de viaje 'fecha'
            $datos = [];
            if (count($datosReservas) > 0) {
                foreach ($datosReservas as $reserva) {
                    $datosRuta = $this->modeloRutas
                                ->where('id_ruta', $reserva->id_ruta)
                                ->first();
                    $datos[] = [
                        'reserva' => $reserva,
                        'ruta' => $datosRuta
                    ];
                }
            }

           // Separar en dos arrays: futuras y pasadas
            $hoy = strtotime(date('Y-m-d'));
            $futuras = [];
            $pasadas = [];

            foreach ($datos as $dato) {
                $fechaViaje = strtotime($dato['ruta']->fecha);
                if ($fechaViaje >= $hoy) {
                    $futuras[] = $dato;
                } else {
                    $pasadas[] = $dato;
                }
            }

            // Ordenar cada grupo por fecha (ascendente)
            usort($futuras, function ($a, $b) {
                return strtotime($a['ruta']->fecha) - strtotime($b['ruta']->fecha);
            });

            usort($pasadas, function ($a, $b) {
                return strtotime($a['ruta']->fecha) - strtotime($b['ruta']->fecha);
            });

            // Unir primero futuras, luego pasadas
            $datosOrdenados = array_merge($futuras, $pasadas);

            return view("v_viajes", ['datos' => $datosOrdenados]);
        }
    } 
?>