<?php
class ErrorHander {
	private $status = true;
	private $message = "";
	public $output = "";
	private $html = "";

	public function __construct() {
		$this->status = true;
		$this->message = "";
	}

	public function __destruct() {
	}

	public function getOutput() {
		return $this->output;
	}

	public function setOutput($output) {
		$this->output = $output;
	}

	/**
	 * @return unknown
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @return unknown
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param unknown_type $message
	 */
	public function setMessage($message) {
		$this->message = $message;
	}

	/**
	 * @param unknown_type $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

}
?>