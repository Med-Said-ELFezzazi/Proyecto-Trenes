<?php

namespace App\Controllers;

use App\Models\ModeloAverias;
use App\Models\ModeloTrenes;

class CAverias extends BaseController {

    protected $modeloAverias;
    protected $modeloTrenes;

    public function __construct()
    {
        $this->modeloAverias = new ModeloAverias();
        $this->modeloTrenes = new ModeloTrenes();
    }


    // Función para mostrar los datos de averias
    public function gestionAverias() {
        // Busqueda con filtros
        $num_serie = '';
        $estado = '';   //str
        $costeMin = '';     //str
        $costeMax = '';     //str
        $fecha = '';    //string(10) "2025-01-31
        $datosFiltrados = [];
        if ($this->request->getPost('aplicarFiltros')) {
            // Obtener los datos selecccionados
            $num_serie = $_POST['numSerieAveria'];
            $estado= $_POST['estadoAveria'] ?? '';
            $fecha = $_POST['fechaAveria'];
            
            $costeMin = $_POST['costeMinAveria'];
            $costeMax = $_POST['costeMaxAveria'];

            // Hago la busqueda en BD
            $datosFiltrados = $this->modeloAverias->datosAveriasFiltrados($num_serie, $fecha, $costeMin, $costeMax, $estado);

            return view('v_home', ['datosFiltrados' => $datosFiltrados]);
        }

        // Añadir avería
        // Mostrar formulario
        if ($this->request->getPost('mostrarForm')) {
            // Paso las numsSeries que hay en la BD
            $nums_series = $this->modeloTrenes->datosTrenes();
            return view('v_home', ['numsSeries' => $nums_series]);
        }
        // Formulario de añadir enviado
        if ($this->request->getPost('GuardarAveria')) {
            // Obtener los datos seleccinados
            $numSerieSel = $_POST['numSerieSel'];
            $descripcion = $_POST['descripcion'];
            // FEcha, comprobar si haya checkeado 'checkbox'
            $fecha = '';
            if (isset($_POST['fechayhora_hoy'])) {  // Si esta marcado
                $fecha = date('Y-m-d H:i:s');
            } else {
                // Obtener la fecha del input
                $fecha = $_POST['fecha'];
            }
            $coste = $_POST['costeAveria'];
            $reparada = '';

            // Comprobación de datos insertados
            $msgErrAltaAveria = '';
            if ($numSerieSel == '0') {
                $msgErrAltaAveria .= 'Deberias seleccionar un número de serie! <br>';
            }
            if ($descripcion == '') {
                $msgErrAltaAveria .= 'Deberias introducir una descripción de la avería! <br>';
            }
            if ($fecha == '') {
                $msgErrAltaAveria .= 'Deberias indicar la fecha/hora de la avería! <br>';
            }
            if ($coste == '' || $coste <= 0) {
                $msgErrAltaAveria .= 'Deberias definir un coste a la avería! <br>';
            }
            if (!isset($_POST['reparadaAveria'])) {
                $msgErrAltaAveria .= 'Deberias indicar si la avería ya esta reparada o no! <br>';
            } else {
                $reparada = $_POST['reparadaAveria'];
            }

            // Según el msg lanzo a la vista
            $nums_series = $this->modeloTrenes->datosTrenes();  // PAra lanzar view
            if ($msgErrAltaAveria == '') {  // Ningun error
                // Insertar en BD
                $insertado = $this->modeloAverias->insertarAveria($numSerieSel, $descripcion,
                            $fecha, $coste, $reparada);
                
                if ($insertado) {
                    return view('v_home', ['numsSeries' => $nums_series,
                                            'msgInfoAveria' => 'Avería añadida correctamente']);
                } else {
                    return view('v_home', ['numsSeries' => $nums_series,
                                        'msgErrorAveria' => 'Error al añadir la avería']);
                }
            } else {
                return view('v_home', ['numsSeries' => $nums_series,
                                    'msgErrorAveria' => $msgErrAltaAveria]);
            }
        }

        $datosAverias = $this->modeloAverias->datosAverias();
        // Eliminar avería
        if ($this->request->getPost('id_averiaBorrar')) {
            // id_averia a eliminar
            $id_averia = $_POST['id_averiaBorrar'];
            // Eliminar de la BD
            $eliminado = $this->modeloAverias->eliminarAveria($id_averia);
            return view('v_home', ['datosAverias' => $datosAverias,
                                    'eliminacionAveria' => $eliminado]);
        }
        
        return view('v_home', ['datosAverias' => $datosAverias]);
    }



    // Función que recibe el id_averia obtiene sus datos de BD y los carga en la vista con formulario para modificar
    public function modificarAveria($id_averia) {
        $nums_series = $this->modeloTrenes->datosTrenes(); // PAra cargar dropdown
        $averia = $this->modeloAverias->dameAveria($id_averia);     // Datos de la averia

        // Button submit actualizar averia clickado
        if ($this->request->getPost('actualizarAveria')) {
            // Obtener los datos actualizados
            $numSerieSel = $_POST['numSerieSel'];
            $descripcion = $_POST['descripcion'];          
            // FEcha, comprobar si haya checkeado 'checkbox'
            $fecha = '';
            if (isset($_POST['fechayhora_hoy'])) {  // Si esta marcado
                $fecha = date('Y-m-d H:i:s');
            } else {
                // Obtener la fecha del input
                $fecha = $_POST['fecha'];
            }
            $coste = $_POST['costeAveria'];
            $reparada = $_POST['reparada'];

            // Validación de datos
            $msgErrModAveria = '';
            if ($numSerieSel == '0') {
                $msgErrModAveria .= 'Deberias seleccionar un número de serie! <br>';
            }
            if ($descripcion == '') {
                $msgErrModAveria .= 'Deberias introducir una descripción de la avería! <br>';
            }
            if ($fecha == '') {
                $msgErrModAveria .= 'Deberias indicar la fecha/hora de la avería! <br>';
            }
            if ($coste == '' || $coste <= 0) {
                $msgErrModAveria .= 'Deberias definir un coste a la avería! <br>';
            }

            if ($msgErrModAveria == '') {  // Ningun error
                // Actualizar en BD
                $actualizado = $this->modeloAverias->actualizarAveria($id_averia, $numSerieSel, $descripcion, $fecha, $coste, $reparada);
                
                if ($actualizado) {
                    $averiaActualizada = $this->modeloAverias->dameAveria($id_averia);
                    return view('v_home', ['numsSeries' => $nums_series,
                                            'averia' => $averiaActualizada,
                                            'msgInfoAveria' => 'Avería actualizada correctamente']);
                } else {
                    return view('v_home', ['numsSeries' => $nums_series,
                                            'averia' => $averia,
                                            'msgInfoAveria' => 'Error al actualizar la avería']);
                }
            } else {
                return view('v_home', ['numsSeries' => $nums_series,
                                    'averia' => $averia,
                                    'msgErrorAveria' => $msgErrModAveria]);
            }

        } else {
            // Cargar v_modAveria con campos repoblados
            return view('v_home', ['numsSeries' => $nums_series,
                                'averia' => $averia]);
        }       
    }

}
