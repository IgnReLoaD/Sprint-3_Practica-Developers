<?php 

/**
 * Used to define the routes in the system.
 * 
 * A route should be defined with a key matching the URL and an
 * controller#action-to-call method. E.g.:
 * 
 * '/' => 'index#index',
 * '/calendar' => 'calendar#index'
 */

$routes = array(
	// check test route first
	// 1. Landing ha de mostrar Vista - users - login
	// rutes per USERS
    '/'         => 'user#index',
    '/index'    => 'user#index',
    // '/listuser' => 'user#index',

	// 2. Si no existeix, ha de deixar CREAR user
    '/adduser'  => 'user#add',

	// 3. Modificar EDIT user
    '/edituser' => 'user#edit',   // 'UserController.php?id=3' ... rebrà per GET la ID ... function editAction($_GET[id])
	// 4. Eliminar DEL user
    '/deluser'  => 'user#del',
	
	// rutes per TASKS
	'/test' => 'test#index',

	// menú de Tasks (botones: NovaTasca, BuscarID, ListarTotes, EliminarID)
	'/listtask' => 'task#index',
	// all Tasks (ListarTotes)
	'/viewalltask' => 'task#viewall',	
	// add Task (formulari input camps)
	'/addtask'  => 'task#add',
	// to store the task in the BD
	'/storetask' => 'task#store',
	// to delete the task in the BD
	'/deltask'  => 'task#del',
	
	'/viewtask' => 'task#view',
	'/edittask' => 'task#edit',


	'/searchtask' => 'task#search',
	'/searchtodeletetask' => 'task#searchtodelete',

);
