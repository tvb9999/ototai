<?php
class skin_documents extends skin_pages {

	function objListHtml($option = array()) {
		global $vsLang, $vsSettings, $bw;
		
		$temp = $option ['virtual'] ? $option ['virtual'] : "pages";
		$BWHTML = "";
		$message = $vsLang->getWords ( 'pages_deleteConfirm_NoItem', "You haven't choose any items!" );
		
		$obj = new Page ();
		$BWHTML .= <<<EOF
			<input type="hidden" name="virtual" id="virtual" value="{$option['virtual']}" />
			<input type="hidden" name="checkedObj" id="checked-obj" value="" />
			<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
			    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
			        <span class="ui-icon ui-icon-triangle-1-e"></span>
			        <span class="ui-dialog-title">{$vsLang->getWords('pages_listPage','Danh sách các trang')}</span>
			    </div>
			    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">
		    		<if=" $vsSettings->getSystemKey($temp.'_addPage_button',1, $temp)">
				    	<li class="ui-state-default ui-corner-top">
				    		<a id="addPage" title="{$vsLang->getWords('pages_addPage','Add')}" onclick="addPage();" href="#">
				    		{$vsLang->getWords('pages_addPage','Add')}
							</a>
			    		</li>
			    	</if>
				    
		    		
		    		<if=" $vsSettings->getSystemKey($temp.'_deletePage_button',1, $temp)">
			    		<li class="ui-state-default ui-corner-top">
				        	<a id="deletePage" title="{$vsLang->getWords('pages_deletePage','Delete')}" onclick="deletePage();" href="#">
				        	{$vsLang->getWords('pages_deletePage','Delete')}
							</a>
						</li>
					</if>
					
					<if=" $vsSettings->getSystemKey($temp.'_hidePage_button',1, $temp)">
				        <li class="ui-state-default ui-corner-top">
				        	<a id="hidePage" title="{$vsLang->getWords('pages_hidePage','Hide')}" onclick="displayPage(0);" href="#">
				        	{$vsLang->getWords('pages_hidePage','Hide')}
							</a>
						</li>
					</if>
					
					<if=" $vsSettings->getSystemKey($temp.'_displayPage_button',1, $temp)">
				        <li class="ui-state-default ui-corner-top">
				        	<a id="displayPage" title="{$vsLang->getWords('pages_unhidePage','Display')}" onclick="displayPage(1);" href="#">
				        	{$vsLang->getWords('pages_unhidePage','Display')}
							</a>
						</li>
					</if>
			    </ul>
				<table cellspacing="1" cellpadding="1" id='productListTable' width="100%">
					<thead>
					    <tr>
					    	<if="$vsSettings->getSystemKey($temp.'_hidePage_button',1, $temp) or $vsSettings->getSystemKey($temp.'_displayPage_button',1, $temp) or $vsSettings->getSystemKey($temp.'_deletePage_button',1, $temp)">
					       	<th width="15" align="center"><input type="checkbox" onclick="checkAll()" onclicktext="checkAll()" name="all" /></th>
					       	</if>
					        <th style='text-align:center;' width="15">{$vsLang->getWords('pages_labelStatus', 'Hiện')}</th>
					        <th style='text-align:center;' width="200">{$vsLang->getWords('pages_labelTitle', 'Tiêu Đề')}</td>
					        <th style='text-align:center;' width="200">Thay đổi</td>
					        <th style='text-align:center;' width="50">{$vsLang->getWords('pages_labelPostDate', 'Ngày đăng')}</th>
					        <if=" $option['upload'] ">
					        <th style='text-align:center;' width="80">{$vsLang->getWords('pages_labelFile', 'Files')}</th>
					        </if>
					    </tr>
					</thead>
					<tbody>
						<if=" count($option['pageList'])">
						<foreach="$option['pageList'] as $obj">
							<tr class="$vsf_class">
							<if="$vsSettings->getSystemKey($option['virtual'].'_hidePage_button',1, $option['virtual']) or $vsSettings->getSystemKey($option['virtual'].'_displayPage_button',1, $option['virtual']) or $vsSettings->getSystemKey($option['virtual'].'_deletePage_button',1, $option['virtual'])">
								<td align="center" width="15">
									<input type="checkbox" onclicktext="checkObject({$obj->getId()});" onclick="checkObject({$obj->getId()});" name="obj_{$obj->getId()}" value="{$obj->getId()}" class="myCheckbox" disabled/>
								</td>
							</if>
								<td style='text-align:center' width="20">{$obj->getStatus('image')}</td>
								
								<td>
									<a href="#" onclick="editPage({$obj->getId()})" title='{$vsLang->getWords('productItem_EditproductTitle','Click here to edit this product')}' class="title">
										{$obj->getTitle()}
									</a>
								</td>
								<td width="50">{$obj->getIntro()}</td>
								<td width="50">{$obj->getPostDate("SHORT")}</td>
	
								<if=" $option['upload'] ">
									<td>
									<a href="javascript:;" onclick="vsf.popupGet('gallerys/display-album-tab/pages/{$obj->getId()}&albumCode=documents','files')" class="ui-state-default ui-corner-all ui-state-focus">
										{$vsLang->getWords('pages_File','Upload')}
									</a>
									</td>
								</if>
							</tr>
						</foreach>
						</if>
					</tbody>
					<tfoot>
						<tr>
							<th colspan='7'>
								<div style='float:right;'>{$option['paging']}</div>
							</th>
						</tr>
					</tfoot>
				</table>
			</div>
			<script type="text/javascript">
				function addPage(){
					vsf.get('{$temp}/displayEditForm/&virtual='+$('#virtual').val(),'mainPageContainer');
				}
				
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
					$("#obj-category option:selected").each(function () {
						$("#idCategory").val($(this).val());
					});
					vsf.get('{$temp}/add-edit-obj-form/&virtual='+$('#virtual').val(),'obj-panel');
				});
				
				function deletePage(){
					jConfirm(
						'{$vsLang->getWords("pages_deleteConfirm","Are you sure to delete these page information?")}', 
						'{$bw->vars['global_websitename']} Dialog', 
						function(r){
							if(r){
								if($('#checked-obj').val()=='') {
									vsf.alert("{$vsLang->getWords('hide_obj_confirm_noneitem', "You haven't choose any items to do!")}");
									return false;
								}
								jsonStr = $('#checked-obj').val();
								vsf.get('$temp/deletePage/'+jsonStr+'/&virtual='+$('#virtual').val(),'mainPageContainer');
							}
						}
					);
				}
				function displayPage(status){
						var flag=true; var jsonStr = "";
						if($('#checked-obj').val()=='') {
							vsf.alert("{$vsLang->getWords('hide_obj_confirm_noneitem', "You haven't choose any items to do!")}");
							return false;
						}
						jsonStr = $('#checked-obj').val();
						
						vsf.get('{$temp}/updateStatus/'+jsonStr+'/'+status+'/&virtual='+$('#virtual').val(),'mainPageContainer');
				}
				function editPage(id){
					vsf.get('{$temp}/displayEditForm/'+id+'/&virtual='+$('#virtual').val(),'mainPageContainer');
					return false;
				}
				
		
			$(document).ready(function(){
				$('#idCategory').val("{$option['catIds']}");
				$('#pageIn').val("{$bw->input[3]}");
			});
			</script>
EOF;
		return $BWHTML;
	}

	function displayModulePages($option = array()) {
		global $vsLang, $bw, $vsSettings;
		$BWHTML = "";
		
		$BWHTML = <<<EOF
				<script type="text/javascript">
					$(document).ready(function(){
						$("#catContiner [value=0]").val({$option['rootId']});
					});
					function catView(){					
						var cat = "";
						$('#catSelect option:selected').each(function(){
							cat += ","+$(this).val();
						});
						vsf.get('documents/displayCatMPageList/'+cat.substr(1)+'/{$option['virtual']}/','mainPageContainer');
					}
				</script>
			    <div id='pageTabContainer' class="ui-tabs-panel ui-widget-content ui-corner-bottom">	
			    	<if="$vsSettings->getSystemKey($option['virtual'].'_category_list',1,$option['virtual'])">
					<div class="left-cell">
						<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
							<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
								<span class="ui-icon ui-icon-triangle-1-e"></span>
								<span class="ui-dialog-title">{$vsLang->getWords('category_table_title_header','Categories')}</span>
							</div>
							<table width="100%" cellpadding="0" cellspacing="1">
								<tr>
							    	<th id="catContinerLabel" colspan="2">{$data['message']}{$vsLang->getWords('category_chosen',"Selected categories")}: {$vsLang->getWords('category_not_selected',"None")}</th>
							    </tr>
							    <tr>
							        <td width="220">
		{$option['cat']}
							        </td>
							    	<td align="center">
							    	<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" onclick="catView()" title='{$vsLang->getWords('view_list_in_cat',"Click here to edit this {$bw->input[0]}")}'>{$vsLang->getWords('global_view','Xem')}</a>
							        </td>
								</tr>
							</table>
						</div>
					</div>
					<div class='right-cell' id="mainPageContainer" >
					<else />
						<div class='right-cell' style='width:100%' id="mainPageContainer">
					</if>
					{$option ['list']}
					{$option['error']}
					</div>
					<div class="clear"></div>
				</div>
			
EOF;
		
		return $BWHTML;
	}

	function displayModulePagesTab($module) {
		global $vsLang, $bw, $vsUser, $vsSettings;
		$BWHTML = <<<EOF
	
			<div id="page_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all-top">
				<ul id="tabs_nav" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner">
				 	<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
			        	<a href="{$bw->base_url}documents/displayMPages/{$module}/&ajax=1">
			        		<span>{$vsLang->getWords('Pages','Trang')} : {$vsLang->getWords('pages_virtual_'.$module,$module)}</span>
		        		</a>
	        		</li>
	        		<if="$vsSettings->getSystemKey($module.'_category_tab',1,$module,1,1)">
		        		<li class="ui-state-default ui-corner-top">
				        	<a href="{$bw->base_url}/menus/display-category-tab/{$module}/&ajax=1">
				        		<span>{$vsLang->getWords('Category','Danh mục')} : {$vsLang->getWords('pages_virtual_'.$module,$module)}</span>
			        		</a>
		        		</li>
	        		</if>
	        		<if="$vsSettings->getSystemKey($module.'_setting_tab',1,$module,1,1)">
				        <li class="ui-state-default ui-corner-top">
	        				<a href="{$bw->base_url}settings/moduleObjTab/{$module}/&ajax=1"><span>{$vsLang->getWords("tab_{$bw->input[0]}_ss",'System Settings')}</span></a>
	        			</li>
        			</if>
        			<if="$vsSettings->getSystemKey($bw->input[0].'_banner_tab',1,$module,1,1)">
			        	<li class="ui-state-default ui-corner-top">
					        	<a href="{$bw->base_url}partners/moduleObjTab/{$bw->input[0]}/&ajax=1">
									<span>{$vsLang->getWords("tab_{$bw->input[0]}_partner","{$bw->input[0]} Banner")}</span>
								</a>
				        </li>
			        </if>
			    </ul>
			</div>
EOF;
		return $BWHTML;
	}

	function displayEditPageForm($obj="", $option=''){
		global $vsLang,$bw,$vsSettings;
		$checkStatus[$obj->getStatus()]="checked";

		$BWHTML = <<<EOF
			<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
			    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
			        <span class="ui-icon ui-icon-triangle-1-e"></span>
			        <span class="ui-dialog-title">{$option['formTitle']}</span>
			        <span id="closePageForm" class="closePage" title="{$vsLang->getWords('global_undo','Trở lại')}"></span>
			    </div>
			    <div class='clear'></div>
			    <form id="editPageForm" method="post">
					<table cellpadding="1" cellspacing="1" border="0" class="ui-dialog-content ui-widget-content" style="width:100%;">
						<if="$vsSettings->getSystemKey("{$option['key']}_title",1,$option['key'],1,1)">
						<tr class='smalltitle'>
		        			<td >
		        				{$vsLang->getWords('pages_pageTittle','Title')}
		        			</td>
		            		<td colspan="2" height="15">
		            			<input id='pageTitle' name="pageTitle" value="{$obj->getTitle()}" type="text" style="width:100%;">
		        			</td>
		        		</tr>
		        		</if>
		        		<tr class='smalltitle'>
		        			<td height="15">
		        				{$vsLang->getWords('pages_pageIndex','Index')}
		        			</td>
		            		<td>
		            			<input id="pageIndex" name="pageIndex" value="{$obj->getIndex()}" class='numeric' type="text" style="width:25px;margin-right:50px;">
		            			{$vsLang->getWords('pages_pageStatus','Status')}
		            			<input type="radio" class="checkbox"  name="pageStatus" {$checkStatus[0]} value="0"/> {$vsLang->getWords('global_no','No')}
		            			<input type="radio" class="checkbox" name="pageStatus" {$checkStatus[1]} value="1"/> Private
				            	<input type="radio" class="checkbox"  name="pageStatus" {$checkStatus[2]} value="2"/> Public
		            			&nbsp; &nbsp; &nbsp; &nbsp;
		        			</td>
		        			<td rowspan="3">
		        			<if="$vsSettings->getSystemKey("{$option['key']}_image",1,$option['key'],1,1)">
		        				<if="$obj->getImage()">
									<div id="td-obj-image">{$obj->createImageCache($obj->getImage(),100,100)}</div>
									<div>{$vsLang->getWords('pages_pageDeleteImage','Delete Image')}
									<input name="pageDeleteImage" type="checkbox" value="1" class="checkbox" />
									</div>
								</if>
								</if>
		        			</td>
		        		</tr>
		        		<if="$vsSettings->getSystemKey("{$option['key']}_code",0,$option['key'],1,1)">
		        		<tr class='smalltitle'>
		        			<td >
								{$vsLang->getWords('pages_pageCode','Page Code')}
		        			</td>
		            		<td colspan="2" height="15">
		            			<input id="pageCode" name="pageCode" value="{$obj->getCode()}" type="text" style="width:105px;margin-right:50px;">
		        			</td>
		        		</tr>
		        		</if>
		        		<if="$vsSettings->getSystemKey("{$option['module']}_key",0,$option['key'],1,1)">
						<tr class='smalltitle'>
		        			<td height="15">
		        				{$vsLang->getWords('pages_pageLinkSetting','URL Setting')}
		        			</td>
		            		<td align='left'>
		            			{$vsLang->getWords('pages_pageUpdateStatus','URL Backuped')}
		            			{$vsLang->getWords('pages_pageKeep','Do Nothing')}
		            			<input name="pageUpdatedAction" type="radio"  class='checkbox' checked value="0"/>
		            			<if="!$obj->updateLink">
		            			{$vsLang->getWords('pages_pageUpdated','Update URL')}
		            			<input name="pageUpdatedAction" type="radio" class='checkbox' value="1"/>
		            			</if>
		        			</td>
		        		</tr>
		        		</if>
		        		<if="$vsSettings->getSystemKey("{$option['key']}_image",1,$option['key'],1,1)">
		        		<tr class='smalltitle'>
		        			<td >{$vsLang->getWords('pages_pageImage',"Intro Image")}</td>
							<td>
								<table>
								<tr>
									<td>{$vsLang->getWords('obj_image_link', "Link")}:<input onclick="checkedLinkFile($('#link-text').val());" onclicktext="checkedLinkFile($('#link-text').val());" type="radio" id="link-text" name="link-file" value="link" /></td>
									<td><input size="39" type="text" name="txtlink" id="txtlink""/></td>
								</tr>
								<tr>
									<td>{$vsLang->getWords('obj_image_file', "File")}:<input onclick="checkedLinkFile($('#link-file').val());" onclicktext="checkedLinkFile($('#link-file').val());" type="radio" id="link-file" name="link-file" value="file" checked="checked"/></td>
									<td><input size="27" type="file" name="pageImage" id="pageImage" /></td>
								</tr>
								</table>
							</td>
						</tr>
						</if>
						<if="$vsSettings->getSystemKey($option['key'].'_address_google',0,$option['key'],1,1)">
							<tr class='smalltitle'>
			        			<td >
			        				{$vsLang->getWords('pages_addGoogle','Address Google')}
			        			</td>
			            		<td >
			            			<input id='pageAddGoogle' name="pageAddGoogle" value="{$obj->getAddGoogle()}" type="text" style="width:100%;">
			        			</td>
			        		</tr>
		        		</if>
						<if="$vsSettings->getSystemKey("{$option['key']}_intro",1,$option['key'],1,1)">
		         		<tr class='smalltitle'>
		        			<td height="15" colspan="3">
		        				{$vsLang->getWords('pages_pageIntro','Introduction')}
							</td>
						</tr>
						<tr class='smalltitle'>
							<td colspan="3">
								<if="$vsSettings->getSystemKey("{$option['key']}_intro_editor", 0, $option['key'],1,1)">
								{$obj->getIntro()}
								<else />
									<textarea name="pageIntro" style="width:100%;height:150px;">{$obj->getIntro()}</textarea>
								</if>
							</td>
		        		</tr>
		        		</if>
		        		<if="$vsSettings->getSystemKey("{$option['key']}_content",1,$option['key'],1,1)">
		        		<tr class='smalltitle'>
		        			<td class="submenu" colspan="3">
		            			{$vsLang->getWords('pages_pageContent','Page Content')}
							</td>
		        		</tr>
		        		<tr class='smalltitle'>
		        			<td id='pageContent1' colspan="3" name='pageContent' align="center" >
		             			{$obj->getContent()}
		             		</td>
		             	</tr>
		             	</if>
						<tr class='smalltitle ui-dialog-buttonpanel'>
							<td colspan="3" align="center" valign="top">
								<input type="submit" value="{$option['submitValue']}" />
							</td>
						</tr>
					</table>
					<input type="hidden" name="pageOldFileId" value="{$obj->getImage(false)}" />
					<input type="hidden" name="pageOldgroupIds" value="{$obj->getGroupdIds()}" />
					<input type="hidden" name="pageId" value="{$obj->getId()}">
					<input type="hidden" name="virtualModule"  id="virtualModule" value="{$option['virtual']}">	
					<input type="hidden" name="pageAddTime" value="{$obj->getPostDate()}">
				</form>
			</div>
			<div id="result"></div>
			<script type="text/javascript">
				function checkedLinkFile(value){
					if(value=='link'){
						$("#txtlink").removeAttr('disabled');
						$("#pageImage").attr('disabled', 'disabled');
					}else{
						$("#txtlink").attr('disabled', 'disabled');
						$("#pageImage").removeAttr('disabled');
					}
				}
				$('#txtlink').change(function() {
					var img_html = '<img src="'+$(this).val()+'" style="width:100px; max-height:115px;" />'; 
					$('#td-obj-image').html(img_html);
				});
				$(document).ready(function(){
					checkedLinkFile('file');
					 $('#pagePostDate').datepicker({dateFormat: 'dd/mm/yy'});
					
					$("input.numeric").numeric();
					var ids = '{$obj->getGroupdIds()}';
					var idArray = new Array();
					idArray = ids.split(',');
					for(var i = 0; i < idArray.length; i++){
						$('[value='+idArray[i]+']').attr('selected', 'selected');
					}
					
				});
				
				$('#closePageForm').click(function(){
					if($("#virtualModule").val())
						return vsf.get('{$option['key']}/getMPageList/'+$("#virtualModule").val(),'mainPageContainer');
					<if="$bw->input['modePageCode']!=''">
						<if="$bw->input['modePageCode']!='1'">
							return vsf.get('pages/pageCode/{$bw->input['modePageCode']}','mainPageContainer');
						</if>
						return vsf.get('pages/pageCode','mainPageContainer');
					</if>
					vsf.get('pages/getObjList','mainPageContainer');
				});
				
				function getSelectedMenuCat(){
					var cat = "";
					$('#menuSelect option:selected').each(function(){
						cat += ","+$(this).val();
					});
					$('#catSelect option:selected').each(function(){
						cat += ","+$(this).val();
					});
					return cat.substr(1);
				}
				
				$('#editPageForm').submit(function(){
					var count=0;
					$("#menuSelect  option").each(function () {
						count++;
					});
					$("#catSelect  option").each(function () {
						count++;
					});
					if(count>1){
						pageGroupIds = getSelectedMenuCat();
						if(!pageGroupIds){
							jAlert(
			        			'{$vsLang->getWords('pages_emptyCatError','You have to choose the category!')}',
			        			'{$bw->vars['global_websitename']} Dialog'
		        			);
			        		$('#menuSelect').focus(); 
			        		$('#menuSelect').addClass('ui-state-error ui-corner-all-inner');
			        		$('#catSelect').addClass('ui-state-error ui-corner-all-inner');
			        		return false;
						}
			        	var hiddenCatId = '<input type="hidden" name="pageGroupIds" value="' + pageGroupIds + '" />';

		        		$('#editPageForm').append(hiddenCatId);
					}
		        	<if="$bw->input['modePageCode']!=''">
		        		var hiddenPageCode = '<input type="hidden" name="modePageCode" value="{$bw->input['modePageCode']}" /><input type="hidden" name="pageCode" value="{$bw->input['modePageCode']}" />';
			        	$('#editPageForm').append(hiddenPageCode);
			        	
		        		vsf.uploadFile('editPageForm', 'pages','editPageProcess','mainPageContainer', 'pages');
		        	<else />
		        		vsf.uploadFile('editPageForm', '{$option['key']}','editPageProcess','mainPageContainer', 'pages');
		        	</if>
		        	return false;
				});
			</script>
EOF;
		        			return $BWHTML;
	}
}
?>