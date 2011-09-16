<?php
class skin_menus {
	function MainPage() {
		global $bw, $vsLang;
		$BWHTML = "";
		//--starthtml--//
		$BWHTML .= <<<EOF
<div id="page_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all-top">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all-inner">
        <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="{$bw->base_url}menus/viewuser/&ajax=1"><span>{$vsLang->getWords('menu_user',"User menus")}</span></a></li>
        <li class="ui-state-default ui-corner-top"><a href="{$bw->base_url}menus/viewadmin/&ajax=1"><span>{$vsLang->getWords('menu_admin',"Admin menus")}</span></a></li>
    </ul>
</div>
EOF;
		//--endhtml--//
		

		return $BWHTML;
	}
	
	function addEditMenuForm($form = array(), $message = "", $menu) {
		global $vsLang, $vsMenu, $bw,$vsStd,$vsSettings;
		
		$max_upload_size = min($vsStd->let_to_num(ini_get('post_max_size')), $vsStd->let_to_num(ini_get('upload_max_filesize')));
		$BWHTML = "";
		if ($form ['type']) {
			$switchForm = <<<EOF
<input class="button" type="button" value="{$vsLang->getWords('menu_bt_switch_add',"Form Add")}" name="switch" onclick="vsf.get('menus/addmenuform/{$menu->isAdmin}','addeditform_{$menu->isAdmin}');" />
EOF;
		}
		if (! $menu->getId ()) {
			$menu->type = 0;
			$menu->status = 1;
			$menu->isLink = 1;
			$menu->isDropdown = 0;
		}
		
		$BWHTML .= <<<EOF
<div class="ui-dialog ui-widget ui-widget-content ui-corner-all" style="min-height:400px;">
	<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
    	<span class="ui-dialog-title">{$vsLang->getWords('group_box_title','Group Admin List')}</span>
    </div>
    <div class="red">{$message}</div>
<form method="post" name="form" id="addEditMenu_{$menu->isAdmin}">
<input type="hidden" name="formType" value="{$form['type']}" />
<input type="hidden" name="ID" id="ID" value="{$menu->id}" />
<input type="hidden" name="menuIsAdmin" id="menuIsAdmin_{$menu->isAdmin}" value="{$menu->isAdmin}" />
<input type="hidden" name="parentId" id="parentId_{$menu->isAdmin}" value="{$menu->parentId}" />
<table cellpadding="0" cellspacing="1" width="100%">
<thead>
	<tr>
    	<th colspan="4">{$form['title']}</th>
	</tr>
</thead>
<tr>
    <td>{$vsLang->getWords('menu_form_name',"Name")}</td>
    <td><input type="text" value="{$menu->title}" name="menuTitle" id="menuTitle{$menu->isAdmin}" size="35" /></td>
    <td>{$vsLang->getWords('menu_form_visible',"Visible")}</td>
    <td>
       <input type="radio" class="checkbox " id="menuStatus_first" name="menuStatus" value="1" />
       <label for="menuStatus_first">{$vsLang->getWords('menu_form_yes',"Yes")}</label>
       <div class="clear"></div>
       <input type="radio" class="checkbox" id="menuStatus_last" name="menuStatus" value="0" />
       <label for="menuStatus_last">{$vsLang->getWords('menu_form_no',"No")}</label>
    </td>
</tr>
<tr>
    <td>{$vsLang->getWords('menu_form_link',"Url")}</td>
    <td><input type="text" value="{$menu->url}" name="menuUrl" size="35" /></td>
    <td>{$vsLang->getWords('menu_form_index',"Index")}</td>
    <td><input type="text" name="menuIndex" size="3" value="{$menu->index}" /></td>
</tr>
<tr>
    <td>{$vsLang->getWords('menu_form_type',"Type")}</td>
    <td>
        <input type="radio" class="checkbox" name="menuType" value="1" />
        <label for="menuType_first">{$vsLang->getWords('menu_form_external',"External")}</label>
        <div class="clear"></div>
        <input type="radio" class="checkbox"  name="menuType" value="0" />
        <label for="menuType_last">{$vsLang->getWords('menu_form_internal',"Internal")}</label>
    </td>
    <td> {$vsLang->getWords('menu_form_islink',"Is link")}</td>
    <td>
        <input type="radio" class="checkbox" name="menuIsLink" value="1" />
        <label for="menuIsLink_first">{$vsLang->getWords('menu_form_yes',"Yes")}</label>
        <div class="clear"></div>
        <input type="radio" class="checkbox" name="menuIsLink" value="0"/>
        <label for="menuIsLink_last">{$vsLang->getWords('menu_form_no',"No")}</label>
    </td>
</tr>
<tr>
    <td>{$vsLang->getWords('menu_form_alt',"Description")}</td>
    <td><input type="text" value="{$menu->alt}" name="menuAlt" size="35" /></td>
    <td>{$vsLang->getWords('menu_form_dropdown',"Dropdown")}</td>
    <td>
         <input type="radio" class="checkbox" name="menuIsDropdown" value="1" /> 
         <label for="menuIsDropDown_first">{$vsLang->getWords('menu_form_yes',"Yes")}</label>
         <div class="clear"></div>
         <input type="radio" class="checkbox" name="menuIsDropdown" value="0"/>
         <label for="menuIsDropDown_last">{$vsLang->getWords('menu_form_no',"No")}</label>
    </td>
</tr>
<tr>
    <td>{$vsLang->getWords('menu_form_position',"Position")}</td>
    <td>
    	 <input type="checkbox"  class="checkbox" id="main" name="posMain" value='1'  />
        <label for="main">{$vsLang->getWords('menu_form_main',"Main")}</label>
        <div class="clear"></div>
        <input type="checkbox"  class="checkbox" id="top" name="posTop" value='1'  />
        <label for="top">{$vsLang->getWords('menu_form_top',"Top")}</label>
        <div class="clear"></div>
        <input type="checkbox"  class="checkbox" id="right" name="posRight" value='1'  />
        <label for="right">{$vsLang->getWords('menu_form_right',"Right")}</label>
        <div class="clear"></div>
        <input type="checkbox"  class="checkbox" id="bottom" name="posBottom" value='1'/>
        <label for="bottom">{$vsLang->getWords('menu_form_bottom',"Bottom")}</label>
        <div class="clear"></div>
        <input type="checkbox"  class="checkbox" id="left" name="posLeft" value='1'  />
        <label for="left">{$vsLang->getWords('menu_form_left',"Left")}</label>
    </td>
    <td colspan="2" align="center" class="ui-dialog-buttonpanel">{$switchForm} &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input class="ui-state-default ui-corner-all" type="submit" name="submit" value="{$form['submit']}" /></td>
</tr>
<if="$menu->getBackup()">
	<tr>
	    <td> {$vsLang->getWords('menu_form_backup',"Retore Link")}</td>
	    <td>
	        <input type="checkbox" class="checkbox" id="menuRetore" name="menuRetore" value="1" />
	        &nbsp; <b>Current link</b>: {$menu->getUrl()}
	    </td>
	    <td  colspan="2">
	       <b>Real link</b>: <span style="color:red">{$menu->getBackup()}</span>
	    </td>
    </tr>
    </if>
<tr>
	<tr>
		        			<td >{$vsLang->getWords('menu_image',"Hình ảnh")}</td>
							<td>
								<table>
								<tr>
									<td>{$vsLang->getWords('obj_image_link', "Link")}:<input onclick="checkedLinkFile($('#link-text').val());" onclicktext="checkedLinkFile($('#link-text').val());" type="radio" id="link-text" name="link-file" value="link" /></td>
									<td><input size="39" type="text" name="txtlink" id="txtlink"/></td>
								</tr>
								<tr>
									<td>{$vsLang->getWords('obj_image_file', "File")}:<input onclick="checkedLinkFile($('#link-file').val());" onclicktext="checkedLinkFile($('#link-file').val());" type="radio" id="link-file" name="link-file" value="file" checked="checked"/></td>
									<td id="pageImage">
									<div style="width: 180px; height: 18px; border: solid 1px #7FAAFF; background-color: #C5D9FF; padding: 2px;">
										<span id="spanButtonPlaceholder"></span>
									</div>
									<div id="fsUploadProgress"></div>
									
								        	<script>
												$(window).ready(function() {
													var swfu;
													swfu = new SWFUpload(vsf.uploadSWF($max_upload_size,['editPageForm','spanButtonPlaceholder','fsUploadProgress',''],'menus','menus',["{$vsSettings->getSystemKey('global_file_image_extend',"*.jpg;*.png;*.gif","global",  0, 1)}","Images"]));
													
												});
											</script>
									</td>
								</tr>
								</table>
							</td>
							<td>
								<if="$menu->getFileId()">
									<div id="td-obj-image">{$menu->createImageCache($menu->getFileId(),100,100)}</div>
								</if>
							</td>
						</tr>
</tr>
</table>
</form>
</div>
<div class="clear"></div>
<script>
	function checkedLinkFile(value){
		if(value=='link'){
			$("#txtlink").removeAttr('disabled');
			$("#pageImage").css('visibility','hidden');
		}else{
			$("#txtlink").attr('disabled', 'disabled');
			$("#pageImage").css('visibility','visible');
		}
	}
	function setValue_{$bw->typemenu}(id) {
		$('#'+id).val($('#slmenu_{$bw->typemenu}').val());
	}
	$(window).ready(function() {
		checkedLinkFile('file');
		vsf.jRadio('{$menu->getStatus()}','menuStatus');
		vsf.jRadio('{$menu->getType()}','menuType');
		vsf.jRadio('{$menu->getIsDropdown()}','menuIsDropdown');
		vsf.jRadio('{$menu->getIsLink()}','menuIsLink');
		vsf.jCheckbox('{$menu->main}','main');
		vsf.jCheckbox('{$menu->top}','top');
		vsf.jCheckbox('{$menu->right}','right');
		vsf.jCheckbox('{$menu->left}','left');
		vsf.jCheckbox('{$menu->bottom}','bottom');
	});
	$('#addEditMenu_{$menu->isAdmin}').submit(function(){
		if(!$("#menuTitle{$menu->isAdmin}").val()){
			vsf.alert("{$vsLang->getWords('null_title', 'Tiêu đề không được để trống!!!')}");
			return false;
		}
		vsf.submitForm($('#addEditMenu_{$menu->isAdmin}'), '{$bw->input[0]}/addeditmenu',"addeditform_{$menu->isAdmin}");
vsf.get('menus/getmenulist/{$menu->isAdmin}','menulist_{$menu->isAdmin}');
return false;
	});
</script>
EOF;
		//--endhtml--//
		

		return $BWHTML;
	}
	
