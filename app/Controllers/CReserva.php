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
                $campo = ($tipo === 'origen') ? 'origen' : 'destino';
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
            $origen = $_POST['origen'];
            $destino = $_POST['destino'];
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
                $origen = $datosRuta->origen;
                $destino = $datosRuta->destino;
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



        public function enviarEmailCancelacion($emailCliente, $idReservas, $origen, $destino, $fecha, $horaSalida, $tarifa) {
            // Obtener fecha y hora actual
            $fechaCancelacion = date('Y-m-d');
            $horaCancelacion = date('H:i');
        
            // Cuerpo del mensaje
            $cuerpo = "
                <h2 style='color: red;'>Cancelacion confirmada</h2>
                <p>Tu reserva ha sido cancelada con exito. Detalles del trayecto:</p>
                <div style='border: 2px dashed #dc3545; width: 500px; padding: 15px; background-color: #f8f9fa; font-family: sans-serif;'>
                    <table border='0' style='width: 100%; font-size: 14px;'>
                        <tr><td><b>IDs de reserva:</b></td><td>{$idReservas}</td></tr>
                        <tr><td><b>Fecha del viaje:</b></td><td>{$fecha}</td></tr>
                        <tr><td><b>Hora de salida:</b></td><td>{$horaSalida}</td></tr>
                        <tr><td><b>Origen:</b></td><td>{$origen}</td></tr>
                        <tr><td><b>Destino:</b></td><td>{$destino}</td></tr>
                        <tr><td><b>Tarifa aplicada:</b></td><td>{$tarifa} Euros</td></tr>
                        <tr><td><b>Fecha de cancelacion:</b></td><td>{$fechaCancelacion}</td></tr>
                        <tr><td><b>Hora de cancelacion:</b></td><td>{$horaCancelacion}</td></tr>
                    </table>
                </div>
                <br>
                <p><i>Recibiras el reembolso conforme a la politica de cancelacion.</i></p>
                <p>Gracias por confiar en nosotros.</p>
            ";
        
            $emailService = Services::emailService();
            return $emailService->sendEmail(
                $emailCliente,
                'Confirmacion de cancelacion de reserva',
                $cuerpo
            );
        }
        


        public function cancelarReservasCli() {
            $jsonReservas = $this->request->getPost('reservasObjs');
            $jsonRuta = $this->request->getPost('rutaObjs');

            // Conviertir a array asociativo
            $reservas = json_decode($jsonReservas, true); 
            $ruta = json_decode($jsonRuta, true);
            if (!session()->has('ruta')) {
                session()->set('ruta', $ruta);
            }

            $reservasSeleccionadas = (array) $this->request->getPost('reservas');

            if (count($reservasSeleccionadas) > 0) {
                $idReservas = '';
                $count=0;

                // Quitar el valor 'todos'
                $reservasSeleccionadas = array_filter($reservasSeleccionadas, function($resv) {
                    return $resv !== 'todos';
                });

                // Rellenar la variable de id-reservas
                foreach ($reservasSeleccionadas as $resv) {
                    if ($count == count($reservasSeleccionadas) - 1) {
                        $idReservas .= $resv;
                    } else {
                        $idReservas .= $resv . ", ";
                    }
                    $count++;
                    // Eliminar de la BD
                    $this->modeloReservas->delete($resv);
                }                

                // ENVIAR EMAIL
                $email = $this->modeloClientes->dameCliente(session()->get('dniCliente'))->email;
                $horaFormateada = date('H:i', strtotime(session()->get('ruta', $ruta)['hora_salida']));

                $this->enviarEmailCancelacion($email, $idReservas, session()->get('ruta', $ruta)['origen'], session()->get('ruta', $ruta)['destino'],
                                        session()->get('ruta', $ruta)['fecha'], $horaFormateada, session()->get('ruta', $ruta)['tarifa']);

                // Resetear las variables de session
                session()->remove(['ruta']);

                return view("v_cancelReserva", ['reservas' => $reservas, 'ruta' => $ruta, 'reservasSeleccionadas' => $reservasSeleccionadas]);
            }

            return view("v_cancelReserva", ['reservas' => $reservas, 'ruta' => $ruta]);
        }



        public function enviarEmailModificacion($emailCliente, $idReservas, $origen, $destino, $fecha, $horaSalida, $tarifa) {
            // Obtener fecha y hora actual
            $fechaModificacion = date('Y-m-d');
            $horaModificacion = date('H:i');
        
            // Cuerpo del mensaje
            $cuerpo = "
            <h2 style='color: blue;'>Modificacion confirmada</h2>
            <p>Tu reserva ha sido modificada con exito. Detalles del trayecto actualizado:</p>
            <div style='border: 2px dashed #007bff; width: 500px; padding: 15px; background-color: #f8f9fa; font-family: sans-serif;'>
                <table border='0' style='width: 100%; font-size: 14px;'>
                <tr><td><b>IDs de reserva:</b></td><td>{$idReservas}</td></tr>
                <tr><td><b>Fecha del viaje:</b></td><td>{$fecha}</td></tr>
                <tr><td><b>Hora de salida:</b></td><td>{$horaSalida}</td></tr>
                <tr><td><b>Origen:</b></td><td>{$origen}</td></tr>
                <tr><td><b>Destino:</b></td><td>{$destino}</td></tr>
                <tr><td><b>Tarifa aplicada:</b></td><td>{$tarifa} Euros</td></tr>
                <tr><td><b>Fecha de modificacion:</b></td><td>{$fechaModificacion}</td></tr>
                <tr><td><b>Hora de modificacion:</b></td><td>{$horaModificacion}</td></tr>
                </table>
            </div>
            <br>
            <p><i>Gracias por confiar en nosotros. ¡Buen viaje!</i></p>
            ";
        
            $emailService = Services::emailService();
            return $emailService->sendEmail(
            $emailCliente,
            'Confirmación de modificación de reserva',
            $cuerpo
            );
        }


        public function modificarReservasCli() {
            $jsonReservas = $this->request->getPost('reservasObjs');
            $jsonRuta = $this->request->getPost('rutaObjs');

            // Conviertir a array asociativo
            $reservas = json_decode($jsonReservas, true); 
            $ruta = json_decode($jsonRuta, true);

            session()->set('rutaMod', $ruta);

            $reservasSeleccionadas = (array) $this->request->getPost('reservas');
            
            if (count($reservasSeleccionadas) > 0) {
                // Quitar 'todos'
                $reservasSeleccionadas = array_filter($reservasSeleccionadas, function($res){
                    return $res !== 'todos';
                });           
                // Guardar las reservas seleccioandas a modificar
                session()->set('reservasSeleccionadas', $reservasSeleccionadas);

                // Ruta actual a cambiar
                $rutaViaje = session()->get('rutaMod');     

                // Obtener viajes que hay del mismo origen y destino con fecha del dia o futura
                $viajesDisponibles = $this->modeloRutas
                                    ->where('origen', $rutaViaje['origen'])
                                    ->where('destino', $rutaViaje['destino'])
                                    ->where('fecha >=', date("Y-m-d"))
                                    ->where('id_ruta !=', $rutaViaje['id_ruta'])
                                    ->findAll();

                // Quitar la ruta de la reserva actual
                $viajesDisponibles = array_filter($viajesDisponibles, function($rutas){
                    return $rutas !== session()->get('rutaMod');
                });

                return view("v_modReserva", ['rutaMod' => session()->get('rutaMod'), 'reservasSeleccionadas' => $reservasSeleccionadas,
                                            'viajesDisponibles' => $viajesDisponibles]);
            } 
            
            // Id_ruta seleccionado 'la nueva ruta a poner'
            $newIdRuta = $this->request->getpost('idRutaSele');
            if ($newIdRuta != null) {
                $idsReservas = '';
                $cont = 0;
                // Iterar sobre tadas las reservas seleccionadas
                foreach (session()->get('reservasSeleccionadas') as $id_res) {
                    // Comprobar si el asiento libre sino asignar uno aleatorio
                    $numAsientoAntiguo = $this->modeloReservas
                        ->where('id_ticket', $id_res)
                        ->first()
                        ->num_asiento;
                    // Comprobar si el asiento esta libre en la nueva ruta 
                    $asientosOcupados = $this->modeloReservas
                            ->where('id_ruta', $newIdRuta)
                            ->findAll();
                    $asientos = [];
                    $esLibre = true;
                    foreach ($asientosOcupados as $reserva) {
                        if ($reserva->num_asiento == $numAsientoAntiguo) {
                            $esLibre = false;
                            break;
                        }
                        $asientos[] = $reserva->num_asiento;
                    }

                    if ($esLibre) {
                        // No cambiar el asiento solo la id_ruta
                        $this->modeloReservas->update($id_res, ['id_ruta' => $newIdRuta]);
                    } else {
                        // Hacer la modificación de id_ruta de la reserva y num_asiento
                        $numSerie = $this->modeloRutas->where('id_ruta', $newIdRuta)->first()->num_serie;

                        // Generar un random en el rango de asientos que hay
                        $capacidadMaxTren = $this->modeloTrenes
                                            ->where('num_serie', $numSerie)->first()->capacidad;

                        do {
                            $randomAsiento = rand(1, $capacidadMaxTren);
                        } while (in_array($randomAsiento, $asientos));

                         $this->modeloReservas->update($id_res, ['id_ruta' => $newIdRuta, 'num_asiento' => $randomAsiento]);
                    }
                    if ($cont == count(session()->get('reservasSeleccionadas')) - 1) {
                        $idsReservas .=  $id_res;
                    } else {
                        $idsReservas .= $id_res . ", ";
                    }
                    $cont++;
                }

                // ENVIAR EMAIL mandar un correo de la modificacion
                $email = $this->modeloClientes->dameCliente(session()->get('dniCliente'))->email;
                $objNewRuta = $this->modeloRutas->where('id_ruta', $newIdRuta)->first();


                $this->enviarEmailModificacion($email, $idsReservas, $objNewRuta->origen, $objNewRuta->destino,
                                        $objNewRuta->fecha,  $objNewRuta->hora_salida, $objNewRuta->tarifa);

                return view("v_modReserva", ['reservasModificadas' => 'Operación realizada correctamente',]);
            }

            return view("v_modReserva", ['reservas' => $reservas, 'ruta' => $ruta]);
        }


    } 


?>