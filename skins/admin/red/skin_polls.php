<?php
class skin_polls {
	function objListHtml($objItems = array(), $option = array()) {
		global $bw, $vsLang,$vsSettings;
		$BWHTML .= <<<EOF
				<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
				    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
				        <span class="ui-icon ui-icon-note"></span>
				        <span class="ui-dialog-title">{$vsLang->getWords('obj_objListHtmlTitle',"{$bw->input[0]} Item List")}</span>
				    </div>
					<table cellspacing="1" cellpadding="1" id='objListHtmlTable' width="100%">
						<thead>
						    <tr>
						        <th>{$vsLang->getWords('obj_list_title', 'Title')}</td>
						        <th width="110" style="text-align:center">{$vsLang->getWords('obj_list_poll', 'Lượt bình chọn')}</th>
						        <th width="65" style="text-align:center">{$vsLang->getWords('obj_list_rate', 'Tỷ lệ')}</th>
						    </tr>
						</thead>
						<tbody>
							<foreach="$objItems as $obj">	
								<php>
									 $this->PercentClick = number_format(($obj->getClick() / $option['totalClick'])*100,1,".","");
									 
								</php>							
								<tr class="$class">
									<td>{$obj->getTitle()}</td>
									<td style="text-align:center">{$obj->getClick()} phiếu</td>
									<td style="text-align:center"><div style=" top: 15px; float: left; width:{$this->PercentClick}%" id="sa_{$obj->getId()}">&nbsp;</div><div style="float:left">&nbsp;$this->PercentClick%</div></td>
								</tr>
								<script language="Javascript">
										$(document).ready(function(){
											col =new Array("#00ff00","#ff0000","#ffff00","#0000ff","#ff00ff","#00ffff","#000000","#00cc00","#cc0000","#cccc00","#0000cc","#cc00cc","#00cccc","#cccccc")
											$("#sa_{$obj->getId()}").css("background-color",col[$vsf_count])
										})
				
								</script> 
							</foreach>
								<tr>
									<td width="300" align="right" color="red" >Tổng lượt bình chọn : </td>
									<td style="text-align:center">{$option['totalClick']} phiếu</td>
								</tr>
						</tbody>
						<tfoot>
							<tr>
								<th colspan='5'>
									<div style='float:right;'>{$option['paging']}</div>
								</th>
							</tr>
						</tfoot>
					</table>
				</div>
EOF;
	}

