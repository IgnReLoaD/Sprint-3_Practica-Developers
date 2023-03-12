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
	'/listtask' => 'task#index',
	'/addtask'  => 'task#add',
	'/edittask' => 'task#edit',
	'/deltask'  => 'task#del',
	'/viewtask' => 'task#view',
	'/searchtask' => 'task#search',
	'/searchtodeletetask' => 'task#searchtodelete',
	'/viewalltask' => 'task#viewall',

);
