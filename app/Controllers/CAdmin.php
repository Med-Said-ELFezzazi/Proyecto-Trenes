<?php

namespace App\Controllers;

use App\Models\ModeloTrenes;
use App\Models\ModeloRutas;
// use stdClass;

class CAdmin extends BaseController {

    protected $modeloTrenes;
    protected $modeloRutas;

    public function __construct()
    {
        $this->modeloTrenes = new ModeloTrenes();
        $this->modeloRutas = new ModeloRutas();
    }


    public function index()
    {

        return view('v_home');
    }

    // Función que compruebe la validación de datos de un Tren 'Tiene que tener 4 digitos y 3 letras'
    /*public function num_serieValida($num_serie)
    {
        // Longitud
        if (strlen($num_serie) != 7) {
            return false;
        }
        if (ctype_digit(substr($num_serie, 0, 4)) && !ctype_digit(substr($num_serie, 4))) {
            return true;
        }
        return false;
    }

    // Función que compruebe si una num_serie ya existe en BD o no
    public function num_serieYaExiste($num_serie)
    {
        $num_serieExiste = $this->modeloTrenes->capacidadTren($num_serie);
        if ($num_serieExiste == null) {
            return false;
        } else {
            return true;
        }
    }

    // Función que lanza la vista home pasandole datosTrenes para cargarlo en la vista v_trenes
    public function administracionTrenes() {
        $datosTrenes = $this->modeloTrenes->datosTrenes();

        // Añadir nuevo Tren
        if (isset($_POST['aniadirTren'])) {  // igual a $this->request->getPost('aniadirTren')
            $num_serie = $_POST['num_serie'];

            $msg = '';
            if (!$this->num_serieValida($num_serie)) {
                $msg .= 'Numero de serie erróneo! (Tiene que tener 4 dígitos y 3 letras)<br>';
            }
            if ($this->num_serieYaExiste($num_serie)) {
                $msg .= 'Numero de serie ya existe!';
            }

            if ($msg != '') {
                return view('v_home', [
                    'datosTrenes' => $datosTrenes,
                    'msgErrorTren' => $msg
                ]);
            } else {
                // Configuración de subida 'caso haya subido algo'
                $imgFile = $this->request->getFile('imagen');
                if ($imgFile && $imgFile->isValid() && !$imgFile->hasMoved()) {
                    // Validar que sea una imagen
                    $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
                    if (!in_array($imgFile->getMimeType(), $tiposPermitidos)) {
                        return view('v_home', [
                            'datosTrenes' => $datosTrenes,
                            'msgErrorTren' => 'El archivo debe ser una imagen (JPG, PNG, GIF).'
                        ]);
                    }
                    // Mover la imagen y guardarla
                    $rutaDestino = WRITEPATH . '../public/images/trenes/';
                    $nomImg = $imgFile->getName();  // Obtener el nombre original de la imagen
                    $imgFile->move($rutaDestino, $nomImg);

                    // Guardar los datos del autobús en la BD
                    $capacidad = $_POST['capacidad'];
                    $modelo = $_POST['modelo'];

                    $trenInsertado = $this->modeloTrenes->insertarTren($num_serie, $capacidad, $modelo, $nomImg);
                    if ($trenInsertado) {
                        return view('v_home', [
                            'datosTrenes' => $datosTrenes,
                            'msgMatriExito' => 'Autobús agregado correctamente'
                        ]);
                    } else {
                        return view('v_home', [
                            'datosTrenes' => $datosTrenes,
                            'msgErrorTren' => 'No se ha podido agregar el tren!'
                        ]);
                    }
                } else {
                    // No se ha subido la imagen 'guardar el tren con imagen sinImg.png'
                    $capacidad = $_POST['capacidad'];
                    $modelo = $_POST['modelo'];
                    $trenInsertado = $this->modeloTrenes->insertarTren($num_serie, $capacidad, $modelo, 'sinImg.png');
                    if ($trenInsertado) {
                        return view('v_home', [
                            'datosTrenes' => $datosTrenes,
                            'msgMatriExito' => 'Autobús agregado correctamente'
                        ]);
                    } else {
                        return view('v_home', [
                            'datosTrenes' => $datosTrenes,
                            'msgErrorTren' => 'No se ha podido agregar el tren!'
                        ]);
                    }
                }
            }
        }

        // Click sobre Borrar tren
        if (isset($_POST['borrarTren'])) {
            // Obtener la num_serie
            $num_serie = $_POST['num_serie'];
            // Antes de eliminar un tren deberia checkear si esta usado en alguna ruta en una fecha del futuro o el mismo dia
            $trenYaEnUso = $this->modeloRutas->trenEnUso($num_serie); // Tren se usa en fecha futura

            $trenUsadoSoloPasado = $this->modeloRutas->trenUsadoPasado($num_serie);   // Tren ha sido usado antes y ya no
            if ($trenUsadoSoloPasado) {
                // Eliminar registros de rutas pasadas
                $this->modeloRutas->eliminarRutasnum_serie($num_serie);
                // Eliminar tren
                $this->modeloTrenes->eliminarTren($num_serie);
                return view('v_home', [
                    'datosTrenes' => $datosTrenes,
                    'msgExitoEliTren' => 'Tren eliminado correctamente junto con sus rutas pasadas'
                ]);
            }
            if ($trenYaEnUso) {
                // obtener id_rutas de la num_serie
                $idRutas = $this->modeloRutas->dameRutasTren($num_serie);
                $rutasStr = '';
                foreach ($idRutas as $ruta) {
                    $rutasStr .= $ruta->id_ruta . ',';
                }
                // Error no se puede eliminar el tren
                $msj = 'No se puede eliminar el tren con la num_serie: ' . $num_serie . ' ya que esta en uso <br>
                    Considera eliminar primero las rutas que tiene asignado <br>
                    Nº de rutas: ' . $rutasStr . '<br><i> (Solo se eliminan trenes con rutas antiguas de la fecha de hoy)</i>';
                return view('v_home', [
                    'datosTrenes' => $datosTrenes,
                    'msgErrorEliTren' => $msj
                ]);
            } else {
                // Suprimir su imagen de images/trenes
                $trenObj = $this->modeloTrenes->dameDatosTren($num_serie);
                if ($trenObj->imagen != 'sinImg.png') {      // Tren tiene imagen
                    $rutaCompleta = WRITEPATH . '../public/images/trenes/' . $trenObj->imagen ;
                    unlink($rutaCompleta);      // Eliminar img
                }
                // Eliminar el tren de BD
                $eliminacionExito = $this->modeloTrenes->eliminarTren($num_serie);

                return view('v_home', [
                    'datosTrenes' => $datosTrenes,
                    'eliminacionExito' => $eliminacionExito ? 'Tren eliminado correctamente.' : 'No se ha podido eliminar el tren.'
                ]);
            }
        }

        // Modificar
        if (isset($_POST['btnModificar'])) {
            $mod = 'modificando';
            return view('v_tst', ['datosTrenes' => $datosTrenes,
                        'mod' => $mod]);
        }
 

        // Lanzar la vista v_home
        return view('v_home', ['datosTrenes' => $datosTrenes]);
    }*/


    /*public function modificarTren() {
        $datosTrenes = $this->modeloTrenes->datosTrenes();
        $Newmodelo = $this->request->getPost('modelo');

        return view('v_home', ['datosTrenes' => $datosTrenes,
                            'modelo' => $Newmodelo]);
    }*/

}