	function addEditObjForm($objItem, $option = array()) {
		global $vsLang, $bw,$vsSettings;
		
		$BWHTML .= <<<EOF
			<div id="error-message" name="error-message"></div>
			<form id='add-edit-obj-form' name="add-edit-obj-form" method="POST"  enctype='multipart/form-data'>
				<input type="hidden" id="obj-cat-id" name="pollCatId" value="{$option['categoryId']}" />
				<input type="hidden" name="pollId" value="{$objItem->getId()}" />
				<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
					<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
						<span class="ui-dialog-title">{$option['formTitle']}</span>
					</div>
					<table class="ui-dialog-content ui-widget-content">
						<tr>
							<if="$vsSettings->getSystemKey($bw->input[0].'_title',1)">
							<td class="label_obj">{$vsLang->getWords('obj_title', 'Title')}:</td>
							<td><input size="55" type="text" name="pollTitle" value="{$objItem->getTitle()}" id="obj-title"/></td>
							</if>
							<if="$vsSettings->getSystemKey($bw->input[0].'_image',1)">
							<td align='left' rowspan="4">
								<div style="border: 1px solid ;"id="td-obj-image">{$objItem->createImageCache($objItem->getImage(),178,100)}</div>
							</td>
							</if>
						</tr>
						<tr >
							<if="$vsSettings->getSystemKey($bw->input[0].'_status',1)">
							<td class="label_obj">{$vsLang->getWords('obj_Status', 'Active')}:</td>
							<td><input class='c_noneWidth' type="checkbox" name="pollStatus" {$objItem->getStatus()} style='margin-right:10px;'/></td>
							</if>
						</tr>
						<tr >
							<if="$vsSettings->getSystemKey($bw->input[0].'_index',1)">
							<td class="label_obj">{$vsLang->getWords('obj_Index', 'Index')}:</td>
							<td><input size="4" class="numric" type="text" name="pollIndex" value="{$objItem->getIndex()}" id="obj-Index"/></td>
							</if>
						</tr>
						<if="$vsSettings->getSystemKey($bw->input[0].'_intro',1)">
						<tr>
							<td class="label_obj">{$vsLang->getWords('obj_Intro', 'Intro')}:</td>
							<td colspan="2">{$objItem->getIntro()}</td>
						</tr>
						</if>
						<if="$vsSettings->getSystemKey($bw->input[0].'_content',1)">
						<tr>
							<td colspan="3" align="center">{$objItem->getContent()}</td>
						</tr>
						</if>
						<tr>
							<td class="ui-dialog-buttonpanel" colspan="3" align="center">
								<input type="submit" name="submit" value="{$option['formSubmit']}" />
							</td>
						</tr>
					</table>
				</div>
			</form>
			<script language="javascript">
				function alertError(message){
					jAlert(
						message,
						'{$bw->vars['global_websitename']} Dialog'
					);
				}
				$('#add-edit-obj-form').submit(function(){
					var flag  = true;
					var error = "";
					var categoryId;
					var count=0;
					
					$("#obj-answer option:selected").each(function () {
						categoryId = $(this).val();
						count=1;
					});
					$('#obj-cat-id').val(categoryId);
					
					
					if(($('#obj-answer').val()==null || $('#obj-answer').val()==0 && !count)){
						error = "<li>{$vsLang->getWords('not_select_answer', 'Vui lòng chọn câu hỏi!!!')}</li>";
						flag  = false;
					}
					
					var title = $("#obj-title").val();
					if(title == null || title == ""){
						error += "<li>{$vsLang->getWords('null_title', 'Tiêu đề không được để trống!!!')}</li>";
						flag  = false;
					}
					
					if(!flag){
						error = "<ul class='ul-popu'>" + error + "</ul>";
						alertError(error);
						return false;
					}
					
					vsf.uploadFile("add-edit-obj-form", "{$bw->input[0]}", "add-edit-obj-process", "answer-panel","polls");
					return false;
				});
				$(document).ready(function(){					
					$("input.numric").numeric();
					vsf.jSelect('{$objItem->getCatId()}','obj-answer');
				});
			</script>
EOF;
	}

	function categoryList($categoryGroup = array()) {
		global $vsLang, $bw;
		$BWHTML .= <<<EOF
			<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
				<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
					<span class="ui-icon ui-icon-triangle-1-e"></span>
					<span class="ui-dialog-title">{$vsLang->getWords('category_table_title_header','Categories')}</span>
				</div>
				<table width="100%" cellpadding="0" cellspacing="1">
					<tr>
				    	<th id="obj-category-message" colspan="2">{$data['message']}{$vsLang->getWords('category_chosen',"Selected categories")}: {$vsLang->getWords('category_not_selected',"None")}</th>
				    </tr>
				    <tr>
				        <td>
				       		<select size="18" style="width: 100%;height:100px" id="obj-category">
				        		<if="count($categoryGroup->getChildren())"
				        		<foreach="$categoryGroup->getChildren() as $oMenu">
				        		<option title="{$oMenu->getAlt()}" value="{$oMenu->id}">{$oMenu->title}</option>
				        		</foreach>
				        	</select>
				        </td>
				    	<td align="center">
				        	<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" id="view-obj-bt" title='{$vsLang->getWords('view_list_in_cat',"Click here to edit this {$bw->input[0]}")}'>{$vsLang->getWords('global_view_result','Xem kết quả')}</a>
				        </td>
					</tr>
				</table>
			</div>
			
			<script type="text/javascript">
				$('#view-obj-bt').click(function() {
					var categoryId = '';
					$("#obj-category option:selected").each(function () {
						categoryId = $(this).val();
					});
					if(!categoryId) {
						jAlert(
						'Vui lòng chọn câu hỏi.',
						'{$bw->vars['global_websitename']} Dialog'
					);
						return false;
					}
					vsf.get('{$bw->input[0]}/display-obj-list/'+categoryId+'/objListHtml','obj-panel');
				});
				
				
			</script>
EOF;
	}

