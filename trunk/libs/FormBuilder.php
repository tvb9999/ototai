<?php
class FormBuilder {
	private $html = "";

	public function __construct() {
		require_once 'skin_formbuilder.php';
		$this->html = new skin_formbuilder ();
	}

	public function __destruct() {
	}

	function buildForm($formData = array()) {
		// Build hidden elements
		$hiddenFieldsHTML = "";
		foreach ( $formData ['hidden'] as $value ) {
			$arrayAttribute = array ('type' => 'hidden', 'id' => $value ['id'], 'name' => $value ['name'], 'value' => $value ['data'] );
			
			$hiddenFieldsHTML .= $this->html->inputTagHTML ( $this->convertArrayAttributeToString ( $arrayAttribute ) );
		}
		$formDataHTML ['hiddenFields'] = $hiddenFieldsHTML;
		
		// Build other elements
		$elementsHTML = "";
		foreach ( $formData ['elements'] as $value ) {
			$elementsHTML .= $this->buildElements ( $value );
		}
		$formDataHTML ['elementHTML'] = $elementsHTML;
		
		// Build button elements
		foreach ( $formData ['buttons'] as $value ) {
			$attributeString = $this->convertArrayAttributeToString ( $value );
			$buttonHTML .= $this->html->inputTagHTML ( $attributeString );
		}
		$formDataHTML ['buttonHTML'] = $buttonHTML;
		
		// Apply form
		$formDataHTML ['label'] = $formData ['label'];
		$arrayAtribute = array ('id' => $formData ['id'], 'name' => $formData ['name'], 'method' => $formData ['method'] ? $formData ['method'] : 'post' );
		if ($formData ['enctype'])
			$arrayAttribute ['enctype'] = $formData ['enctype'];
		
		$formDataHTML ['attributeString'] = $this->convertArrayAttributeToString ( $arrayAttribute );
		
		return $this->html->formHTML ( $formDataHTML );
	}

	function buildElements($element = array()) {
		switch ($element ['type']) {
			case 'text' :
				$output = $this->buildInputText ( $element );
				break;
			
			case 'textarea' :
				$output = $this->buildTextArea ( $element );
				break;
			
			case 'radio' :
				$output = $this->buildRadio ( $element );
				break;
			/*
				 case 'checkbox':
				 $output = $this->buildCheckBox($element);
				 break;

				 case 'button':
				 $output = $this->buildButton($element);
				 break;

				 case 'datetime':
				 $output = $this->buildDateTime($element);
				 break;*/
		}
		
		return $output;
	}

	function buildRadio($element = array()) {
		
		$count = 1;
		foreach ( $element ['data'] as $key => $value ) {
			$arrayAttribute = array ('type' => 'radio', 'name' => $element ['name'], 'id' => $element ['id'] . "-" . $count, 'value' => $value );
			if ($element ['check'] == $key) {
				$arrayAttribute ['checked'] = 'checked';
			}
			
			$attributeString = $this->convertArrayAttributeToString ( $arrayAttribute );
			
			if ($element ['extend'])
				$attributeString .= $this->convertArrayAttributeToString ( $element ['extend'] );
			
			$elementHTML .= $this->html->groupElementTagHTML ( $key, $attributeString );
			
			$count ++;
		}
		
		return $this->html->groupHTML ( $element ['label'], $elementHTML );
	}

	function buildTextArea($element = array()) {
		if ($element ['richtext']) {
			global $vsStd;
			$vsStd->requireFile ( UTILS_PATH . "class_editor.php" );
			
			$editor = new class_editor ();
			$editor->setEditorID ( $element ['id'] );
			$editor->setValue ( $element ['data'] );
			
			$arrayAttribute = array ('width' => '350px', 'height' => '200px' );
			
			if ($element ['extend'] ['width'])
				$arrayAttribute ['width'] = $element ['extend'] ['width'];
			if ($element ['extend'] ['height'])
				$arrayAttribute ['height'] = $element ['extend'] ['height'];
			
			$output = $this->html->richTextEditor ( $element ['label'], $editor->createEditor ( $element ['name'], $arrayAttribute ) );
		} else {
			$arrayAttribute = array ('id' => $element ['id'], 'name' => $element ['name'] );
			$attributeString = $this->convertArrayAttributeToString ( $arrayAttribute );
			if (count ( $element ['extend'] ))
				$attributeString .= $this->convertArrayAttributeToString ( $element ['extend'] );
			
			$output = $this->html->textareaTagHTML ( $element ['label'], $element ['data'], $attributeString );
		}
		
		if ($element ['description'])
			$output .= $this->html->descriptionHTML ( $element ['description'] );
		
		return $output;
	}

	function buildInputText($element = array()) {
		$arrayAttribute = array ('type' => 'text', 'name' => $element ['name'], 'id' => $element ['id'], 'value' => $element ['data'] );
		
		$attributeString = $this->convertArrayAttributeToString ( $arrayAttribute );
		if (count ( $element ['extend'] ))
			$attributeString .= $this->convertArrayAttributeToString ( $element ['extend'] );
		
		$output = $this->html->inputTextHTML ( $element ['label'], $attributeString );
		
		if ($element ['description'])
			$output .= $this->html->descriptionHTML ( $element ['description'] );
		
		return $output;
	}

	public function convertArrayAttributeToString($arrayAtrribute) {
		foreach ( $arrayAtrribute as $key => $value ) {
			$str .= "{$key}=\"{$value}\" ";
		}
		unset ( $arrayAtrribute );
		return $str;
	}

