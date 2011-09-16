<?php
class menus_public{

	private $model		= null;
	private $view		= null;
	private $output		= "";

	public function __construct(){
		require_once (CORE_PATH.'menus/menus.php');
		$this->model = new menus();
	}

	public function __destruct(){unset($this);}

	public function auto_run(){
		global $bw;
		switch ($bw->input[1]){
			case 'view':{
				$this->output = $this->mapLink($bw->input[2]);
				break;
			}
			default:
				$this->output = $this->mapLink($bw->input[2]);
				break;
		}
	}

	public function mapLink($id){

		$menu = $this->model->getObjectById($id);

		$actionLink = array();

		if($menu){
			$actionLink = explode('/', $menu->getUrl());
		}

		return $actionLink;
	}



	/**
	 * @param $output the $output to set
	 */
	public function setOutput($output) {
		$this->output = $output;
	}

	/**
	 * @param $view the $view to set
	 */
	public function setView($view) {
		$this->view = $view;
	}

	/**
	 * @param $model the $model to set
	 */
	public function setModel($model) {
		$this->model = $model;
	}

	/**
	 * @return the $output
	 */
	public function getOutput() {
		return $this->output;
	}

	/**
	 * @return the $view
	 */
	public function getView() {
		return $this->view;
	}

	/**
	 * @return the $model
	 */
	public function getModel() {
		return $this->model;
	}



}