	function objList($menulist = "", $bt_buildCache = false, $message = "") {
		global $vsLang, $bw;
		$BWHTML = "";
		//--starthtml--//
		

		if ($bt_buildCache)
			$buildCache = <<<EOF
<a href="#" onclick="vsf.get('menus/buildcache/','menulist_{$bw->typemenu}'); return false;" title="{$vsLang->getWords('menu_form_build_cache',"Build cache")}">
    <img src="{$bw->vars['img_url']}/cache.png" />
</a>
EOF;
		
		$BWHTML .= <<<EOF
<script type="text/javascript">

function deleteMenu_{$bw->typemenu}() {
	if($('#slmenu_{$bw->typemenu}').val() > 0) {
		jConfirm(
			'{$vsLang->getWords("pages_deleteConfirm","Are you sure to delete these page information?")}', 
			'{$bw->vars['global_websitename']} Dialog', 
			function(r){
				if(r){
					vsf.get('menus/deletemenu/'+$('#slmenu_{$bw->typemenu}').val()+'/','menulist_{$bw->typemenu}');
				}
			}
		);
	}
	else {
		jAlert(
			"{$vsLang->getWords('menu_select_to_delete',"Please select a menu to delete!")}",
			"{$bw->vars['global_websitename']} Dialog"
		);
		return false;
	}
}

function editMenu_{$bw->typemenu}() {
	if($('#slmenu_{$bw->typemenu}').val() > 0) {
		vsf.get('menus/editmenu/'+$('#slmenu_{$bw->typemenu}').val()+'/','addeditform_{$bw->typemenu}');
	}
	else {
		jAlert(
			"{$vsLang->getWords('menu_select_to_edit',"Please select a menu to edit!")}",
			"{$bw->vars['global_websitename']} Dialog"
		);
		return false;
	}
}
</script>
<div class="ui-dialog ui-widget ui-widget-content ui-corner-all2">
	<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
    	<span class="ui-dialog-title">{$vsLang->getWords('menu_form_menulist','Menu list')}</span>
    </div>
    <div class="error">{$message}</div>
    <table cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td class="ui-dialog-buttonpanel">
            	<a href="#" onclick="deleteMenu_{$bw->typemenu}(); return false;" title="{$vsLang->getWords('menu_form_delete',"Delete")}">
                	<img src="{$bw->vars['img_url']}/del.png" />
                </a>
                <a href="#" onclick="editMenu_{$bw->typemenu}(); return false;" title="{$vsLang->getWords('menu_form_edit',"Edit")}">
                	<img src="{$bw->vars['img_url']}/edit.png" />
                </a>
{$buildCache}
            </td>
        </tr>
        <tr align="center">
        	<td class="ui-dialog-selectpanel">
            <select multiple="multiple" style="width:281px" onchange="setValue_{$bw->typemenu}('parentId_{$bw->typemenu}');" id="slmenu_{$bw->typemenu}" size="20">{$menulist}</select>
            </td>
		</tr>
    </table>
</div>
<div class="clear"></div>
EOF;
		//--endhtml--//
		

		return $BWHTML;
	}
	
