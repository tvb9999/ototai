<?php
class skin_advisorys {
	
	function replyadvisoryForm($option){
			global $vsLang;
			$BWHTML .= <<<EOF
				<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
					<form id="formReply" method="post">
						<input type="hidden" name="email" value="{$option['obj']->getEmail()}"/>
						<input type="hidden" name="name"  value="{$option['obj']->getName()}"/>
						<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
							<span class="ui-dialog-title">
								{$vsLang->getWords('advisoryReplyFormTitle','Reply Email')}
							</span>
					       	<span id="buttonSend" class="ui-dialog-title" style="float:right; margin-left: 10px; cursor: pointer;">
								{$vsLang->getWords('advisorys_replyForm_Send','Send Reply')}
							</span>
							
						</div>
						{$option['obj']->getIntro()}
					</form>
				</div>
				
				<script type='text/javascript'>					
					function sendReply(){
						$('#formReply').submit();
						$('#buttonClose').click();
					}
						
					$('#buttonSend').click(function(){
						$('#formReply').submit();
						$('#buttonClose').click();
					});
					
					$('#buttonClose').click(function(){
						$("#replyForm").html('');
					});

					$('#formReply').submit(function(){		
						vsf.submitForm($(this),'advisorys/replyProcess/{$option["obj"]->getId()}/{$option["obj"]->getType()}/','albumn-reply');
						return false;
					});
				</script>
EOF;
			return $BWHTML;
		}
	
	function objListHtml($objItems = array(), $option = array()) {
		global $bw, $vsLang, $vsSettings;
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
				    	<li class="ui-state-default ui-corner-top" id="add-objlist-bt"><a href="#" title="{$vsLang->getWords('add_obj_alt_bt',"Add {$bw->input[0]}")}">{$vsLang->getWords('add_obj_alt_bt',"Add {$bw->input[0]}")}</a></li>
				        <li class="ui-state-default ui-corner-top" id="hide-objlist-bt"><a href="#" title="{$vsLang->getWords('hide_obj_alt_bt',"Hide selected {$bw->input[0]}")}">{$vsLang->getWords('hide_obj_bt','Hide')}</a></li>
				        <li class="ui-state-default ui-corner-top" id="visible-objlist-bt"><a href="#" title="{$vsLang->getWords('visible_obj_alt_bt',"Visible selected {$bw->input[0]} ")}">{$vsLang->getWords('visible_obj_bt','Visible')}</a></li>
				        <li class="ui-state-default ui-corner-top" id="delete-objlist-bt"><a href="#" title="{$vsLang->getWords('delete_obj_alt_bt',"Delete selected {$bw->input[0]}")}">{$vsLang->getWords('delete_obj_bt','Delete')}</a></li>
				    </ul>
					<table cellspacing="1" cellpadding="1" id='objListHtmlTable' width="100%">
						<thead>
						    <tr>
						        <th width="15"><input type="checkbox" onclick="checkAll()" onclicktext="checkAll()" name="all" /></th>
						        <th width="50">{$vsLang->getWords('obj_list_status', 'Status')}</th>
						        <th>{$vsLang->getWords('obj_list_title', 'Title')}</td>
						        <th width="50">{$vsLang->getWords('obj_list_index', 'Index')}</th>
						        <th width="150">{$vsLang->getWords('obj_list_action', 'Option')}</th>
						    </tr>
						</thead>
						<tbody>
							<foreach="$objItems as $obj">
								<tr class="$vsf_class">
									<td align="center">
									<input type="checkbox" onclicktext="checkObject({$obj->getId()});" onclick="checkObject({$obj->getId()});" name="obj_{$obj->getId()}" value="{$obj->getId()}" class="myCheckbox" />
									</td>
									<td style='text-align:center'>{$obj->getStatus('image')}</td>
									<if=" $vsSettings->getSystemKey($bw->input[0].'_title', 1,$bw->input[0],1,1) ">
										<td>
											<a href="javascript:vsf.get('{$bw->input[0]}/add-edit-obj-form/{$obj->getId()}/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}','obj-panel')" title='{$vsLang->getWords('advisorysItem_EditObjTitle',"Click here to edit this {$bw->input[0]}")}' style='color:#CA59AA !important;' >
												{$obj->getTitle()}
											</a>
										</td>
									<else />
										<td>
											<a href="javascript:vsf.get('{$bw->input[0]}/add-edit-obj-form/{$obj->getId()}/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}','obj-panel')" title='{$vsLang->getWords('advisorysItem_EditObjTitle',"Click here to edit this {$bw->input[0]}")}' style='color:#CA59AA !important;' >
												{$obj->getIntro(100)}
											</a>
										</td>
									</if>
									<td>{$obj->getIndex()}</td>
									<td>
										<if=" $vsSettings->getSystemKey("{$bw->input[0]}_album", 0, $bw->input[0],1,1) ">
										<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:vsf.popupGet('gallerys/display-album-tab/advisory/{$obj->getId()}&albumCode=image','albumn')"  >
											{$vsLang->getWords('obj_album', 'Album')}
										</a>
										</if>
									</td>
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
			<div class="clear" id="file"></div>
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
					checkedString = checkedString.substr(0,checkedString.lastIndexOf(','));
					$('#checked-obj').val(checkedString);
				}
				
				
				$('#add-objlist-bt').click(function(){
					vsf.get('{$bw->input[0]}/add-edit-obj-form/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}','obj-panel');
				});
				
