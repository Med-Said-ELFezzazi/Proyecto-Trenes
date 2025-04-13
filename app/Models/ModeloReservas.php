<?php
    namespace App\Models;
    use CodeIgniter\Model;

    class ModeloReservas extends Model {

    protected $table      = 'reservas';
    protected $primaryKey = 'id_ticket';

    protected $useAutoIncrement = true;

    protected $returnType     = 'object';   // Array de obj, como se le pasan o devuelve las filas de la tabla
    protected $useSoftDeletes = false;

    protected $allowedFields = ['dni', 'id_ruta', 'num_asiento', 'fecha_reserva', 'opinion', 'fecha_opinion'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Obtener todas ciudades de origen
    public function ciudadesOrg() {
        $ciudadesOrg = $this
            ->select('id_ruta, ciudad_origin')
            ->groupBy('ciudad_origin')
            ->findAll();
        return $ciudadesOrg;
    }

    // Obtener todas ciudades de destino
    public function ciudadesDes() {
        $ciudadesDes = $this->select('id_ruta, ciudad_destino')
                    ->groupBy('ciudad_destino')
                    ->findAll();
        return $ciudadesDes;
    }

    // Obtener datos de rutas con datos seleccionados
    public function datosRutas($fecha, $ciudad_origin, $ciudad_destino) {
        $datosRutas = $this
            ->where('fecha', $fecha)
            ->where('ciudad_origin', $ciudad_origin)
            ->where('ciudad_destino', $ciudad_destino)
            ->orderBy('hora_salida', 'ASC')
            ->findAll();
        return $datosRutas;
    }
    
    // Obtener datos de tarifas segund ciudad origin
    public function datosTarifas() {
        $datosTarifas = $this
            ->distinct()
            ->select('ciudad_origin, ciudad_destino, tarifa')
            ->orderBy('ciudad_origin', 'ASC')
            ->findAll();
        return $datosTarifas;
    }

    // Función que el número de reservas de un viaje
    public function numeroReservas($id_ruta) {
        $numReservas = $this
            ->where('id_ruta', $id_ruta)
            ->countAllResults();
        return $numReservas;
    }
    

    // Función que inserta una reserva Y DEVUELVE true o false
    public function agregarReserva($dni, $id_ruta, $arrAsientos) {
        $this->db->transStart();
    
        foreach ($arrAsientos as $asiento) {
            $this->insert([
                'dni' => $dni,
                'id_ruta' => $id_ruta,
                'num_asiento' => $asiento,
                'fecha_reserva' => date('Y-m-d H:i:s')
            ]);
        }
    
        $this->db->transComplete();
    
        return $this->db->transStatus();
    }
    

    // Función que obtiene idTicket/num_asiento de una reserva    
    public function dameIdTicket($dni, $id_ruta, $fecha_reserva) {
        $tickets = $this
            ->select('id_ticket, num_asiento')
            ->where('dni', $dni)
            ->where('id_ruta', $id_ruta)
            ->where("DATE(fecha_reserva)", $fecha_reserva) // Comparar solo la fecha
            ->get()
            ->getResultArray(); // Devuelve el resultado en formato array
        
        // Devolver un array de resultados
        return empty($tickets) ? [] : $tickets;
    }  
        

    // Función que obtiene los numeros de asientos de una ruta pasada en el param
    public function asientosReservadosRuta($id_ruta) {
        $asientos = $this
            ->select('num_asiento')
            ->where('id_ruta', $id_ruta)
            ->findAll();
        return $asientos;       // Devuelve un array de objetos 'num asiento'
    }

    // Función que elimina reservas de ruta pasando su id_ruta en param
    public function eliminarReservasRuta($id_ruta) {
        $this->where('id_ruta', $id_ruta)
        ->delete('reservas');
    }


    // funcion que insertar opinion a unas reservas pasadas en formato array y el dni del cliente
    public function insertarOpinion($id_tickets, $opinion, $dni){
        $this->db->transStart();
        //'un cliente puede reservar más de un billete de un viaje'
        // Array aux para dar el mismo opinion a todas las resevas con la misma ruta
        $arrRutas = []; 
        foreach ($id_tickets as $id_ticket) {
            $reserva = $this->find($id_ticket); // La reserva formato objeto
            if (!in_array($reserva->id_ruta, $arrRutas)) {
                // Actualizar todas las reservas con el mismo id_ruta
                $data = [
                    'opinion' => $opinion,
                    'fecha_opinion' => date('Y-m-d H:i:s')
                ];

                $this->where('id_ruta', $reserva->id_ruta)
                    ->where('dni', $dni)
                    ->update(null, $data);
                
                $arrRutas[] = $reserva->id_ruta;
            }
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }


    // Función que devuelve reservas sin opinión de un cliente pasado su dni
    public function reservasSinOpinionCli($dni) {
        $reservas = $this
                    ->where('dni', $dni)
                    ->where('opinion', '')
                    ->groupBy('id_ruta')    // Para obtener rutas únicas 'pq un cliente puede hacer varias reservas para una ruta'
                    ->findAll();
        return empty($reservas) ? [] : $reservas;
    }



    public function asientoOcupado($id_ruta, $num_asiento) {
        return $this->where('id_ruta', $id_ruta)
                    ->where('num_asiento', $num_asiento)
                    ->countAllResults() > 0;
    }



}