	function objMain($menulist, $addeditform) {
		global $bw;
		$BWHTML = "";
		//--starthtml--//
		

		$BWHTML .= <<<EOF
<div id="menulist_{$bw->typemenu}" class="left-cell">{$menulist}</div>
<div id="addeditform_{$bw->typemenu}" class="right-cell">{$addeditform}</div>
<div class="clear"></div>
EOF;
		//--endhtml--//
		

		return $BWHTML;
	}
	
	function getSimpleListCatHtml($data, $categoryGroup) {
		global $vsLang, $bw;
		$temp = "";
		if ($bw->input [0] == "reals")
			$temp = $bw->input [0] . "_";
		$BWHTML = "";
		$BWHTML .= <<<EOF
			<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
				<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
				    <span class="ui-dialog-title">{$vsLang->getWords($temp.'category_table_title_header','Categories')}</span>
				</div>
				<div id="category-message{$categoryGroup->getUrl()}">{$data['message']}{$vsLang->getWords($temp.'category_chosen',"Selected categories")}: {$vsLang->getWords('category_not_selected',"None")}</div>
				<table width="100%" class="ui-dialog-content ui-widget-content" cellpadding="0" cellspacing="0">
				    <tr>
				        <td width="200">
							{$data['html']}
				        </td>
				    </tr>
				</table>
			</div>
		<script type="text/javascript">
			function setValue_category{$categoryGroup->getUrl()}() {
				var currentId = '';
				var parentId = '';
				$("#category-{$categoryGroup->getUrl()} option:selected").each(function () {
				    currentId += $(this).val() + ',';
				    parentId = $(this).val();
				});
				
				currentId = currentId.substr(0, currentId.length-1);
				$("#category-message{$categoryGroup->getUrl()}").html('{$vsLang->getWords('category_chosen',"Selected categories")}:'+currentId);
				$('#category-parent-id-{$categoryGroup->getUrl()}').val(parentId);
			}
		</script>
EOF;
	}
	
