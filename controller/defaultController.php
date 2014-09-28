<?php

require '../vendor/autoload.php';

class defaultController {
	function defaultAction($data = array()) {
		header('Location: ' . baseurl('note', true));
	}
}