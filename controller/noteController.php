<?php

require '../vendor/autoload.php';
require '../model/noteModel.php';

class noteController {

	private $_client;

	function getClient() {
		if (!$this->_client) {
			$this->_client = new Basho\Riak\Riak('127.0.0.1', 8098);
		}
		return $this->_client;
	}

	function defaultAction() {
		$data = array();
		$items = array();

		if (isset($_GET['status']) && isset($_GET['key'])) {
			$status = strtolower($_GET['status']);
			if ($status == 'success') {
				$key = $_GET['key'];
				$data['success'] = "Deleted note with id: {$key}.";
			}
		}

		$bucket = $this->getClient()->bucket('note');
		$keys = $this->getClient()->add('note')
				->map("function (v) { return [v.key]; }")
				->reduce("Riak.reduceSort")
				->run();
		
		foreach ($keys as $key) {
			$json = $bucket->get($key)->getData();
			if ($json) {
				$note = new noteModel();
				$note->deriakize($key, $json);
				$items[] = $note;
			}
		}

		usort($items, function($a, $b) {
			if ($a->date == $b->date) {
				return 0;
			}
			return $a->date > $b->date;
		});

		$data['items'] = $items;

		view('default', $data);
	}

	function deleteAction($data = array()) {
		if (!isset($data['key'])) {
			header('Location: ' . baseurl('note/', true));
			return;
		}

		$key = $data['key'];
		$bucket = $this->getClient()->bucket('note');
		$object = $bucket->get($key);
		$json = $object->getData();
		$note = new noteModel();
		$note->deriakize($key, $json);

		if (count($_POST) > 0) {
			$object->delete();

			header('Location: ' . baseurl('note?status=success&key=' . $key, true));
			return;
		}		

		$data['title'] = 'Delete note';
		$data['action'] = 'Delete';
		$data['form_action'] = baseurl('note/delete/key/' . $key, true);
		$data['text'] = $note->text;
		$data['subject'] = $note->subject;
		$data['disabled'] = true;

		view('crud', $data);
	}

	function editAction($data = array()) {
		if (!isset($data['key'])) {
			header('Location: ' . baseurl('note/', true));
			return;
		}

		if (isset($_GET['status'])) {
			$status = strtolower($_GET['status']);
			if ($status == 'success') {
				$data['success'] = "Updated note.";
			}
		}

		$key = $data['key'];
		$bucket = $this->getClient()->bucket('note');
		$object = $bucket->get($key);
		$json = $object->getData();
		$note = new noteModel();
		$note->deriakize($key, $json);

		if (count($_POST) > 0) {
			$note->subject = isset($_POST['subject']) ? $_POST['subject'] : '';
			$note->text = isset($_POST['text']) ? $_POST['text'] : '';

			$object->setData($note->riakize($key));
			$object->store();

			header('Location: ' . baseurl('note/edit/key/' . $key . '?status=success', true));
			return;
		}		

		$data['title'] = 'Edit note';
		$data['action'] = 'Edit';
		$data['form_action'] = baseurl('note/edit/key/' . $key, true);
		$data['text'] = $note->text;
		$data['subject'] = $note->subject;

		view('crud', $data);
	}

	function addAction($data = array()) {
		if (isset($data['key'])) {
			$key = $data['key'];
			$note = new noteModel();
			$note->subject = isset($_POST['subject']) ? $_POST['subject'] : '';
			$note->text = isset($_POST['text']) ? $_POST['text'] : '';
			$note->date = time();

			$bucket = $this->getClient()->bucket('note');
			$object = $bucket->newObject($key, $note->riakize($key));
			$object->store();

			header('Location: ' . baseurl('note/add?status=success&key=' . $key, true));
			return;
		}

		if (isset($_GET['status']) && isset($_GET['key'])) {
			$status = strtolower($_GET['status']);
			if ($status == 'success') {
				$key = $_GET['key'];
				$data = array('success' => "Saved note with id: {$key}.");
			}
		}

		$data['title'] = 'Add note';
		$data['action'] = 'Add';
		$data['form_action'] = baseurl('note/add/key/' . uniqid(), true);

		view('crud', $data);
	}
}