	function displayObjTab($option) {
		global $bw,$vsSettings;
		$BWHTML .= <<<EOF
		<if="$vsSettings->getSystemKey($bw->input[0].'_Category_tab',1)">
	        <div class='left-cell' style="width:100%"><div id='category-panel'>{$option['categoryList']}</div></div>
			<input type="hidden" id="idCategory" name="idCategory" />
			<div id="obj-panel" style="width:100%" class="right-cell">{$option['objList']}</div>
			<div class="clear"></div>
			<else />
			<input type="hidden" id="idCategory" name="idCategory" />
			<div id="obj-panel" style="width:100%" class="right-cell">{$option['objList']}</div>
			<div class="clear"></div>
        </if>
			
EOF;
		return $BWHTML;
	}

	function managerObjHtml() {
		global $bw, $vsLang,$vsSettings;
		$BWHTML .= <<<EOF
			<div id="page_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all-top">
				<ul id="tabs_nav" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner">
			    	<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
			        	<a href="{$bw->base_url}{$bw->input[0]}/display-obj-tab/&ajax=1"><span>{$vsLang->getWords('tab_obj_objes',"{$bw->input[0]}")}</span></a>
			        </li>
					<li class="ui-state-default ui-corner-top">
			        	<a href="{$bw->base_url}menus/display-category-tab/{$bw->input[0]}/&ajax=1"><span>{$vsLang->getWords('tab_obj_categories','Categories')}</span></a>
			        </li>
			        <li class="ui-state-default ui-corner-top">
				        	<a href="{$bw->base_url}{$bw->input[0]}/display-answer-tab/&ajax=1"><span>{$vsLang->getWords('tab_obj_answer','Trả lời')}</span></a>
				        </li>
			        <if="$vsSettings->getSystemKey($bw->input[0].'_setting_tab',1)">
				        <li class="ui-state-default ui-corner-top">
				        	<a href="{$bw->base_url}systemsettings/display-setting-tab/{$bw->input[0]}/&ajax=1">
								<span>{$vsLang->getWords("tab_{$bw->input[0]}_SS",'System Settings')}</span>
							</a>
			        	</li>
		        	</if>	
				</ul>
			</div>
EOF;
		return $BWHTML;
	}

	function displayAnswer($categoryGroup,$answerList){
		global $bw,$vsLang;
		
		$BWHTML .= <<<EOF
			<div class='left-cell' id='category-panel' style="width:40%">
				<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
				<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
					<span class="ui-icon ui-icon-triangle-1-e"></span>
					<span class="ui-dialog-title">{$vsLang->getWords('category_table_title_header','Categories')}</span>
				</div>
				<table width="100%" cellpadding="0" cellspacing="1">
					<tr>
				    	<th id="obj-answer-message" colspan="2">{$vsLang->getWords('category_chosen',"Selected categories")}: {$vsLang->getWords('category_not_selected',"None")}</th>
				    </tr>
				    <tr>
				        <td width="320">
				        	<select size="20" style="width: 100%;height:100px" id="obj-answer">
				        		<if="count($categoryGroup->getChildren())"
				        		<foreach="$categoryGroup->getChildren() as $oMenu">
				        		<option title="{$oMenu->getAlt()}" value="{$oMenu->id}">{$oMenu->title}</option>
				        		</foreach>
				        	</select>
				        </td>
				    	<td align="center">
				        	<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" id="view-answer-bt" title='{$vsLang->getWords('view_list_in_cat',"Click here to edit this {$bw->input[0]}")}'>{$vsLang->getWords('global_view','Xem')}</a>
				    		<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" id="add-answer-bt" title='{$vsLang->getWords('add_object_for_cat',"Click here to add this {$bw->input[0]}")}'>{$vsLang->getWords('global_add','Thêm')}</a>
				        </td>
					</tr>
				</table>
			</div>
			
			<script type="text/javascript">
				$('#view-answer-bt').click(function() {
					var categoryId = '';
					$("#obj-answer option:selected").each(function () {
						categoryId = $(this).val();
					});
					vsf.get('{$bw->input[0]}/display-obj-list/'+categoryId+'/answerListHtml','answer-panel');
				});
				
				$('#add-answer-bt').click(function(){
					var categoryId = '';
					$("#obj-answer option:selected").each(function () {
						categoryId=$(this).val();
					});
					
					$("#idCategory").val(categoryId);
				
					vsf.get('{$bw->input[0]}/add-edit-obj-form/', 'answer-panel');
				});
				
				var parentId = '';
				$('#obj-answer').change(function() {
					var currentId = '';
					var parentId = '';
					$("#obj-answer option:selected").each(function () {
						currentId += $(this).val() + ',';
						parentId = $(this).val();
					});
										
					currentId = currentId.substr(0, currentId.length-1);
					$("#obj-answer-message").html('{$vsLang->getWords('category_chosen',"Selected categories")}:'+currentId);
					$('#obj-cat-id').val(parentId);
				});
			</script>			
			</div>
			<input type="hidden" id="idCategory" name="idCategory" />
			<div id="answer-panel" class="right-cell" style="width:60%">{$answerList}</div>
			<div class="clear"></div>
EOF;
	}
	