	function categoryList($data, $categoryGroup) {
		global $vsLang, $bw, $vsSettings;
		$temp = "";
		if ($bw->input [2] == "areas")
			$temp = $bw->input [2] . "_";
		$BWHTML = "";
		$BWHTML .= <<<EOF
			<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
				<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
				    <span class="ui-dialog-title">{$vsLang->getWords($temp.'category_table_title_header','Categories')}</span>
				</div>
				<div id="category-message{$categoryGroup->getUrl()}">{$data['message']}{$vsLang->getWords($temp.'category_chosen',"Selected categories")}: {$vsLang->getWords('category_not_selected',"None")}</div>
				<table width="100%" class="ui-dialog-content ui-widget-content" cellpadding="0" cellspacing="0">
				    <tr>
				        <td width="200">
							{$data['html']}
				        </td>
				        <td id="second" class="second" style="text-align:center">
				        <if="$vsSettings->getSystemKey($categoryGroup->getUrl().'_cate_edit',1,$categoryGroup->getUrl(),1,1)">
					        <input style="width:50px;margin-bottom:20px" type="button"   class="ui-state-default ui-corner-all" onclick="editCategory{$categoryGroup->getUrl()}()" value="{$vsLang->getWords('category_edit_bt',"Edit")}">
					    </if>
					        <input style="width:50px" type="button"  class="ui-state-default ui-corner-all" onclick="deleteCategory{$categoryGroup->getUrl()}()" value="{$vsLang->getWords('category_delete_bt',"Delete")}">
				        </td>
				    </tr>
				</table>
			</div>
			<script type="text/javascript">
			function setValue_category{$categoryGroup->getUrl()}() {
						var currentId = '';
						var parentId = '';
						$("#menus-category{$categoryGroup->getUrl()} option:selected").each(function () {
						    currentId += $(this).val() + ',';
						    parentId = $(this).val();
						});
						currentId = currentId.substr(0, currentId.length-1);
						$("#category-message{$categoryGroup->getUrl()}").html('{$vsLang->getWords('category_chosen',"Selected categories")}:'+currentId);
						$('#category-parent-id-{$categoryGroup->getUrl()}').val(parentId);
					}
				
			function deleteCategory{$categoryGroup->getUrl()}() {
					jConfirm(
						'{$vsLang->getWords("category_confirm_delete","Are you sure to delete these categories information?")}', 
					 	'{$bw->vars["global_websitename"]} Dialog',
					 	function(r){
					 	if(r){
					 		var currentId = '';
							$("#menus-category{$categoryGroup->getUrl()} option:selected").each(function () {
						        currentId += $(this).val() + ',';
						    });
						    currentId = currentId.substr(0, currentId.length-1);
							if(currentId==0) {
								$('#category-message{$categoryGroup->getUrl()}').html('{$vsLang->getWords('err_chosen_category', 'Please choose category to perform your action!')}');
								$('#menus-category{$categoryGroup->getUrl()}').addClass('ui-state-error');
								return false;
							}
							vsf.get('menus/delete-category/{$categoryGroup->getUrl()}/'+currentId+'/','category-table{$categoryGroup->getUrl()}');
							vsf.get('menus/display-category-tab/{$categoryGroup->getUrl()}/','categoryTabContainer{$categoryGroup->getUrl()}');
							}
							return false;
					});
				}
				
				function editCategory{$categoryGroup->getUrl()}() {
					temp = $("#menus-category{$categoryGroup->getUrl()} option:selected");
					currentId = $(temp[0]).val();
					if(currentId==0) {
						$('#category-message{$categoryGroup->getUrl()}').html('{$vsLang->getWords('err_chosen_category', 'Please choose category to perform your action!')}');
						$('#menus-category{$categoryGroup->getUrl()}').addClass('ui-state-error');
						return false;
					}

					vsf.get('menus/edit-category/{$categoryGroup->getUrl()}/'+currentId+'/','category-form{$categoryGroup->getUrl()}');
					return false;
				}
				</script>
EOF;
	}
	
