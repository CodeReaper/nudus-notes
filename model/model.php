<?php

class model {
	var $key;

	function riakize($modelKey) {
		$json = array();
		foreach ($this as $key => $value) {
			$json[$key] = $value;
		}
		$json['key'] = $modelKey;
		return json_encode($json);
	}

	function deriakize($modelKey, $json) {
		$json = json_decode($json);
		foreach ($json as $key => $value) {
			$this->$key = $value;
		}
		$this->key = $modelKey;
	}
}