	/**
	 * Buld checkbox function
	 * @param $arrayCheckBox
	 * @param $arraySelected
	 * @return unknown_type
	 * Example: $formBuilder->buildcheckBox(array(	input1=>array(name=>Top, value=>1),
	 * input2=>array(name=>Bottom,2),
	 * input3=>array(name=>Left,3),
	 * input4=>array(name=>Right,4)
	 * ),
	 * array(1,2));
	 */
	public function buildCheckBox($arrayCheckBox = array(), $arraySelected = array()) {
		
		$returnHTML = "";
		foreach ( $arrayCheckBox as $key => $value ) {
			$arrayAttribute = array ();
			
			$arrayAttribute ['type'] = "checkbox";
			$arrayAttribute ['name'] = $key;
			$arrayAttribute ['id'] = $key;
			$arrayAttribute ['value'] = $value ['value'];
			$checkBoxTitle = $value ['name'];
			
			if (in_array ( $value ['value'], $arraySelected )) {
				$arrayAttribute ['checked'] = "checked";
			}
			
			$returnHTML .= $this->buildInputTag ( $arrayAttribute, $checkBoxTitle );
		}
		
		return $returnHTML;
	}

	public function buildInputTag($arrayAtrribute = array(), $title = "") {
		
		$str = $this->convertArrayAttributeToString ( $arrayAtrribute );
		unset ( $arrayAtrribute );
		
		return $this->html->inputTagHTML ( $str, $display );
	}

	public function addOption($arrayAtrribute, $display) {
		$str = $this->convertArrayAttributeToString ( $arrayAtrribute );
		
		$this->listOption .= $this->html->buildOptionHTML ( $str, $display );
		
		unset ( $arrayAtrribute );
		unset ( $str );
	}

	public function buildDropDownBox($arrayAtrribute) {
		$str = $this->convertArrayAttributeToString ( $arrayAtrribute );
		
		unset ( $arrayAtrribute );
		$out = $this->html->buildDropDownBoxHTML ( $str, $this->listOption );
		unset ( $this->listOption );
		return $out;
	}

	public function buildInputTextArea($arrayAtrribute, $value) {
		$str = $this->convertArrayAttributeToString ( $arrayAtrribute );
		unset ( $arrayAtrribute );
		return $this->html->buildInputTextAreaHTML ( $str, $value );
	}

	public function createDropDownDay($day = 0, $end) {
		for($i = 1; $i ++; $i < $end) {
			
			if ($day) {
				if ($i == $day)
					$selected = "selected => 'selected = selected'";
			} elseif ($i == date ( 'D' ))
				$selected = "selected => 'selected = selected'";
			else
				$selected = "";
			
			$arr = array ("name" => "lqv_day_{$i}", "value" => "day_{$i}", $selected );
			$this->addOption ( $arr, $i );
		}
		unset ( $end );
		unset ( $arr );
		unset ( $i );
	}

	public function createDropDownMonth($month) {
		for($i = 1; $i ++; $i < 13) {
			
			if ($month) {
				if ($i == $month)
					$selected = "selected => 'selected = selected'";
			} elseif ($i == date ( 'M' ))
				$selected = "selected => 'selected = selected'";
			else
				$selected = "";
			
			$arr = array ("name" => "lqv_month_{$i}", "value" => "month_{$i}", $selected );
			$this->addOption ( $arr, $i );
		}
	}

	public function createDropDownYear($year) {
		$end = date ( 'Y' );
		for($start = date ( 'Y' ) - 30; $start ++; $start <= $end) {
			
			if ($year) {
				if ($start == $year)
					$selected = "selected => 'selected = selected'";
			} elseif ($start == date ( 'Y' ))
				$selected = "selected => 'selected = selected'";
			else
				$selected = "";
			
			$arr = array ("name" => "lqv_year_{$start}", "value" => "year_{$start}", $selected );
			$this->addOption ( $arr, $start );
		}
		unset ( $arr );
		unset ( $start );
		unset ( $end );
	}

	public function buildInputDateTime($name) {
		global $vsPrint;
		$vsPrint->addJavaScriptString ( "date-picker", "
	    		$(document).ready(function(){
	    		$('#date-picker').datepicker({showOn: 'button', buttonImage: imgurl+'cal.gif', buttonImageOnly: true});
				  });
    		" );
		$out = $this->html->buildInputDateTimeHTML ( $name );
		return $out;
	}

	public function validateDate($day, $month, $year) {
		if ($month < 1 || $month > 12 || ! is_int ( $month ))
			return false;
		if ($year < 1 || $year > date ( 'Y' ) || ! is_int ( $year ))
			return false;
		if ($day < 1 || $day > $this->caculatorDayEndOfMonth ( $month, $year ))
			return false;
		return true;
	}

	public function caculatorDayEndOfMonth($month, $year) {
		if ($month == 4 || $month == 6 || $month == 9 || $month == 11)
			return 30;
		if ($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12)
			return 31;
		if ($month == 2 && ($year % 4) == 0 && ($year % 100) != 0)
			return 28;
		return 29;
	}

	public function buildElementForm($arrayAtrribute) {
		switch ($arrayAtrribute ['type']) {
			case 'select' :
				$out = $this->buildDropDownBox ( $arrayAtrribute );
				break;
			case 'date' :
				$out = $this->buildInputDateTime ( $arrayAtrribute );
				break;
			default :
				$out = $this->buildInputSimple ( $arrayAtrribute );
				break;
		}
		return $out;
	}

	public function buildListInputType() {
		return array ('textbox' => 'textbox', 'textarea' => 'textarea', 'button' => 'button', 'submit' => 'submit', 'select' => 'select', 'checkbox' => 'checkbox', 'hidden' => 'hidden', 'radio' => 'radio', 'date' => 'date', 'password' => 'password', 'image' => 'image', 'file' => 'file', 'reset' => 'reset' );
	}
}
?>