	function answerListHtml($objItems = array(), $option = array()) {
		global $bw, $vsLang,$vsSettings;
		$BWHTML .= <<<EOF
			<div class="red">{$option['message']}</div>
			<form id="obj-list-form">
				<input type="hidden" name="checkedObj" id="checked-obj" value="" />
				<input type="hidden" name="categoryId" value="{$option['categoryId']}" id="categoryId" />
				<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
				    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
				        <span class="ui-icon ui-icon-note"></span>
				        <span class="ui-dialog-title">{$vsLang->getWords('obj_objListHtmlTitle',"{$bw->input[0]} Item List")}</span>
				    </div>
				    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">
				    	<li class="ui-state-default ui-corner-top" id="add-answer-bts"><a href="#" title="{$vsLang->getWords('add_obj_alt_bt',"Add {$bw->input[0]}")}">{$vsLang->getWords('add_obj_alt_bt',"Add {$bw->input[0]}")}</a></li>
				        <li class="ui-state-default ui-corner-top" id="hide-answer-bt"><a href="#" title="{$vsLang->getWords('hide_obj_alt_bt',"Hide selected {$bw->input[0]}")}">{$vsLang->getWords('hide_obj_bt','Hide')}</a></li>
				        <li class="ui-state-default ui-corner-top" id="visible-answer-bt"><a href="#" title="{$vsLang->getWords('visible_obj_alt_bt',"Visible selected {$bw->input[0]} ")}">{$vsLang->getWords('visible_obj_bt','Visible')}</a></li>
				        <li class="ui-state-default ui-corner-top" id="delete-answer-bt"><a href="#" title="{$vsLang->getWords('delete_obj_alt_bt',"Delete selected {$bw->input[0]}")}">{$vsLang->getWords('delete_obj_bt','Delete')}</a></li>
				    </ul>
					<table cellspacing="1" cellpadding="1" id='objListHtmlTable' width="100%">
						<thead>
						    <tr>
						        <th width="15"><input type="checkbox" onclick="checkAll()" onclicktext="checkAll()" name="all" /></th>
						        <th width="50">{$vsLang->getWords('obj_list_status', 'Active')}</th>
						        <th>{$vsLang->getWords('obj_list_title', 'Title')}</td>
						        <th width="60" style="text-align:center">{$vsLang->getWords('obj_list_index', 'Thứ tự')}</th>
						    </tr>
						</thead>
						<tbody>
							<foreach="$objItems as $obj">	
								<tr class="$class">
									<td align="center">
										<input type="checkbox" onclicktext="checkObject({$obj->getId()});" onclick="checkObject({$obj->getId()});" name="obj_{$obj->getId()}" value="{$obj->getId()}" class="myCheckbox" />
									</td>
									<td style='text-align:center'>{$obj->getStatus('image')}</td>
									<td>
										<a href="javascript:vsf.get('{$bw->input[0]}/add-edit-obj-form/{$obj->getId()}/','answer-panel')" title='{$vsLang->getWords('newsItem_EditObjTitle',"Click here to edit this {$bw->input[0]}")}' style='color:#CA59AA !important;' >{$obj->getTitle()}</a>
									</td>
									<td style="text-align:center">{$obj->getIndex()}</td>
								</tr>
								
							</foreach>
						</tbody>
						<tfoot>
							<tr>
								<th colspan='5'>
									<div style='float:right;'>{$option['paging']}</div>
								</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</form>
			<script type="text/javascript">
				function checkObject() {
					var checkedString = '';
					$("input[type=checkbox]").each(function(){
						if($(this).hasClass('myCheckbox')){
							if(this.checked) checkedString += $(this).val()+',';
						}
					});
					checkedString = checkedString.substr(0,checkedString.lastIndexOf(','));
					$('#checked-obj').val(checkedString);
					
				}
			
				function deleteObj(id, categoryId) {
					jConfirm(
						"{$vsLang->getWords('obj_delete_confirm', "Are you sure want to delete this {$bw->input[0]}?")}",
						"{$bw->vars['global_websitename']} Dialog",
						function(r) {
							if(r) {
								vsf.get('{$bw->input[0]}/delete-obj/'+id+'/','answer-panel');
								vsf.get('{$bw->input[0]}/display-obj-list/'+ categoryId +'/answerListHtml','answer-panel');
							}
						}
					);
					return false;
				}
				function checkAll() {
					var checked_status = $("input[name=all]:checked").length;
					var checkedString = '';
					$("input[type=checkbox]").each(function(){
						if($(this).hasClass('myCheckbox')){
						this.checked = checked_status;
						if(checked_status) checkedString += $(this).val()+',';
						}
					});
					$("span[acaica=myCheckbox]").each(function(){
						if(checked_status)
							this.style.backgroundPosition = "0 -50px";
						else this.style.backgroundPosition = "0 0";
					});
					$('#checked-obj').val(checkedString);
				}
				
				$('#add-answer-bts').click(function(){
					$("#obj-answer option:selected").each(function () {
						$("#idCategory").val($(this).val());
					});
					
					vsf.get('{$bw->input[0]}/add-edit-obj-form/','answer-panel');
				});
				
				$('#hide-answer-bt').click(function() {
					if($('#checked-obj').val()=='') {
						jAlert(
							"{$vsLang->getWords('hide_obj_confirm_noitem', "You haven't choose any items to hide!")}",
							"{$bw->vars['global_websitename']} Dialog"
						);
						return false;
					}
					var categoryId =0;
					$("#obj-answer option:selected").each(function () {
						categoryId = $(this).val();
					});
					vsf.submitForm($('#obj-list-form'),'{$bw->input[0]}/hide-checked-obj/','');
					vsf.get('{$bw->input[0]}/display-obj-list/'+ categoryId +'/answerListHtml','answer-panel');
				});
				
				$('#visible-answer-bt').click(function() {
					if($('#checked-obj').val()=='') {
						jAlert(
							"{$vsLang->getWords('visible_obj_confirm_noitem', "You haven't choose any items to visible!")}",
							"{$bw->vars['global_websitename']} Dialog"
						);
						return false;
					}
					var categoryId =0;
					$("#obj-answer option:selected").each(function () {
						categoryId = $(this).val();
					});
					vsf.submitForm($('#obj-list-form'),'{$bw->input[0]}/visible-checked-obj/','');
					vsf.get('{$bw->input[0]}/display-obj-list/'+categoryId+'/answerListHtml','answer-panel');
				});
				
				$('#delete-answer-bt').click(function() {
					if($('#checked-obj').val()=='') {
						jAlert(
							"{$vsLang->getWords('delete_obj_confirm_noitem', "You haven't choose any items to delete!")}",
							"{$bw->vars['global_websitename']} Dialog"
						);
						return false;
					}
					
					jConfirm(
						"{$vsLang->getWords('obj_delete_confirm', "Are you sure want to delete this {$bw->input[0]}?")}",
						"{$bw->vars['global_websitename']} Dialog",
						function(r) {
							if(r) {
							var categoryId ;
								$("#obj-answer option:selected").each(function () {
									categoryId = $(this).val();
								});
								vsf.submitForm($('#obj-list-form'),'{$bw->input[0]}/delete-checked-obj/','answer-panel');
								vsf.get('{$bw->input[0]}/display-obj-list/'+ categoryId +'/answerListHtml','answer-panel');
							}
						}
					);
				});
			</script>
EOF;
	}

}
?>