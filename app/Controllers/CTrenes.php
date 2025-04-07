<?php

    namespace App\Controllers;

    use App\Models\ModeloTrenes;
    use App\Models\ModeloRutas;


    class CTrenes extends BaseController {
        protected $modeloRutas;
        protected $modeloTrenes;

        public function __construct(){
            $this->modeloTrenes = new ModeloTrenes();
            $this->modeloRutas = new ModeloRutas();
        }

        
        // Función que compruebe la validación de num_serie de un tren 'Tiene que tener 4 digitos y 3 letras'
        public function numSerieValido($num_serie)
        {
            if (is_string($num_serie) && strlen($num_serie) === 12) {
                // Verifica si todos los caracteres son digitos
                if (ctype_digit($num_serie)) {
                    return true;
                }
            }
            return false;
        }

        // Función que compruebe si una matricula ya existe en BD o no
        public function numSerieYaExiste($num_serie)
        {
            $matriculaExiste = $this->modeloTrenes->capacidadTren($num_serie);
            if ($matriculaExiste == null) {
                return false;
            } else {
                return true;
            }
        }



        public function administracionTrenes(){
            $datosTrenes = $this->modeloTrenes->datosTrenes();

            // Añadir nuevo tren
            if (isset($_POST['aniadirTren'])) {  // igual a $this->request->getPost('aniadirTren')
                $num_serie = $_POST['numSerie'];

                $msg = '';
                if (!$this->numSerieValido($num_serie)) {
                    $msg .= 'Número de serie erróneo! (Tiene que tener exactamente 12 dígitos)<br>';
                }
                if ($this->numSerieYaExiste($num_serie)) {
                    $msg .= 'Número de serie ya existe!';
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

                        // Guardar los datos del Trenes en la BD
                        $capacidad = $_POST['capacidad'];
                        $modelo = $_POST['modelo'];
                        $bagones = $_POST['bagones'];
                        
                        $trenInsertado = $this->modeloTrenes->insertarTren($num_serie, $capacidad, $modelo,$bagones, $nomImg);
                        if ($trenInsertado) {
                            return view('v_home', [
                                'datosTrenes' => $datosTrenes,
                                'msgMatriExito' => 'Tren agregado correctamente'
                            ]);
                        } else {
                            return view('v_home', [
                                'datosTrenes' => $datosTrenes,
                                'msgErrorTren' => 'No se ha podido agregar el Trene!'
                            ]);
                        }
                    } else {
                        // No se ha subido la imagen 'guardar el Trene con imagen sinImg.png'
                        $capacidad = $_POST['capacidad'];
                        $modelo = $_POST['modelo'];
                        $bagones = $_POST['bagones'];

                        $trenInsertado = $this->modeloTrenes->insertarTren($num_serie, $capacidad, $modelo, $bagones, 'sinImg.png');
                        if ($trenInsertado) {
                            return view('v_home', [
                                'datosTrenes' => $datosTrenes,
                                'msgMatriExito' => 'Tren agregado correctamente'
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



            // Click sobre Borrar Trene
            if (isset($_POST['borrarTren'])) {
                // Obtener la num_Serie
                $num_serie = $_POST['numSerie'];
                // Antes de eliminar un tren deberia checkear si esta usado en alguna ruta en una fecha del futuro o el mismo dia
                $trenYaEnUso = $this->modeloRutas->trenEnUso($num_serie); // tren se usa en fecha futura

                $trenUsadoSoloPasado = $this->modeloRutas->trenUsadoPasado($num_serie);   // tren ha sido usado antes y ya no
                if ($trenUsadoSoloPasado) {
                    // Eliminar registros de rutas pasadas
                    $this->modeloRutas->eliminarRutasNumSerie($num_serie);
                    // Eliminar tren
                    $this->modeloTrenes->eliminarTren($num_serie);
                    return view('v_home', [
                                        'datosTrenes' => $datosTrenes,
                                        'msgExitoEliTren' => 'Tren eliminado correctamente junto con sus rutas pasadas'
                    ]);
                }
                if ($trenYaEnUso) {
                    // obtener id_rutas de la matricula
                    $idRutas = $this->modeloRutas->dameRutasTren($num_serie);
                    $rutasStr = '';
                    foreach ($idRutas as $ruta) {
                        $rutasStr .= $ruta->id_ruta . ',';
                    }
                    // Error no se puede eliminar el tren
                    $msj = 'No se puede eliminar el tren con el número de serie: ' . $num_serie . ' ya que esta en uso <br>
                        Considera eliminar primero las rutas que tiene asignado <br>
                        Nº de rutas: ' . $rutasStr . '<br><i> (Solo se eliminan trenes con rutas antiguas de la fecha de hoy)</i>';
                    return view('v_home', [
                        'datosTrenes' => $datosTrenes,
                        'msgErrorEliTren' => $msj
                    ]);
                } else {
                    // Suprimir su imagen de images/trenes
                    $trenObj = $this->modeloTrenes->dameDatosTren($num_serie);
                    if ($trenObj->imagen != 'sinImg.png') {      // tren tiene imagen
                        $rutaCompleta = WRITEPATH . '../public/images/trenes/' . $trenObj->imagen ;
                        unlink($rutaCompleta);      // Eliminar img
                    }
                    // Eliminar el tren de BD
                    $eliminacionExito = $this->modeloTrenes->eliminarTren($num_serie);

                    return view('v_home', [
                        'datosTrenes' => $datosTrenes,
                        'eliminacionExito' => $eliminacionExito ? 'Tren eliminado correctamente junto con sus rutas pasadas' : 'No se ha podido eliminar el tren'
                    ]);
                }
            }

            // Lanzar la vista v_home
            return view('v_home', ['datosTrenes' => $datosTrenes]);
        
        }



        // Función que actualiza datos de un tren pasando su matricula
        public function modificarTren($num_serie) {
            $tren = $this->modeloTrenes->dameDatosTren($num_serie);
              
            // Al click actualizar Trenes
            if (isset($_POST['actualizarTren'])) {
                
                // Obtener nuevos datos del Trenes
                $capacidad = $_POST['capacidad'];
                $modelo = $_POST['modelo'];
                $bagones = $_POST['bagones'];

                // Validación de datos
                $msgErrModTren = '';
                if ($capacidad == '') {
                    $msgErrModTren .= 'Deberias introducir la capacidad! <br>';
                }
                if ($modelo == '') {
                    $msgErrModTren .= 'Deberias introducir el modelo del tren!';
                }
                if ($bagones == '') {
                    $msgErrModTren .= 'Deberias introducir la cantidad de bagones del tren!';
                }
                if ($msgErrModTren != '') {
                    return view('v_home', ['trenMod' => $tren,
                                        'msgErrModTren' => $msgErrModTren]);
                } else {
                    // Configuración de subida 'caso haya subido algo'
                    $imgFile = $this->request->getFile('imagen');
                    if ($imgFile && $imgFile->isValid() && !$imgFile->hasMoved()) {
                        // Validar que sea una imagen
                        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
                        if (!in_array($imgFile->getMimeType(), $tiposPermitidos)) {
                            return view('v_home', [
                                'trenMod' => $tren,
                                'msgErrModTren' => 'El archivo debe ser una imagen (JPG, PNG, GIF).'
                            ]);
                        }
                        // Mover la imagen y guardarla
                        $rutaDestino = WRITEPATH . '../public/images/trenes/';
                        $nomImg = $imgFile->getName();  // Obtener el nombre original de la imagen
                        $imgFile->move($rutaDestino, $nomImg);

                        // suprimir la imagen antigua si la tenia
                        if ($tren->imagen != 'sinImg.png') {
                            $rutaCompleta = WRITEPATH . '../public/images/trenes/' . $tren->imagen ;
                            unlink($rutaCompleta);      // Eliminar img
                        }

                        // Actualizar
                        $actualizado = $this->modeloTrenes->actualizarTren($num_serie, $capacidad, $modelo, $bagones, $nomImg);
                        if ($actualizado) {
                            return view('v_home', ['trenMod' => $tren,
                                                    'msgInfoModTren' => 'Datos actualizados correctamente']);
                        } else {
                            return view('v_home', ['trenMod' => $tren,
                                                    'msgErrModTren' => 'Error al actualizar el tren BD!']);
                        }
                    } else {
                        // No se ha subido la imagen 'guardar el tren con imagen sinImg.png'
                        // Actualizar
                        $actualizado = $this->modeloTrenes->actualizarTren($num_serie, $capacidad, $modelo, $bagones);
                        if ($actualizado) {
                            return view('v_home', ['trenMod' => $tren,
                                                    'msgInfoModTren' => 'Datos actualizados correctamente']);
                        } else {
                            return view('v_home', ['trenMod' => $tren,
                                                    'msgErrModTren' => 'Error al actualizar el tren BD!']);
                        }
                    }
                }
            }
                
            // Cargar la vista v_modTren
            return view('v_home', ['trenMod' => $tren]);
        }



    }

?>