	function MainCategories($categoryForm = "", $categoryTable = "", $str = "") {
		global $vsLang, $bw,$vsSettings;
		
		$BWHTML = "";
		$BWHTML .= <<<EOF
			<div id='categoryTabContainer{$str}'>
				<div class="left-cell" id="category-table{$str}">{$categoryTable}</div>
				<div class="right-cell" id="category-form{$str}">
				<if="$vsSettings->getSystemKey($str.'_cate_add_form',1,$str,1,1)">
					{$categoryForm}
				</if>
			</div>
				<div class="clear"></div>
			</div>
EOF;
	}
	
	function addEditCategoryForm($category, $option) {
		global $vsLang, $bw, $vsUser,$vsStd,$vsSettings;
		
		$max_upload_size = min($vsStd->let_to_num(ini_get('post_max_size')), $vsStd->let_to_num(ini_get('upload_max_filesize')));
		
		if (! $category->getId ()) {
			$category->status = 1;
			$category->isDropdown = 0;
		} else
			$switchForm = <<<EOF
<input class="ui-state-default ui-corner-all"  type="button" value="{$vsLang->getWords('menu_bt_switch_add',"New Category")}" name="switch" onclick="vsf.get('menus/edit-category/{$option['cate']}/','category-form{$option['cate']}');" />
EOF;
		
		$checkStatus [$category->status] = "checked";
		$checkDropdown [$category->isDropdown] = "checked";
		$pTop = $category->top ? 'checked ' : '';
		$pRight = $category->right ? 'checked ' : '';
		
		$BWHTML .= <<<EOF
			<form id="add-edit-category-form{$option['cate']}" method="post" name='add-edit-category-form{$option['cate']}'>
			<if="!$bw->vars[$option['cate'].'_url']">
			<input type="hidden" name="categoryGroup" value="{$bw->input[2]}" />
			</if>
			<input type="hidden" name="categoryId" value="{$category->getId()}" />
			<input type="hidden" id="category-parent-id-{$option['cate']}" name="categoryParentId" value="{$category->getParentId()}" />
				<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
					<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
				    	<span class="ui-dialog-title">{$option['formTitle']}</span>
				    </div>
				    <div id="err-category-form-message{$option['cate']}" class="red">{$option['message']}</div>
				    <table class="ui-dialog-content ui-widget-content" cellpadding="0" cellspacing="0" width="100%">
				    	<tr>
				    		<if="$bw->vars[$option['cate'].'_cate_title']">
				        	<td>{$vsLang->getWords('category_form_header_name','Name')}</td>
				            <td><input id="category-name{$option['cate']}" type="text" name="categoryName" size="36" value="{$category->getTitle()}" /></td>
				            </if>
				            <if="$bw->vars[$option['cate'].'_cate_status']">
				        	<td>{$vsLang->getWords('category_form_header_status','Status')}</td>
				            <td>
				            	<input type="radio" class="checkbox" name="categoryIsVisible" {$checkStatus[1]} value="1"/> {$vsLang->getWords('global_yes','Yes')}
				            	<input type="radio" class="checkbox"  name="categoryIsVisible" {$checkStatus[0]} value="0"/> {$vsLang->getWords('global_no','No')}
				            	<if="$bw->vars[$option['cate'].'_cate_status_home']">
				            		<input type="radio" class="checkbox"  name="categoryIsVisible" {$checkStatus[2]} value="2"/> {$vsLang->getWords('global_home','Trang chủ')}
				            	</if>
				            	<if="$bw->vars[$option['cate'].'_cate_status_special']">
					            	<input type="checkbox" class="checkbox"  name="posTop" $pTop value="2"/> Tour home
					            	<input type="checkbox" class="checkbox"  name="posRight" $pRight value="3"/> Hotel home
				            	</if>
				            </td>
				            </if>
						</tr>
						<if="$bw->vars[$option['cate'].'_cate_value']">
						<tr>
				    		
				        	<td>{$vsLang->getWords("category_{$option['cate']}_value",'Value')}</td>
				            <td><input id="category-value{$option['cate']}" type="text" name="categoryValue" size="36" value="{$category->getIsLink()}" /></td>
				            
						</tr>
						</if>
						<if="$bw->vars[$option['cate'].'_cate_dropdown']">
						<tr>
				            <td>{$vsLang->getWords('category_form_header_dropdown','Is dropdown')}</td>
				            <td>
				            	<input type="radio" class="checkbox" name="categoryIsDropdown" {$checkDropdown[1]} value="1"/> {$vsLang->getWords('global_yes','Yes')}
				            	<input type="radio" class="checkbox"  name="categoryIsDropdown" {$checkDropdown[0]} value="0"/> {$vsLang->getWords('global_no','No')}
				            </td>
				            
						</tr>
						</if>
						<if="$bw->vars[$option['cate'].'_cate_url']">
						<tr>
						    <td>{$vsLang->getWords('category_form_header_url','Group')}</td>
				            <td><input type="text" name="categoryGroup" size="10" value="{$category->getUrl()}" /></td>
						</tr>
						</if>
						<if="$bw->vars[$option['cate'].'_cate_index']">
						<tr>
						    <td>{$vsLang->getWords('category_form_header_index','Index')}</td>
				            <td><input type="text" name="categoryIndex" size="10" value="{$category->getIndex()}" /></td>
						</tr>
						</if>
						<if="$bw->vars[$option['cate'].'_cate_file']">
						<tr>
		        			<td >{$vsLang->getWords('menu_image',"Hình ảnh")}</td>
							<td>
								<table>
								<tr>
									<td>{$vsLang->getWords('obj_image_link', "Link")}:<input onclick="checkedLinkFile($('#link-text').val());" onclicktext="checkedLinkFile($('#link-text').val());" type="radio" id="link-text" name="link-file" value="link" /></td>
									<td><input size="39" type="text" name="txtlink" id="txtlink"/></td>
								</tr>
								<tr>
									<td>{$vsLang->getWords('obj_image_file', "File")}:<input onclick="checkedLinkFile($('#link-file').val());" onclicktext="checkedLinkFile($('#link-file').val());" type="radio" id="link-file" name="link-file" value="file" checked="checked"/></td>
									<td id="pageImage">
									<div style="width: 180px; height: 18px; border: solid 1px #7FAAFF; background-color: #C5D9FF; padding: 2px;">
										<span id="spanButtonPlaceholder"></span>
									</div>
									<div id="fsUploadProgress"></div>
									
								        	<script>
												$(window).ready(function() {
													var swfu;
													swfu = new SWFUpload(vsf.uploadSWF($max_upload_size,['add-edit-category-form{$option['cate']}','spanButtonPlaceholder','fsUploadProgress',''],'menus','{$option['cate']}',["{$vsSettings->getSystemKey('global_file_image_extend',"*.jpg;*.png;*.gif","global",  0, 1)}","Images"]));
													
												});
											</script>
									</td>
								</tr>
								</table>
							</td>
							<td>
								<if="$category->getFileId()">
									<div id="td-obj-image">{$category->createImageCache($category->getFileId(),100,100)}</div>
								</if>
							</td>
						</tr>
						</if>
						<if=" $bw->vars[$option['cate'].'_cate_gallery'] && $category->getId()">
							<tr>
							<td></td>
									<td>
									<a href="javascript:;" onclick="vsf.popupGet('gallerys/display-album-tab/menus/{$category->getId()}&albumCode=image','files')" class="ui-state-default ui-corner-all ui-state-focus">
										{$vsLang->getWords('menus_gallery','Album ảnh')}
									</a>
									</td>
							</tr>
						</if>
						<if="$category->getBackup()&&$vsUser->checkRoot()">
						<tr>
						    <td> {$vsLang->getWords('menu_form_backup',"Retore Link")}</td>
						    <td>
						        <input type="checkbox" class="checkbox" id="menuRetore" name="menuRetore" value="1" />
						        &nbsp; <b>Current link</b>: {$category->getUrl()}
						    </td>
						    <td  colspan="2">
						      <b>Real link</b>: <span style="color:red">{$category->getBackup()}</span>
						    </td>
					    </tr>
					    </if>
					    <tr>
					    	<if="$bw->vars[$option['cate'].'_cate_intro']">
				        	<td>{$vsLang->getWords('category_form_header_desc','Description')}</td>
				        	
				            <td colspan="4"><if="$bw->vars[$option['cate'].'_cate_intro_editor']">
									{$category->getAlt()}
									<else />
									
									<textarea id="category-desc" style="width:240px;" name="categoryDesc">{$category->getAlt()}</textarea>
									
								</if>
								</td>
				            </if>
				            </tr>
					    
				        <tr>
				        	<td class="ui-dialog-buttonpanel" colspan="4" align="center">
				        		<input type="button"  class="ui-state-default ui-corner-all" onclick="submitCatForm{$option['cate']}()" value="{$option['formSubmit']}" /> {$switchForm}
			        		</td>
			        		
						</tr>
				    </table>
				</div>
			</form>
			
			<script type="text/javascript">
				function checkedLinkFile(value){
					if(value=='link'){
						$("#txtlink").removeAttr('disabled');
						$("#pageImage").css('visibility','hidden');
					}else{
						$("#txtlink").attr('disabled', 'disabled');
						$("#pageImage").css('visibility','visible');
					}
				}
				function displayDialog(pageId){
					var opacityDiv='<div id="opacity"></div>';
					var containerDiv='<div id="container"></div><div class="clear"></div>';
					$('#vsf-wrapper').append(opacityDiv);
					$('#vsf-wrapper').append(containerDiv);
					vsf.get('pages/displayEditPageTabber/'+pageId,'container');
				}
			
				function submitCatForm{$option['cate']}() {
					if(!$('#category-name{$option['cate']}').val()) {
						str = '* {$vsLang->getWords('err_category_name_blank','Please enter the category name!')}<br />';
						$('#err-category-form-message{$option['cate']}').html(str);
						$('#category-name{$option['cate']}').addClass('ui-state-error');
						return false;
					}
					vsf.submitForm($('#add-edit-category-form{$option['cate']}'), '{$bw->input[0]}/add-edit-category',"categoryTabContainer{$option['cate']}");				
					return false;
				}
				checkedLinkFile('file');
			</script>
EOF;
	}
	
