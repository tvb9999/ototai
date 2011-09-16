<?php

class skin_categories {
	
	private $name;
	
	public $num;

	public function __construct($name = 'vfiles', $num = 0) {
		
		$this->name = $name;
		
		$this->num = $num;
	
	}

	public function __destruct() {
		
		unset ( $this->name );
	
	}

	public function displayMainPage($categoryTable = "", $categoryForm = "") {
		
		$BWHTML .= <<<EOF

			<div class='main-category' id='main-category_{$this->num}'>

				<div  class="left-cell"  id="category-table_{$this->num}">{$categoryTable}</div>

				<div class="right-cell" id="category-form_{$this->num}">{$categoryForm}</div>

			</div>

			<div class="clear"></div>

EOF;
		
		return $BWHTML;
	
	}

	public function displayListCategory($categoryElements = "", $buttons = "", $message = "") {
		
		global $vsLang;
		
		$DELETE_CATEGORY = DELETE_CATEGORY;
		
		$DISPLAY_EDIT_CATEGORY = DISPLAY_EDIT_CATEGORY;
		
		$BWHTML .= <<<EOF

			<script type="text/javascript">

			$(document).ready(function(){

				currentId = 0;

				$('#category-table-list_{$this->num}').change(function() {

				

				    $("#category-table-list_{$this->num} option:selected").each(function () {

				        currentId = $(this).val();

				    });

				    $('#category-form-parent-id').val(currentId);

				    $('#category-chosse_{$this->num}').html(' :'+currentId);

				});

		

				$('#delete-category-bt_{$this->num}').click(function() {

					if(!currentId || currentId == 0 ) {

						$('#category-table-message_{$this->num}').html('{$vsLang->getWords('err_chosen_category', 'Please choose category to perform your action!')}');

						$('#category-table-list_{$this->num}').addClass('ui-state-error');

						return false;

					}

					$('#category-table-list_{$this->num}').removeClass('ui-state-error');

					$('#category-table-message_{$this->num}').html('');

					

					if(!confirm('{$vsLang->getWords('category_confirm_delete','Are you sure to delete category?')}')){

						return false;

					}else{

						vsf.get('{$this->name}/{$DELETE_CATEGORY}/'+currentId+'/','category-table_{$this->num}');	

					}

					$('#category-form-parent-id_{$this->num}').val(0);

				});

				

				$('#edit-category-bt_{$this->num}').click(function() {

					if(!currentId || currentId ==0 ) {

						$('#category-table-message_{$this->num}').html('{$vsLang->getWords('err_chosen_category', 'Please choose category to perform your action!')}');

						$('#category-table-list_{$this->num}').addClass('ui-state-error');

						return false;

					}

					$('#category-table-list_{$this->num}').removeClass('ui-state-error');

					$('#category-table-message_{$this->num}').html('');

					vsf.get('{$this->name}/{$DISPLAY_EDIT_CATEGORY}/'+currentId+'/','category-form_{$this->num}');

				});

				

				$('#add-{$this->name}-bt_{$this->num}').click(function() {

					if(!currentId || currentId ==0 ) {

						$('#category-table-message_{$this->num}').html('{$vsLang->getWords('err_chosen_category', 'Please choose category to perform your action!')}');

						$('#category-table-list_{$this->num}').addClass('ui-state-error');

						return false;

					}

					$('#category-table-list_{$this->num}').removeClass('ui-state-error');

					$('#category-table-message_{$this->num}').html('');

					vsf.get('{$this->name}/DISPLAY_FORM/', 'category-form_{$this->num}');

				});

				

				$('#view-{$this->name}-bt_{$this->num}').click(function() {

					if(!currentId || currentId ==0 ) {

						$('#category-table-message_{$this->num}').html('{$vsLang->getWords('err_chosen_category', 'Please choose category to perform your action!')}');

						$('#category-table-list_{$this->num}').addClass('ui-state-error');

						return false;

					}

					$('#category-table-list_{$this->num}').removeClass('ui-state-error');

					$('#category-table-message_{$this->num}').html('');

					vsf.get('{$this->name}/VIEW_CATEGORY/'+ currentId +"/", 'category-form_{$this->num}');

				});

			});

			</script>

		

			<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">

				<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">

			    	<span class="ui-dialog-title">{$vsLang->getWords('news_category_list','List of categories')}</span>

			    </div>

			    <div id="category-table-message_{$this->num}" class="red">{$message}</div>

			    <table class="ui-dialog-content ui-widget-content" cellpadding="0" cellspacing="0">

			    	<thead>

			    		<th width="400" align='left'>

			    		{$vsLang->getWords('category_table_title_header','Categories')}<h id="category-chosse_{$this->num}"></h>

			    		</th>

			    		<th width="100">{$vsLang->getWords('category_table_action_header','Actions')}</th>

			    	</thead>

			    	<tbody>

			    		<tr>

				    		<td>

				    			<select name="category-table-list_{$this->num}" id='category-table-list_{$this->num}' multiple="multiple" size="20">

				    				<option value="0">Root</option>

				    				{$categoryElements}

				    			</select>

				    		</td>

				    		<td>

				    			<!--<input id="edit-category-bt_{$this->num}" type="button" value="{$vsLang->getWords('category_table_edit_bt','Edit')}" />	

				    			<input id="delete-category-bt_{$this->num}" type="button" value="{$vsLang->getWords('category_table_delete_bt','Delete')}" />-->

				    			{$buttons}

				    		</td>

			    		</tr>

			    	</tbody>

			    </table>

			</div>

EOF;
		
		return $BWHTML;
	
	}

