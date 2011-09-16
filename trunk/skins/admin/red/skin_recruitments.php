<?php
class skin_recruitment {
	function objListHtml($objItems = array(), $option = array()) {
		global $bw, $vsLang;

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
						        <th width="60">{$vsLang->getWords('obj_list_status', 'Active')}</th>
						        <th>{$vsLang->getWords('obj_list_title', 'Title')}</td>
						        <th width="30">{$vsLang->getWords('obj_list_index2', 'Thao tác')}</th>
						        <th width="100">{$vsLang->getWords('obj_list_action', 'Action')}</th>
						    </tr>
						</thead>
						<tbody>
							<foreach="$objItems as $obj">
								<tr class="$vsf_class">
									<td align="center">
									<input type="checkbox" onclicktext="checkObject({$obj->getId()});" onclick="checkObject({$obj->getId()});" name="obj_{$obj->getId()}" value="{$obj->getId()}" class="myCheckbox" />
									</td>
									<td style='text-align:center'>{$obj->getStatus('image')}</td>
									
									<td>
										<a href="javascript:vsf.get('{$bw->input[0]}/add-edit-obj-form/{$obj->getId()}/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}','obj-panel')" title='{$vsLang->getWords('recruitmentItem_EditObjTitle',"Click here to edit this {$bw->input[0]}")}' style='color:#CA59AA !important;' >
										{$obj->getTitle()}
										</a>
									</td>
									<td>{$obj->getIndex()}</td>
									<td>
										<a class="ui-state-default ui-corner-all ui-state-focus" onclick="deleteObj({$obj->getId()});" title='{$vsLang->getWords('newsItem_EditObjTitle',"Click here to delete this {$bw->input[0]}")}'>{$vsLang->getWords('global_del','Xóa')}</a>
                                                                                <!--<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:vsf.get('{$bw->input[0]}/add-edit-obj-form/{$obj->getId()}/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}','obj-panel')" title='{$vsLang->getWords('recruitmentItem_EditObjTitle',"Click here to edit this {$bw->input[0]}")}'>
											{$vsLang->getWords('global_edit','Sửa')}
										</a>-->
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
                     <table cellspacing="1" cellpadding="1" id="objListInfo" width="100%">
                     <tbody>
                          <tr align="left">
                            <span style="padding-left: 10px;line-height:16px;"><img src="{$bw->vars['img_url']}/enable.png" />{$vsLang->getWords('global_status_enable', 'Enable')}</span>
                            <span style="padding-left: 10px;line-height:16px;"><img src="{$bw->vars['img_url']}/disabled.png" /> {$vsLang->getWords('global_status_disabled', 'Disable')}</span>
                            <span style="padding-left: 10px;line-height:16px;"><img src="{$bw->vars['img_url']}/home.png" /> {$vsLang->getWords('global_status_ishome', 'Show on home page')}</span>
                           </tr>
                     </tbody>
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

                                function deleteObj(id) {
					jConfirm(
						"{$vsLang->getWords('obj_delete_confirm', "Are you sure want to delete this {$bw->input[0]}?")}",
						"{$bw->vars['global_websitename']} Dialog",
						function(r) {
							if(r) {
								vsf.get('{$bw->input[0]}/delete-obj/'+id+'/','');
								vsf.get('{$bw->input[0]}/display-obj-list/{$option['categoryId']}/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}','obj-panel');
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
				$('#add-objlist-bt').click(function(){
					$("#obj-category option:selected").each(function () {
						$("#idCategory").val($(this).val());
					});
					vsf.get('{$bw->input[0]}/add-edit-obj-form/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}','obj-panel');
				});
				
				$('#hide-objlist-bt').click(function() {
					if($('#checked-obj').val()=='') {
						jAlert(
							"{$vsLang->getWords('hide_obj_confirm_noitem', "You haven't choose any items to hide!")}",
							"{$bw->vars['global_websitename']} Dialog"
						);
						return false;
					}
					checkObject();
//					var categoryId =0;
//					$("#obj-category option:selected").each(function () {
//						categoryId = $(this).val();
//					});
//					vsf.submitForm($('#obj-list-form'),'{$bw->input[0]}/hide-checked-obj/','none');
					vsf.get('{$bw->input[0]}/hide-checked-obj/'+$('#checked-obj').val()+'/'+ $("#idCategory").val() +'/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}', 'obj-panel');
				});
				
				$('#visible-objlist-bt').click(function() {
					if($('#checked-obj').val()=='') {
						jAlert(
							"{$vsLang->getWords('visible_obj_confirm_noitem', "You haven't choose any items to visible!")}",
							"{$bw->vars['global_websitename']} Dialog"
						);
						return false;
					}
					var categoryId =0;
					$("#obj-category option:selected").each(function () {
						categoryId = $(this).val();
					});
//					vsf.submitForm($('#obj-list-form'),'{$bw->input[0]}/visible-checked-obj/','none');
				checkObject();
				vsf.get('{$bw->input[0]}/visible-checked-obj/'+$('#checked-obj').val()+'/'+ $("#idCategory").val() +'/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}', 'obj-panel');
				});
				
				$('#delete-objlist-bt').click(function() {
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
							var lists = $('#checked-obj').val();
							vsf.get('{$bw->input[0]}/delete-obj/'+lists+'/','none');
							vsf.get('{$bw->input[0]}/display-obj-list/{$option['categoryId']}/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}','obj-panel');
							
							}
						}
					);
				});
			</script>
EOF;
	}

	function addEditObjForm($objItem, $option = array()) {
		global $vsLang, $bw,$vsSettings;
		$BWHTML .= <<<EOF
			<div id="error-message" name="error-message"></div>
			<form id='add-edit-obj-form' name="add-edit-obj-form" method="POST" enctype='multipart/form-data'>
				<input type="hidden" id="obj-cat-id" name="recruitmentCatId" value="{$option['categoryId']}" />
				<input type="hidden" name="recruitmentId" value="{$objItem->getId()}" />
				<input type="hidden" name="pageIndex" value="{$bw->input['pageIndex']}" />
				<input type="hidden" name="pageCate" value="{$bw->input['pageCate']}" />
				<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
					<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
						<span class="ui-dialog-title">{$option['formTitle']}</span>
                                                <p style="align:right; float: right; color: #FFFFFF; cursor: pointer"><span id="close">{$vsLang->getWords('obj_back', 'Back')}</span></p>
					</div>
					<table class="ui-dialog-content ui-widget-content">
						<tr>
							<if="$vsSettings->getSystemKey($bw->input[0].'_title',1)">
							<td class="label_obj" style="float:left;width:67px">{$vsLang->getWords('obj_title', 'Title')}:</td>
							<td colspan="3"><input style="width:100%;" name="recruitmentTitle" value="{$objItem->getTitle()}" id="obj-title"/></td>
							</if>
						</tr>
						
						<tr>
							<td class="label_obj" style="float:left;width:67px">{$vsLang->getWords('obj_Begin', 'Begin')}:</td>
							<td ><input style="width:100px" name="recruitmentBegin" value="{$objItem->getBegin("SHORT")}" id="recruitmentBegin" /></td>
							
							<td class="label_obj" style="float:left;width:67px">{$vsLang->getWords('obj_End', 'End')}:</td>
							<td ><input style="width:100px" name="recruitmentEnd" value="{$objItem->getEnd("SHORT")}"id="recruitmentEnd" /></td>
						</tr>						
						<tr>
							<if="$vsSettings->getSystemKey($bw->input[0].'_index',1)">
							<td class="label_obj" style="width:67px;">{$vsLang->getWords('obj_index', 'Index')}: </td>
							<td style="width:170px;" ><input size="10" type="text" name="recruitmentIndex" value="{$objItem->getIndex()}" /></td>
							</if>
							<if="$vsSettings->getSystemKey($bw->input[0].'_image',1)">
							<td class="label_obj">
							{$vsLang->getWords('obj_image_link', "Link")}:<input onclick="checkedLinkFile($('#link-text').val());" onclicktext="checkedLinkFile($('#link-text').val());" type="radio" id="link-text" name="link-file" value="link" checked="checked"/><br/>
							</td>
							<td>
								<input size="39" type="text" name="txtlink" id="txtlink""/><br/>
							</td>
							</if>
						</tr>
						<tr >
							<if="$vsSettings->getSystemKey($bw->input[0].'_status',1)">
							<td class="label_obj">{$vsLang->getWords('obj_Status', 'Active')}:</td>
							<td><input class='c_noneWidth' type="checkbox" name="recruitmentStatus" id="recruitmentStatus" value='1' style='margin-right:10px;'/></td>
							</if>
							<if="$vsSettings->getSystemKey($bw->input[0].'_image',1)">
							<td class="label_obj" style='text-align:left'>
							{$vsLang->getWords('obj_image_file', "File")}:<input onclick="checkedLinkFile($('#link-file').val());" onclicktext="checkedLinkFile($('#link-file').val());" type="radio" id="link-file" name="link-file" value="file" />
							</td>
							<td>
								<div style="padding:2px 5px;padding-left:0px;">
									<input size="27" type="file" name="recruitmentIntroImage" id="recruitmentIntroImage" />
								</div>
							</td>
							</if>
						</tr>
						<tr>
							<if="$vsSettings->getSystemKey($bw->input[0].'_intro',1)">
							<td class="label_obj" style="width:auto">
							{$vsLang->getWords('obj_Intro', 'Intro')}:
							</td>
							
							<td colspan="3" valgin="center">
								<div style="float:left;">{$objItem->getIntro()}</div>
								
								<if="$bw->vars[$bw->input[0].'_image']">
									<div style="float:right; border: 1px solid;" id="td-obj-image">
										{$objItem->createImageCache($objItem->getImage(),125,125)}
									</div>
								</if>
							</td>
							</if>
						</tr>
						<if="$vsSettings->getSystemKey($bw->input[0].'_content',1)">
						<tr>
							<td colspan="4" align="center">{$objItem->getContent()}</td>
						</tr>
						</if>
						<tr>
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
					checkedLinkFile('link');

                                        $("#close").click(function(){
                                            vsf.get('{$bw->input[0]}/display-obj-list/{$option['categoryId']}/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}','obj-panel');
                                        });
					
					$('#recruitmentBegin').datepicker({dateFormat: 'dd/mm/yy'});
					
					
					$('#recruitmentEnd').datepicker({dateFormat: 'dd/mm/yy'});
					
					vsf.jCheckbox('{$objItem->getStatus()}','recruitmentStatus');
					vsf.jSelect('{$objItem->getCatId()}','obj-category');
				
				});
				$('#txtlink').change(function() {
					var img_html = '<img src="'+$(this).val()+'" style="width:100px; max-height:115px;" />'; 
					$('#td-obj-image').html(img_html);
				});
				$('#recruitmentIntroImage').change(function() {
					var img_name = '<input type="hidden" id="image-name" name="image-name" value="'+$(this).val() +'"/>';
					$('#td-obj-image').html(img_name);
				});
				function checkedLinkFile(value){
					if(value=='link'){
						$("#txtlink").removeAttr('disabled');
						$("#recruitmentIntroImage").attr('disabled', 'disabled');
					}else{
						$("#txtlink").attr('disabled', 'disabled');
						$("#recruitmentIntroImage").removeAttr('disabled');
					}
				}
				$('#add-edit-obj-form').submit(function(){
					var flag  = true;
					var error = "";
					var categoryId=0;
					var count=0;
					$("#obj-category  option").each(function () {
						count++;
					});
					$("#obj-category option:selected").each(function () {
						categoryId = $(this).val();
					});
					$('#obj-cat-id').val(categoryId);
					
					if(categoryId == null && count>1){
						error = "<li>{$vsLang->getWords('not_select_category', 'Vui lÃ²ng chá»�n category!!!')}</li>";
						flag  = false;
					}
					
					var title = $("#obj-title").val();
					if(title == 0 || title == ""){
						error += "<li>{$vsLang->getWords('null_title', 'TiÃªu Ä‘á»� khÃ´ng Ä‘Æ°á»£c Ä‘á»ƒ trá»‘ng!!!')}</li>";
						flag  = false;
					}
					if(!flag){
						error = "<ul class='ul-popu'>" + error + "</ul>";
						vsf.alert(error);
						return false;
					}
					vsf.uploadFile("add-edit-obj-form", "{$bw->input[0]}", "add-edit-obj-process", "obj-panel","recruitment");
					return false;
				});
			</script>
EOF;
	}

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
				    	<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" id="view-obj-bt" title='{$vsLang->getWords('recruitmentItem_EditObjTitle',"Click here to edit this {$bw->input[0]}")}'>{$vsLang->getWords('global_view','Xem')}</a>
				    	<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" id="add-obj-bt" title='{$vsLang->getWords('recruitmentItem_EditObjTitle',"Click here to add this {$bw->input[0]}")}'>{$vsLang->getWords('global_add','Thêm')}</a>
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
		<if="$vsSettings->getSystemKey($bw->input[0].'_Category_tab',1)">
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
			        <if="$vsSettings->getSystemKey($bw->input[0].'_category_tab',1)">
					<li class="ui-state-default ui-corner-top">
			        	<a href="{$bw->base_url}menus/display-category-tab/recruitment/&ajax=1"><span>{$vsLang->getWords('tab_obj_categories','Categories')}</span></a>
			        </li>
			        </if>
			        <if="$vsSettings->getSystemKey($bw->input[0].'_setting_tab',1)">
			        <li class="ui-state-default ui-corner-top">
        				<a href="{$bw->base_url}settings/moduleObjTab/{$bw->input[0]}/&ajax=1">
							{$vsLang->getWords('tab_recruitment_Setting','Recruitment Settings')}
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