	function displayDialog($menulisthtml) {
		global $vsMenu, $vsLang;
		
		$BWHTML .= <<<EOF
			<script type='text/javascript'>
				$(document).ready(
						function(){
					$('#dialogOpen').attr('style','width: 100% !important;');
					$('#dialogOpen').click(function(){
						var groupTitle = $('#dialogOpen :selected').text();
						var pageCatId =  $('#dialogOpen :selected').val();
				
	//					if($('#subTitle').length){
						    var advanceTitle = "{$vsLang->getWords('selected_item ','Đang chọn: ')}";		
							advanceTitle += groupTitle;
							var subTitle =  '[' + advanceTitle + ']';
						
							$('#dialog-subtitle').html(subTitle);
	//					}
	//					vsf.get('menus/get-items/'+pageCatId+'/','list-item');
	return true;
					});
				});
			</script>
			<div class="ui-dialog ui-widget ui-widget-content ui-corner-all" style="height:332px;">
				<div id="boxTree" style="float:left;padding-right:5px"><select multiple='multiple' id='dialogOpen' size='20'>{$menulisthtml}</select></div>
				<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner" style="float:left; width: 620px;">
			    	<span class="ui-dialog-title">{$vsLang->getWords('list_item_title','Danh sách')}</span>
			    	<span class="ui-dialog-title" id="dialog-subtitle" style="float:right; padding-right:3px;"></span>
			    	<div id="list-item"></div>
			    </div>
			   <div id="subBoxTree"></div>
			</div>
			
EOF;
	}
}
?>