	public function displayCategoryForm($form = array(), $category = NULL) {
		
		global $vsLang;
		
		$ADD_EDIT_CATEGORY = ADD_EDIT_CATEGORY;
		
		$BWHTML .= <<<EOF

			<script type="text/javascript">

			$(document).ready(function(){

				$('#add-edit-category-form_{$this->num}').submit(function() {

					var error = 0;

					var str = "";

					

					if(!$('#category-name_{$this->num}').val()) {

						str += '* {$vsLang->getWords('err_category_name_blank','Please enter the category name!')}<br />';

						$('#category-name_{$this->num}').addClass('ui-state-error');

					}

					else {

						$('#category-name_{$this->num}').removeClass('ui-state-error');

					}

					

					if(str) {

						$('#err-category-form-message').html(str);

						return false;

					}

				

					$('#err-category-form-message_{$this->num}').html('');

					var parentId = $('#category-form-parent-id').val();

					vsf.submitForm($('#add-edit-category-form_{$this->num}'),'{$this->name}/{$ADD_EDIT_CATEGORY}/','main-category_{$this->num}');

					return false;

				});});

			</script>

			<form id="add-edit-category-form_{$this->num}" method="post">

				<input type="hidden" id="form-type_{$this->num}" name="formType" value="{$form['type']}" />

				<input type="hidden" name="categoryId" value="{$category->getId()}" />

				<input type="hidden" id="category-form-parent-id" name="categoryParentId" value="{$category->getParentId()}" />

				<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">

					<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">

				    	<span class="ui-dialog-title">{$vsLang->getWords('category_form_title_'.$form['type'],$form['type']." category form")}</span>

				    </div>

				    <div id="err-category-form-message" class="red">{$form['message']}</div>

				    <table class="ui-dialog-content ui-widget-content" cellpadding="0" cellspacing="0">

				    	<tr>

				        	<td>{$vsLang->getWords('category_form_header_name','Name')}</td>

				            <td><input id="category-name_{$this->num}" type="text" name="categoryName" size="36" value="{$category->getTitle()}" /></td>

				        	<td>{$vsLang->getWords('category_form_header_visible','Is visible')}</td>

				            <td>

				            	<input type="radio" class="checkbox" name="categoryIsVisible" value="1" {$category->visible}/> {$vsLang->getWords('global_yes','Yes')}

				            	<input type="radio" class="checkbox"  name="categoryIsVisible" value="0" {$category->unVisible}/> {$vsLang->getWords('global_no','No')}

				            </td>

						</tr>

						<tr>

				        	<td rowspan="2">{$vsLang->getWords('category_form_header_desc','Description')}</td>

				            <td rowspan="2"><textarea id="category-desc_{$this->num}" type="text" name="categoryDesc">{$category->getAlt()}</textarea></td>

				            <td>{$vsLang->getWords('category_form_header_dropdown','Is dropdown')}</td>

				            <td>

				            	<input type="radio" class="checkbox" name="categoryIsDropdown" value="1" {$category->dropdown}/> {$vsLang->getWords('global_yes','Yes')}

				            	<input type="radio" class="checkbox"  name="categoryIsDropdown" value="0" {$category->unDropdown}/> {$vsLang->getWords('global_no','No')}

				            </td>

						</tr>

						<tr>

						    <td>{$vsLang->getWords('category_form_header_index','Order')}</td>

				            <td><input type="text" name="categoryIndex" size="10" value="{$category->getIndex()}" /></td>

						</tr>

				        <tr>

				        	<td class="ui-dialog-buttonpanel" colspan="2" align="right"><input type="submit" value="{$vsLang->getWords('category_form_bt_'.$form['type'],$form['type'])}" /></td>

						</tr>

				    </table>

				</div>

			</form>

EOF;
		
		return $BWHTML;
	
	}

	public function displayButtonAddAndView() {
		
		global $vsLang;
		
		$BWHTML = <<<EOF

			<input id="add-{$this->name}-bt_{$this->num}" type="button" value="{$vsLang->getWords('category_table_add_bt','Add')}" />	

			<input id="view-{$this->name}-bt_{$this->num}" type="button" value="{$vsLang->getWords('category_table_view_bt','View')}" />	

EOF;
		
		return $BWHTML;
	
	}

	public function displayButtonEditAndDelete() {
		
		global $vsLang;
		
		$BWHTML = <<<EOF

			<input id="edit-category-bt_{$this->num}" type="button" value="{$vsLang->getWords('category_table_edit_bt','Edit')}" />	

			<input id="delete-category-bt_{$this->num}" type="button" value="{$vsLang->getWords('category_table_delete_bt','Delete')}" />	

EOF;
		
		return $BWHTML;
	
	}

}

?>