				$('#hide-objlist-bt').click(function() {
					if(checkVa())
						vsf.get('{$bw->input[0]}/hide-checked-obj/'+$('#checked-obj').val()+'/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}', 'obj-panel');
				});
				
				$('#visible-objlist-bt').click(function() {
				if(checkVa())
					vsf.get('{$bw->input[0]}/visible-checked-obj/'+$('#checked-obj').val()+'/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}', 'obj-panel');
				});
				
		
				
				$('#delete-objlist-bt').click(function() {
					if(checkVa())
						jConfirm(
							"{$vsLang->getWords('obj_delete_confirm', "Are you sure want to delete this {$bw->input[0]}?")}",
							"{$bw->vars['global_websitename']} Dialog",
							function(r) {
								if(r) {
								vsf.get('{$bw->input[0]}/delete-obj/'+$('#checked-obj').val()+'/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}','obj-panel');
								return false;
								}
							}
						);
				});
				function checkVa(){
			
					if(!$('#checked-obj').val()||$('#checked-obj').val()=="") {
							jAlert(
								"{$vsLang->getWords('obj_confirm_noitem', "You haven't choose any items !")}",
								"{$bw->vars['global_websitename']} Dialog"
							);
							return false;
						}
						return true;
				}
			</script>
EOF;
	}
	
	function addEditObjForm($objItem, $option = array()) {
		global $vsLang, $bw, $vsSettings;
		$BWHTML .= <<<EOF
			<div id="error-message" name="error-message"></div>
			<form id='add-edit-obj-form' name="add-edit-obj-form" method="POST" enctype='multipart/form-data'>
				<input type="hidden" id="obj-cat-id" name="advisoryCatId" value="{$option['categoryId']}" />
				<input type="hidden" id="pageCate" name="pageCate" value="{$bw->input['pageCate']}" />
				<input type="hidden" id="pageIndex" name="pageIndex" value="{$bw->input['pageIndex']}" />
				<input type="hidden" name="advisoryId" value="{$objItem->getId()}" />
				
				<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
					<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
					<span class="ui-icon ui-icon-note"></span>
						<span class="ui-dialog-title">{$option['formTitle']}</span>
						 <span id="closePageForm" class="closePage" title="{$vsLang->getWords('global_undo','Trở lại')}"></span>
					</div>
					<table class="ui-dialog-content ui-widget-content" style="width:100%;">
					<if=" $vsSettings->getSystemKey($bw->input[0].'_title', 1,$bw->input[0],1,1) ">
						<tr class='smalltitle'>
							<td class="submenu" style="float:left;width:67px">{$vsLang->getWords('obj_title', 'Title')}:</td>
							<td colspan="3">
								<input name="advisoryTitle" value="{$objItem->getTitle()}" id="obj-title" style="width:100%"/>
							</td>
						</tr>
					</if>
						<tr class='smalltitle'>
							<td class="submenu" style="width:67px;"> {$vsLang->getWords('obj_email', 'Email')}:</td>
							<td><input size="35" name="advisoryEmail" value="{$objItem->getEmail()}" id="obj-email"/></td>
							<td class="submenu" style="width:67px;"> {$vsLang->getWords('obj_address', 'Address')}:</td>
							<td><input size="35" name="advisoryAddress" value="{$objItem->getAddress()}" id="obj-email"/></td>
						</tr>
						<tr class='smalltitle'>
							<td class="submenu" style="width:30px;">{$vsLang->getWords('obj_Fullname', 'Fullname')}: </td>
							<td><input size="35" name="advisoryName" value="{$objItem->getName()}" /></td>
							<td class="submenu">{$vsLang->getWords('obj_Status', 'Status')}:</td>
							<td>
							{$vsLang->getWords('obj_status_hidden', 'Hide')}   		
							<input class='c_noneWidth' type="radio" name="advisoryStatus" id="advisoryStatus"  value="0" style='margin-right:3px;'/>
							
							{$vsLang->getWords('obj_status_show', 'Display')} 	
							<input class='c_noneWidth' type="radio" name="advisoryStatus" id="advisoryStatus" checked value="1" style='margin-right:3px;'/>
							
							{$vsLang->getWords('obj_status_special', 'Specical')}
							<input class='c_noneWidth' type="radio" name="advisoryStatus" id="advisoryStatus" checked value="2" style='margin-right:3px;'/>
							</td>
						</tr>
						<tr class='smalltitle'>
							<td class="submenu" style="width:30px;">{$vsLang->getWords('obj_Index', 'Index')}: </td>
							<td><input  size="15" name="advisoryIndex" value="{$objItem->getIndex()}" /></td>
							<if="$vsSettings->getSystemKey($bw->input[0].'_email_reply', 0,$bw->input[0],1,1) ">
							<td class="submenu">{$vsLang->getWords('obj_reply', 'Reply')}:</td>
							<td>
								<a href="javascript:vsf.popupGet('{$bw->input[0]}/reply/{$objItem->getId()}','albumn-reply',900,600)">
									{$vsLang->getWords('obj_reply', 'Reply')}
								</a>
							</td>
							<else />
							<td class="submenu" colspan="2">&nbsp;</td>
							</if>
						</tr>
						<tr class='smalltitle'>
							<td class="submenu" style="width:auto">
								{$vsLang->getWords('obj_Intro', 'Question')}:
							</td>
							<td colspan="3" valgin="center">
								{$objItem->getIntro()}
							</td>
						</tr>
						<tr class='smalltitle'>
							<td colspan="4" class="submenu" style="width:auto">
								{$vsLang->getWords('obj_Content', 'Answer')}:
							</td>
						</tr>
						<tr class='smalltitle'>
							<td colspan="4" align="center">{$objItem->getContent()}</td>
						</tr>
						<tr class='smalltitle'>
							<td class="ui-dialog-buttonpanel" colspan="4" align="center">
								<input type="submit" name="submit" value="{$option['formSubmit']}" />
							</td>
						</tr>
					</table>
				</div>
			</form>
			<script language="javascript">
				$(window).ready(function() {
					$("input.numeric").numeric();
					vsf.jRadio('{$objItem->getStatus()}','advisoryStatus');
					vsf.jSelect('{$objItem->getCatId()}','obj-category');
				});
				$('#add-edit-obj-form').submit(function(){
					var flag  = true;
					var error = "";
					var categoryId = "";
					var count=0;
					
					$("#obj-category option:selected").each(function () {
						categoryId += $(this).val() + ",";
					});
					categoryId = categoryId.slice(0, -1);
					$('#obj-cat-id').val(categoryId);
					

					if(!flag){
						error = "<ul class='ul-popu'>" + error + "</ul>";
						vsf.alert(error);
						return false;
					}
					vsf.submitForm($("#add-edit-obj-form"), "{$bw->input[0]}/add-edit-obj-process", "obj-panel");
					return false;
				});
				$('#closePageForm').click(function(){
					vsf.get('{$bw->input[0]}/display-obj-list/&pageIndex={$bw->input['pageIndex']}&pageCate={$bw->input['pageCate']}','obj-panel');
					return false;
				});
			</script>
EOF;
	}
	//image 125*125
	function categoryList($data = array()) {
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
				        <td width="220">
				        {$data['html']}
				        </td>
				    	<td align="center">
				    	<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" id="view-obj-bt" title='{$vsLang->getWords('advisorysItem_EditObjTitle',"Click here to edit this {$bw->input[0]}")}'>{$vsLang->getWords('global_view','Xem')}</a>
				    	<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" id="add-obj-bt" title='{$vsLang->getWords('advisorysItem_EditObjTitle',"Click here to add this {$bw->input[0]}")}'>{$vsLang->getWords('global_add','Thêm')}</a>
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
					vsf.get('{$bw->input[0]}/display-obj-list/'+categoryId+'/','obj-panel');
				});
				$('#add-obj-bt').click(function(){
					var categoryId = '';
					$("#obj-category option:selected").each(function () {
						categoryId=$(this).val();
					});
					$("#idCategory").val(categoryId);
					vsf.get('{$bw->input[0]}/add-edit-obj-form/', 'obj-panel');
				});
				var parentId = '';
				$('#obj-category').change(function() {
					var currentId = '';
					var parentId = '';
					$("#obj-category option:selected").each(function () {
						currentId += $(this).val() + ',';
						parentId = $(this).val();
					});
					currentId = currentId.substr(0, currentId.length-1);
					$("#obj-category-message").html('{$vsLang->getWords('category_chosen',"Selected categories")}:'+currentId);
					$('#obj-cat-id').val(parentId);
				});
			</script>
