<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Modo sin sesión
$routes->get('/visitante', 'CVisitante::modoVisitante');
$routes->get('/visitante/lineasHorarios', 'CVisitante::lineasHorarios');   
$routes->post('/visitante/lineasHorarios', 'CVisitante::lineasHorarios');
$routes->get('/visitante/tarifas', 'CVisitante::tarifas');

// Modo con sesión 'Cliente'
// Autenticación
$routes->match(['GET', 'POST'], '/autenticacion', 'CLogin::index');
// Home 'bienvenida'
$routes->get('/home', 'CLogin::cargarHome');
// Modificar datos cliente
$routes->get('modificarCliente', 'CClientes::modificarCliente');
$routes->post('modificarCliente', 'CClientes::modificarCliente');
// Consultar horarios
$routes->match(['GET', 'POST'], '/lineasHorarios', 'CVisitante::lineasHorarios');
// Consultar tarifas
$routes->get('/tarifas', 'CVisitante::tarifas');
// Reservar
$routes->match(['GET', 'POST'], '/reserva', 'CReserva::reservar');
$routes->match(['GET', 'POST'], '/reserva/servicios', 'CReserva::servicios');
$routes->match(['GET', 'POST'], '/reserva/elegirAsiento', 'CReserva::elegirAsiento');
$routes->match(['GET', 'POST'], '/reserva/revisarCompra', 'CReserva::revisarCompra');

// REalizar la compra
$routes->post('reserva/realizarCompra', 'CReserva::realizarCompra');
$routes->post('reserva/confirmarCompra', 'CReserva::realizarCompra');


// Cerrar sesión
$routes->get('cerrarSession', 'CLogin::cerrarSession');

// ADMIN
$routes->get('/admin/home', 'CAdmin::index');
// Administración de trenes
$routes->get('/admin/trenes', 'CTrenes::administracionTrenes');
$routes->post('/admin/trenes', 'CTrenes::administracionTrenes');
$routes->match(['GET', 'POST'],'/admin/trenes/mod/(:any)', 'CTrenes::modificarTren/$1');

// Gestion de averias
$routes->get('/admin/averias', 'CAverias::gestionAverias');
$routes->post('/admin/averias', 'CAverias::gestionAverias');
// Modificar averia
$routes->match(['GET', 'POST'], '/admin/averias/modificar/(:num)', 'CAverias::modificarAveria/$1');

// Gestion de rutas
$routes->match(['GET', 'POST'], '/admin/rutas', 'CAdmin::gestionRutas');
// Modificar ruta
$routes->match(['GET', 'POST'], '/admin/rutas/modificar/(:num)', 'CAdmin::modificarRuta/$1');
// Eliminar ruta
$routes->get('/admin/rutas/eliminar/(:num)', 'CAdmin::eliminarRuta/$1');
// Añadir nueva ruta
$routes->match(['GET', 'POST'], '/admin/rutas/aniadirRuta', 'CAdmin::aniadirRuta');

// OPINION
$routes->get('/opinion', 'CReserva::opinar');
// Insertar opinión
$routes->post('/opinion/add' , 'CReserva::insertarOpinion');

// Mis viajes
$routes->get('/misViajes', 'CReserva::getReservasCli');
$routes->post('/cancelReserva', 'CReserva::cancelarReservasCli');
$routes->post('/modificarReserva', 'CReserva::modificarReservasCli');
