<?php

define('NUDUS', dirname(dirname($_SERVER['SCRIPT_FILENAME'])) . DIRECTORY_SEPARATOR);
define('NUDUS_CONTROLLER', NUDUS . 'controller' . DIRECTORY_SEPARATOR);
define('NUDUS_VIEW', NUDUS . 'view' . DIRECTORY_SEPARATOR);
define('NUDUS_FILE_SUFFIX', '.php');
define('NUDUS_CONTROLLER_SUFFIX', 'Controller');
define('NUDUS_ACTION_SUFFIX', 'Action');
define('NUDUS_DEFAULT_CONTROLLER', 'default');
define('NUDUS_DEFAULT_ACTION', 'default');
define('NUDUS_BASE_URL', str_replace($_SERVER['DOCUMENT_ROOT'], '', NUDUS));

function baseurl($name, $return = false) {
	if ($return) {
		return NUDUS_BASE_URL . $name;
	} else {
		echo NUDUS_BASE_URL . $name;
	}
}

function view($name, $data = array()) {
	extract($data);
	require NUDUS_VIEW . $name . NUDUS_FILE_SUFFIX;
}

function route($controller, $action, $key_value_pairs) {
	$controllerPath = NUDUS_CONTROLLER . $controller . NUDUS_FILE_SUFFIX;
	if (file_exists($controllerPath)) {
		require $controllerPath;
		$controller = new $controller();
		if (method_exists($controller, $action)) {
			call_user_func_array(array($controller, $action), array($key_value_pairs));
			exit(0);
		}
	}
}

$controller = NUDUS_DEFAULT_CONTROLLER;
$action = NUDUS_DEFAULT_ACTION;
$key_value_pairs = array();

if ($_GET['url']) {
	$url = rtrim($_GET['url'], '/');
	$url = filter_var($url, FILTER_SANITIZE_URL);
	$url = explode('/', $url);

	if (count($url) >= 1) {
		$controller = array_shift($url);
	}

	if (count($url) >= 1) {
		$action = array_shift($url);
	}

	$count = count($url);
	for ($i=0; $i < $count; $i = $i + 2) {
		$value = null;
		if (isset($url[$i + 1])) {
			$value = $url[$i + 1];
		}
		$key_value_pairs[$url[$i]] = $value;
	}
}

$action = $action . NUDUS_ACTION_SUFFIX;
$controller = $controller . NUDUS_CONTROLLER_SUFFIX;

route($controller, $action, $key_value_pairs);
route('Http404' . NUDUS_CONTROLLER_SUFFIX, NUDUS_DEFAULT_ACTION . NUDUS_ACTION_SUFFIX, array());