EOF;
	}

	function displayObjTab($option) {
		global $bw,$vsSettings;
		$BWHTML .= <<<EOF
		<if="$vsSettings->getSystemKey($bw->input[0].'_category_tab',1,$bw->input[0],1,1)">
	        <div class='left-cell'><div id='category-panel'>{$option['categoryList']}</div></div>
			<input type="hidden" id="idCategory" name="idCategory" />
			<div id="obj-panel" class="right-cell">{$option['objList']}</div>
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
			        <if="$vsSettings->getSystemKey($bw->input[0].'_category_tab',1,$bw->input[0],1,1)">
					<li class="ui-state-default ui-corner-top">
			        	<a href="{$bw->base_url}menus/display-category-tab/advisorys/&ajax=1"><span>{$vsLang->getWords('tab_obj_categories','Categories')}</span></a>
			        </li>
			        </if>
			        <if="$vsSettings->getSystemKey($bw->input[0].'_setting_tab',1,$bw->input[0],1,1)">
			        <li class="ui-state-default ui-corner-top">
        				<a href="{$bw->base_url}settings/moduleObjTab/{$bw->input[0]}/&ajax=1">
							{$vsLang->getWords('tab_advisorys_Setting','Settings')}
						</a>
        			</li>	
        			</if>
				</ul>
			</div>
EOF;
		return $BWHTML;
	}
}
?>