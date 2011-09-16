<?php
class skin_pages {
	//show defaut page admin
	function pageMainLayout(){
		global $bw, $vsLang,$vsSettings;
		$BWHTML = "";
		$BWHTML .= <<<EOF
			<div id="page_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all-top">
				<ul id="tabs_nav" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner">
				 	<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
			        	<a href="{$bw->base_url}pages/displayPagesTab/&ajax=1">
			        		<span>{$vsLang->getWords('tab_Pages','Pages')}</span>
		        		</a>
	        		</li>
	        		<li class="ui-state-default ui-corner-top">
			        	<a href="{$bw->base_url}pages/displayVirtualTab/&ajax=1">
			        		<span>{$vsLang->getWords('tab_Virtual','Virtual Module')}</span>
		        		</a>
	        		</li>
	        		<if="$vsSettings->getSystemKey($bw->input[0].'_setting_tab',1,$bw->input[0],1,1 )">
		        		<li class="ui-state-default ui-corner-top">
				        	<a href="{$bw->base_url}settings/moduleObjTab/{$bw->input[0]}/&ajax=1">
								<span>{$vsLang->getWords("tab_{$bw->input[0]}_SS",'System Settings')}</span>
							</a>
			        	</li>	
		        	</if>
		        	<if="$vsSettings->getSystemKey($bw->input[0].'_banner_tab',1,$bw->input[0],1,1 )">
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
	//start virtual Page Tab
	function displayVirtualTab($option=array()){
		global $vsLang, $bw;
		$BWHTML = <<<EOF
			<div id='virtualTabContainer'>			
				<div class="left-cell">
					<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
						<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
							<span class="ui-icon ui-icon-triangle-1-e"></span>
							<span class="ui-dialog-title">{$vsLang->getWords('pages_virtual_module_title_header','Virtual Module')}</span>
						</div>
						
						<div id="virtualForm">{$option['form']}</div>
					</div>
				</div>
				<div class='right-cell' id="mainPageContainer">
				{$option['list']}
				</div>
				<div class="clear"></div>
			</div>
EOF;
				return $BWHTML;
	}

	function displayVirtualItemContainer($virtualList = array(), $option=array()){
		global $vsLang, $bw;
		$message = $vsLang->getWords('pages_deleteConfirm_NoItem', "You haven't choose any items!");


		$BWHTML .= <<<EOF
			<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
			    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
			        <span class="ui-icon ui-icon-triangle-1-e"></span>
			        <span class="ui-dialog-title">{$vsLang->getWords('pages_listVirtualModule','List of Virtual Module')}</span>
			        
			        <span id="deleteVirtual" style="float: right; cursor: pointer;">Delete</span>
			    </div>
			    			   
				    
				<table cellspacing="1" cellpadding="1" id='productListTable' width="100%">
					<thead>
					    <tr>
					        <th width="15"><input type="checkbox" onclick="checkAll()" onclicktext="checkAll()" name="all" /></th>
					        <th >{$vsLang->getWords('pages_virtual_labelStatus', 'Tên module')}</td>
					    </tr>
					</thead>
					<tbody>
						<if=" count($virtualList) > 0">
						<foreach="$virtualList as $virtual">
							<tr>
								<td align="center" width="15">
									<input type="checkbox" onclicktext="checkObject({$virtual->getId()});" onclick="checkObject({$virtual->getId()});" name="obj_{$virtual->getId()}" value="{$virtual->getId()}" class="myCheckbox" />
								</td>
								<td>
									<a href="javascript:vsf.get('pages/displayVirtualForm/{$virtual->getId()}','virtualForm')" title='{$vsLang->getWords('productItem_EditproductTitle','Click here to edit this product')}' class="title">
										<strong>{$virtual->getTitle()} ({$virtual->getClass()})</strong>
									</a>
									<br />
									<div class="desctext">{$virtual->getIntro()}</div>
								</td>
							</tr>
						</foreach>
						</if>
					</tbody>
					<tfoot>
						<tr>
							<th colspan='7'></th>
						</tr>
					</tfoot>
				</table>
			</div>
			<script type='text/javascript'>
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
				$('#deleteVirtual').click(function(){
					jConfirm(
						'{$vsLang->getWords("pages_deleteConfirm","Are you sure to delete these virtual module information?")}', 
						'{$bw->vars['global_websitename']} Dialog', 
						function(r){
							if(r){
								var flag=true; var jsonStr = "";
								$("input[type=checkbox]").each(function(){
									if($(this).hasClass('myCheckbox')){
										if(this.checked) jsonStr += $(this).val()+',';
									}
								});
								jsonStr = jsonStr.substr(0,jsonStr.lastIndexOf(','));
								vsf.get('pages/deleteVirtual/'+jsonStr+'/','virtualTabContainer');
							}
						}
					);
				});
				
			</script>
EOF;
		return $BWHTML;

	}

	function displayEditVirtualForm($module="", $option=''){
		global $vsLang;

		$BWHTML = <<<EOF
			    <form id="editVirtualForm" method="post">
			    	<input class="input" type="hidden" value="{$module->getId()}" name="moduleId" />
			    	<input class="input" type="hidden" value="{$module->getTitle()}" name="oldModuleTitle" />
					<table cellpadding="0" cellspacing="1" width="100%">
				    	<tr>
				        	<th>{$vsLang->getWords('module_list_name','Tên chức năng')}</th>
				            <td><input id="moduleTitle" type="text" value="{$module->getTitle()}" name="moduleTitle" style="width:170px;"/></td>
				        </tr>
				        <tr>
				        	<th>{$vsLang->getWords('module_list_desc','Mô tả')}</th>
				            <td><textarea cols="19" rows="5" name="moduleIntro">{$module->getIntro()}</textarea></td>
				        </tr>
				        <tr>
				        	<th>{$vsLang->getWords('module_list_use','Sử dụng cho')}</th>
				            <td>
				            {$vsLang->getWords('module_list_use_admin','Admin')} <input type="checkbox" name="moduleIsAdmin" id="moduleIsAdmin" value="1"> --
				            {$vsLang->getWords('module_list_use_user','User')} 	<input type="checkbox" name="moduleIsUser" id="moduleIsUser" value="1">
				            </td>
				        </tr>
				        <tr>
				        	<th>&nbsp;</th>
				            <td>
				            	<button class="ui-state-default ui-corner-all" type="submit">{$option['submitValue']}</button>
			            	</td>
				        </tr>
				    </table>			
				</form>
			<div id="result"></div>
			
			<script type="text/javascript">
				$(window).ready(function() {
				vsf.jCheckbox('{$module->getAdmin()}','moduleIsAdmin');
				vsf.jCheckbox('{$module->getUser()}','moduleIsUser');
			});
				$('#editVirtualForm').submit(function(){
					
					
					if(!$('#moduleTitle').val()){
						jAlert(
		        			'{$vsLang->getWords('page_virtualModule_empty','This field can not be empty!')}',
		        			'{$bw->vars['global_websitename']} Dialog'
	        			);
		        		$('#moduleTitle').focus(); 
						$('#moduleTitle').addClass('ui-state-error ui-corner-all-inner');
		        		return false;
					}
							        	
		        	

		        	vsf.submitForm($('#editVirtualForm'),'pages/editVirtualProcess/','virtualTabContainer');
		        	return false;
				});
			</script>
EOF;
				            return $BWHTML;
	}
	//end of virtual page tab

	function displayPageTab($option=array()){
		global $vsLang, $bw,$vsSettings;
		$temp = $option['virtual']? $option['virtual'] : "pages";
		$BWHTML = <<<EOF
		<script type="text/javascript">
				function viewCat(){					
					var cat = "";
					$('#catSelect option:selected').each(function(){
						cat += ","+$(this).val();
					});
					$('#menuSelect option:selected').each(function(){
						cat += ","+$(this).val();
					});
					vsf.get('$temp/displayCatPageList/'+cat.substr(1)+'/{$option['virtual']}/','mainPageContainer');
				}
			</script>
			<div id='pageTabContainer'>			
				
				<input type="hidden" name="currentModule" id="currentModule" value="{$option['module']}" />
				<input name="idCategorys" type="hidden" value="{$option['strCate']}" id="idCategory" />
				<input name="idCatCurrent" type="hidden" value="{$option['idCatCurrent']}" id="idCatCurrent" />
				<input name="pageIn" type="hidden" value="" id="pageIn" />
				<if="$vsSettings->getSystemKey('pages_virtual_show_'.$module,0,$module,1,1 )">
				<div class="left-cell">
					<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
						<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
							<span class="ui-icon ui-icon-triangle-1-e"></span>
							<span class="ui-dialog-title">{$vsLang->getWords('category_table_title_header','Categories')}</span>
						</div>
						<if="$option['menu']">
							<div id="menusContiner">
								<div id="menuContinerLabel">
									{$vsLang->getWords('pages_menu','Menus')}
								</div>
								<select size="10" id="menuSelect" multiple="true">
									{$option['menu']}
								</select>
							</div>
						</if>
					</div>
					<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
						<div id="catContiner">
							<div id="catContinerLabel">
							{$vsLang->getWords('pages_category','Categories')}
							</div>
							<select size="10" id="catSelect" multiple="true">
							{$option['cat']}
							</select>
						</div>
					</div>
					<div style="text-align:center;">
						<img src="{$bw->vars['img_url']}/view.png" alt="{$vsLang->getWords('pages_viewPage',"View")}" onclick="viewCat()"/>
					</div>
					
				</div>
				</if>
				
				<div class='right-cell' id="mainPageContainer" <if="!$vsSettings->getSystemKey('pages_virtual_show_'.$module,0,$module,1,1)"> style="width:100%" </if>>
					<div style="color:red;">{$option['error']}</div>
					{$option['list']}
				</div>
				<div class="clear"></div>
			</div>
			
EOF;
				return $BWHTML;
	}

	function objListHtmlWithCode($option=array()){
		global $vsLang, $bw,$vsUser,$vsSettings;
		
		$BWHTML = "";
		$message = $vsLang->getWords('pages_deleteConfirm_NoItem', "You haven't choose any items!");
		$BWHTML .= <<<EOF
		<div id="mainPageContainer" class="ui-widget ui-widget-content ui-corner-all-top">
			<input type="hidden" name="virtual" id="virtual" value="{$option['virtual']}" />
			<input type="hidden" name="checkedObj" id="checked-obj" value="" />
			<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
			    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
			        <span class="ui-icon ui-icon-triangle-1-e"></span>
			        <span class="ui-dialog-title">{$vsLang->getWords('pages_listPage','Danh sách các trang')}</span>
			    </div>
			    
			    <if=" $vsUser->checkRoot() ">
			    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">
			    	<if="$vsSettings->getSystemKey($option['modePageCode'].'_add_button',1, $option['modePageCode'],1,1)">
				    	<li class="ui-state-default ui-corner-top">
				    		<a id="addPage" title="{$vsLang->getWords('pages_addpage','Add')}" onclick="addPage();" href="#">
				    		{$vsLang->getWords('pages_addpage','Add')}
							</a>
			    		</li>
			    	</if>
				    
		    		
		    		<if=" $vsSettings->getSystemKey($option['modePageCode'].'_delete_button',1, $option['modePageCode'],1,1)">
			    		<li class="ui-state-default ui-corner-top">
				        	<a id="deletePage" title="{$vsLang->getWords('pages_deletePage','Delete')}" onclick="deletePage();" href="#">
				        	{$vsLang->getWords('pages_deletePage','Delete')}
							</a>
						</li>
					</if>
					
					<if=" $vsSettings->getSystemKey($option['modePageCode'].'_hide_button',1, $option['modePageCode'],1,1)">
				        <li class="ui-state-default ui-corner-top">
				        	<a id="hidePage" title="{$vsLang->getWords('pages_hidePage','Hide')}" onclick="displayPage(0);" href="#">
				        	{$vsLang->getWords('pages_hidePage','Hide')}
							</a>
						</li>
					</if>
					
					<if=" $vsSettings->getSystemKey($option['modePageCode'].'_display_button',1, $option['modePageCode'],1,1)">
				        <li class="ui-state-default ui-corner-top">
				        	<a id="displayPage" title="{$vsLang->getWords('pages_unhidePage','Display')}" onclick="displayPage(1);" href="#">
				        	{$vsLang->getWords('pages_unhidePage','Display')}
							</a>
						</li>
					</if>
			    </ul>
			    </if>
			    
				<table cellspacing="1" cellpadding="1" id='productListTable' width="100%">
					<thead>
					    <tr>
					    <if="$vsSettings->getSystemKey($option['virtual'].'_hide_button',1, $option['virtual'],1,1) or $vsSettings->getSystemKey($option['virtual'].'_display_button',1, $option['virtual'],1,1) or $vsSettings->getSystemKey($option['virtual'].'_delete_button',1, $option['virtual'],1,1)">
					    	<th width="15"><input type="checkbox" onclick="checkAll()" onclicktext="checkAll()" name="all" /></th>
					    </if>
					        <th style='text-align:center;' width="20">{$vsLang->getWords('pages_labelStatus', 'Hiện')}</th>
					        <th style='text-align:center;' width="20">{$vsLang->getWords('pages_code', 'Mã trang')}</th>
					        <th style='text-align:center;' width="200">{$vsLang->getWords('pages_labelTitle', 'Tiêu Đề')}</td>
					        <th style='text-align:center;' width="">{$vsLang->getWords('pages_labelIntro', 'Giới Thiệu')}</th>
					        <th style='text-align:center;' width="50">{$vsLang->getWords('pages_labelPostDate', 'Ngày đăng')}</th>
					    </tr>
					</thead>
					<tbody>
						<if=" count($option['pageList'])">
						<foreach="$option['pageList'] as $obj">
							<tr class="$vsf_class">
							<if="$vsSettings->getSystemKey($option['virtual'].'_hide_button',1, $option['virtual'],1,1) or $vsSettings->getSystemKey($option['virtual'].'_display_button',1, $option['virtual'],1,1) or $vsSettings->getSystemKey($option['virtual'].'_delete_button',1, $option['virtual'],1,1)">
								<td align="center" width="20">
									<input type="checkbox" onclicktext="checkObject({$obj->getId()});" onclick="checkObject({$obj->getId()});" name="obj_{$obj->getId()}" value="{$obj->getId()}" class="myCheckbox" />
								</td>
							</if>
								<td style='text-align:center' width="20">{$obj->getStatus('image')}</td>
								<td style='text-align:center' width="20">{$obj->getCode()}</td>
								<td>
									<a href="#" onclick="editPage({$obj->getId()})" title='{$vsLang->getWords('productItem_EditproductTitle','Click here to edit this product')}' class="title">
									{$obj->getTitle()}
									</a>
								</td>
								<td>{$obj->getIntro(300)}</td>
								<td width="50">{$obj->getPostDate("SHORT")}</td>
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
				function editPage(id){
					vsf.get('pages/displayEditForm/'+id+'/&modePageCode={$option['modePageCode']}','mainPageContainer');
					return false;
				}
				function addPage(){
					vsf.get('pages/displayEditForm/0/&modePageCode={$option['modePageCode']}','mainPageContainer');
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
								vsf.get('pages/deletePage/'+jsonStr+'/&modePageCode={$option['modePageCode']}','mainPageContainer');
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
						
						vsf.get('pages/updateStatus/'+jsonStr+'/'+status+'/&modePageCode={$option['modePageCode']}','mainPageContainer');
				}
			</script>
		</div>
EOF;
		return $BWHTML;
	}

	function objListHtml($option=array()){
		global $vsLang, $vsSettings, $bw;
		
		$message = $vsLang->getWords('pages_deleteConfirm_NoItem', "You haven't choose any items!");
		$option['virtual'] = $option['virtual']?$option['virtual']:$bw->input[0];
		
		$BWHTML .= <<<EOF
			<input type="hidden" name="virtual" id="virtual" value="{$option['virtual']}" />
			<input type="hidden" name="checkedObj" id="checked-obj" value="" />
			<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
			    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
			        <span class="ui-icon ui-icon-triangle-1-e"></span>
			        <span class="ui-dialog-title">{$vsLang->getWords('pages_listPage','Danh sách các trang')}</span>
			    </div>
			    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">
		    		<if="$vsSettings->getSystemKey($option['virtual'].'_add_button',1, $option['virtual'],1,1)">
				    	<li class="ui-state-default ui-corner-top">
				    		<a id="addPage" title="{$vsLang->getWords('pages_addpage','Add')}" onclick="addPage();" href="#">
				    		{$vsLang->getWords('pages_addpage','Add')}
							</a>
			    		</li>
			    	</if>
				    
		    		
		    		<if=" $vsSettings->getSystemKey($option['virtual'].'_delete_button',1, $option['virtual'],1,1)">
			    		<li class="ui-state-default ui-corner-top">
				        	<a id="deletePage" title="{$vsLang->getWords('pages_deletePage','Delete')}" onclick="deletePage();" href="#">
				        	{$vsLang->getWords('pages_deletePage','Delete')}
							</a>
						</li>
					</if>
					
					<if=" $vsSettings->getSystemKey($option['virtual'].'_hide_button',1, $option['virtual'],1,1)">
				        <li class="ui-state-default ui-corner-top">
				        	<a id="hidePage" title="{$vsLang->getWords('pages_hidePage','Hide')}" onclick="displayPage(0);" href="#">
				        	{$vsLang->getWords('pages_hidePage','Hide')}
							</a>
						</li>
					</if>
					
					<if=" $vsSettings->getSystemKey($option['virtual'].'_display_button',1, $option['virtual'],1,1)">
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
					    	<if="$vsSettings->getSystemKey($option['virtual'].'_hide_button',1, $option['virtual'],1,1) or $vsSettings->getSystemKey($option['virtual'].'_display_button',1, $option['virtual'],1,1) or $vsSettings->getSystemKey($option['virtual'].'_delete_button',1, $option['virtual'],1,1)">
					       	<th width="15"><input type="checkbox" onclick="checkAll()" onclicktext="checkAll()" name="all" /></th>
					       	</if>
					        <th style='text-align:center;' width="20">{$vsLang->getWords('pages_labelStatus', 'Hiện')}</th>
					        <th style='text-align:center;' width="200">{$vsLang->getWords('pages_labelTitle', 'Tiêu đề')}</td>
					        <th style='text-align:center;' width="">{$vsLang->getWords('pages_labelIntro', 'Giới Thiệu')}</th>
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
							<if="$vsSettings->getSystemKey($option['virtual'].'_hide_button',1, $option['virtual'],1,1) or $vsSettings->getSystemKey($option['virtual'].'_display_button',1, $option['virtual'],1,1) or $vsSettings->getSystemKey($option['virtual'].'_delete_button',1, $option['virtual'],1,1)">
								<td align="center" width="20">
									<if=" $obj->getCode() ">
										<span style="margin-left:-15px">
			        					<img src="{$bw->vars['img_url']}/disabled.png" title="{$vsLang->getWords('pages_notAllowToDelete','Deny to deleting!')}" alt='{$vsLang->getWords('pages_deny','Deny!')}' />
			        					</span>
									<else />
									<input type="checkbox" onclicktext="checkObject({$obj->getId()});" onclick="checkObject({$obj->getId()});" name="obj_{$obj->getId()}" value="{$obj->getId()}" class="myCheckbox" disabled/>
									</if>
								</td>
							</if>
								<td style='text-align:center' width="20">{$obj->getStatus('image')}</td>
								
								<td>
									<a href="#" onclick="editPage({$obj->getId()})" title='{$vsLang->getWords('productItem_EditproductTitle','Click here to edit this product')}' class="title">
										{$obj->getTitle()}
									</a>
								</td>
								<td>{$obj->getIntro(300)}</td>
								<td width="50">{$obj->getPostDate("SHORT")}</td>
	
								<if=" $option['upload'] ">
									<td>
									<a href="javascript:;" onclick="vsf.popupGet('gallerys/display-album-tab/pages/{$obj->getId()}&albumCode={$option['virtual']}','files')" class="ui-state-default ui-corner-all ui-state-focus">
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
			<script type="text/javascript">
				function addPage(){
					vsf.get('{$option['virtual']}/displayEditForm/&virtual='+$('#virtual').val(),'mainPageContainer');
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
					vsf.get('{$option['virtual']}/add-edit-obj-form/&virtual='+$('#virtual').val(),'obj-panel');
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
								vsf.get('{$option['virtual']}/deletePage/'+jsonStr+'/&virtual='+$('#virtual').val(),'mainPageContainer');
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
						
						vsf.get('{$option['virtual']}/updateStatus/'+jsonStr+'/'+status+'/&virtual='+$('#virtual').val(),'mainPageContainer');
				}
				function editPage(id){
					vsf.get('{$option['virtual']}/displayEditForm/'+id+'/&virtual='+$('#virtual').val(),'mainPageContainer');
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

	function displayEditPageForm($obj="", $option=''){
		global $vsLang,$bw,$vsSettings,$vsStd;
		$max_upload_size = min($vsStd->let_to_num(ini_get('post_max_size')), $vsStd->let_to_num(ini_get('upload_max_filesize')));

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
		        				Tiêu đề : 
		        			</td>
		            		<td height="15">
		            			<input id='pageTitle' name="pageTitle" value="{$obj->getTitle()}" type="text" style="width:80%;">
		        			</td>
		        			<if="$vsSettings->getSystemKey("{$option['key']}_evenstream",1,$option['key'],1,1)">
		        			<td>
		        				<input id='pageEventStream' name="pageEventStream" value="{$vsLang->getWords('global_event_stream','Luồng Sự Kiện')}" type="button" onclick="javascript:getEventStream();" style="width:100%;">
		        			</td>
		        			<else />
		        			<td> </td>
		        			</if>
		        		</tr>
		        		<tr id="showPageEventStrem">
			        		
		        		</tr>
		        		</if>
		        		<if="$vsSettings->getSystemKey("{$option['key']}_icon_news",1,$option['key'],1,1)">       
		        		<tr class='smalltitle'>
		        			<td height="15">
		        				{$vsLang->getWords('pages_pageIcon','Icon')}
		        			</td>
		        			<td colspan="2">
		            			<input type="checkbox" class="checkbox"  name="pageIcon"  value="0"/> {$vsLang->getWords('global_no_icon','Icon Hình')}
		            			<input type="checkbox" class="checkbox" name="pageIcon"  value="1"/> {$vsLang->getWords('global_yes_icon','Icon Video')}
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
		            			<input type="radio" class="checkbox"  name="pageStatus"  value="0"/> {$vsLang->getWords('global_no','No')}
		            			<input type="radio" class="checkbox" name="pageStatus"  value="1"/> {$vsLang->getWords('global_yes','Yes')}
		            			<if="$vsSettings->getSystemKey("{$option['key']}_special_home",0,$option['key'],1,1)">
				            		<input type="radio" class="checkbox "  name="pageStatus"  value="2"/> {$vsLang->getWords('global_home','Trang chủ')}
				            		<input type="radio" class="checkbox "  name="pageStatus"  value="3"/> {$vsLang->getWords('global_home1','Trang chủ 2')}
				            		<input type="radio" class="checkbox "  name="pageStatus"  value="4"/> {$vsLang->getWords('global_home2','Trang chủ 3')}				            		
				            	</if>
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
									<td><input size="39" type="text" name="txtlink" id="txtlink"/></td>
								</tr>
								<tr>
									<td>{$vsLang->getWords('obj_image_file', "File")}:<input onclick="checkedLinkFile($('#link-file').val());" onclicktext="checkedLinkFile($('#link-file').val());" type="radio" id="link-file" name="link-file" value="file" checked="checked"/></td>
									<td id="pageImage">
									<div style="width: 180px; height: 18px; border: solid 1px #7FAAFF; background-color: #C5D9FF; padding: 2px;">
										<span id="spanButtonPlaceholder"></span>
									</div>
									<div id="fsUploadProgress"></div>
									
									<if="$bw->input['modePageCode']!=''">
								        	<script>
												$(window).ready(function() {
													var swfu;
													swfu = new SWFUpload(vsf.uploadSWF($max_upload_size,['editPageForm','spanButtonPlaceholder','fsUploadProgress',''],'pages','pages',["{$vsSettings->getSystemKey('global_file_image_extend',"*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.csv;*.xps;*.pdf;*.zip;*.rar","global",  0, 1)}","Documents"]));
													
												});
											</script>
							        	<else />
							        		<script>
												$(window).ready(function() {
													var swfu;
													swfu = new SWFUpload(vsf.uploadSWF($max_upload_size,['editPageForm','spanButtonPlaceholder','fsUploadProgress',''],'{$option['key']}','{$option['key']}',["{$vsSettings->getSystemKey('global_file_image_extend',"*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.csv;*.xps;*.pdf;*.zip;*.rar","global",  0, 1)}","Documents"]));
												});
											</script>
							        	</if>
									</td>
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
								<input type="submit" value="{$option['submitValue']}"/>
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
			
				function getEventStream(){
					var keyword = $("#pageTitle").val();
					if(!keyword){
						jAlert(
		        			'{$vsLang->getWords('pages_TitleError','Empty Title!')}',
		        			'{$bw->vars['global_websitename']} Dialog'
	        			);
		        		$('#pageTitle').focus();
		        		$('#pageTitle').addClass('ui-state-error ui-corner-all-inner');
		        		return false;
					}
					vsf.get('pages/getEventStream/{$option['virtual']}/'+ keyword ,'showPageEventStrem');
				}
				
				function checkedLinkFile(value){
					if(value=='link'){
						$("#txtlink").removeAttr('disabled');
						$("#pageImage").css('visibility','hidden');
					}else{
						$("#txtlink").attr('disabled', 'disabled');
						$("#pageImage").css('visibility','visible');
					}
				}
				$('#txtlink').change(function() {
					var img_html = '<img src="'+$(this).val()+'" style="width:100px; max-height:115px;" />'; 
					$('#td-obj-image').html(img_html);
				});
				$(document).ready(function(){
					checkedLinkFile('file');
					vsf.jRadio({$obj->getStatus()},'pageStatus')
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
						return vsf.get('{$option['key']}/displayCatMPageList/{$obj->getGroupdIds()}/'+$("#virtualModule").val(),'mainPageContainer');
					<if="$bw->input['modePageCode']!=''">
						<if="$bw->input['modePageCode']!='1'">
							return vsf.get('pages/pageCode/{$bw->input['modePageCode']}','mainPageContainer');
						</if>
						return vsf.get('pages/pageCode','mainPageContainer');
					</if>
					vsf.get('pages/getObjList/','mainPageContainer');
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
		        	</if>
		        		vsf.submitForm($('#editPageForm'), 'pages/editPageProcess',"mainPageContainer");
		        	return false;
				});
			</script>
EOF;
		        			return $BWHTML;
	}

	function displayModulePagesTab($module){
		global $vsLang, $bw,$vsUser,$vsSettings;
		$BWHTML = <<<EOF
	
			<div id="page_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all-top">
				<ul id="tabs_nav" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner">
				 	<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
			        	<a href="{$bw->base_url}pages/displayMPages/{$module}/&ajax=1">
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
        			<if="$vsSettings->getSystemKey($bw->input[0].'_banner_tab',0,$module,1,1)">
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

	function displayEvenStream($result){
		global $bw, $vsLang;
		
		$BWHTML = <<<EOF
				
        			<td colspan="3">
	        				<table cellpadding="1" cellspacing="1" border="0" class="ui-dialog-content ui-widget-content" style="width:100%;">
	        					<thead>
		        					<tr>
		        						<td ><input type="checkbox" onclick="checkAll()" onclicktext="checkAll()" name="allcheck" class="myCheckbox1" disabled/></td>
		        						<td style="text-align:center;">{$vsLang->getWords('pages_pageTittle','Title')}</td>
		        						<td style="text-align:center;">{$vsLang->getWords('pages_pageDate','Date')}</td>
		        					<tr>
	        					</thead>
        						<if="$result">
        							<foreach="$result as $value">
	        							<tr>
			        						<td><input type="checkbox" onclicktext="checkObject({$value->getId()});" onclick="checkObject({$value->getId()});" name="obj_{$value->getId()}" value="{$value->getId()}" class="myCheckbox1"/></td>
			        						<td>{$value->getTitle()}</td>
			        						<td>{$value->getPostDate('LONG')}</td>
			        					</tr>
	        						</foreach>
        						</if>
	        				</table>
	        				<input name="pageEvent" value="1" type="hidden"/>
        					<input id="checked-obj1" name="checkedObj" value="" type="hidden" />
        			</td>
        			<script type='text/javascript'>
        				function checkAll() {
							var checked_status = $("input[name=allcheck]:checked").length;
							var checkedString = '';
							$("input[type=checkbox]").each(function(){
								if($(this).hasClass('myCheckbox1')){
									this.checked = checked_status;
									if(checked_status) 
										checkedString += $(this).val()+',';
								}
							});
							
							$("span[acaica=myCheckbox1]").each(function(){
								if(checked_status)
									this.style.backgroundPosition = "0 -50px";
								else this.style.backgroundPosition = "0 0";
							});
							checkedString = checkedString.substr(0,checkedString.lastIndexOf(','));
							$('#checked-obj1').val(checkedString);
						}
						function checkObject() {
							var checkedString = '';
							$("input[type=checkbox]").each(function(){
								if($(this).hasClass('myCheckbox1')){
									if(this.checked) 
										checkedString += $(this).val()+',';
								}
							});
							checkedString = checkedString.substr(0,checkedString.lastIndexOf(','));
							$('#checked-obj1').val(checkedString);
						}
        			</script>
EOF;
	return $BWHTML;
	}
	
	function displayModulePages($option=array()){
		global $vsLang, $bw,$vsSettings;
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
						vsf.get('pages/displayCatMPageList/'+cat.substr(1)+'/{$option['virtual']}/','mainPageContainer');
					}
				</script>
			    <div id='pageTabContainer' class="ui-tabs-panel ui-widget-content ui-corner-bottom">	
			    	<if="$vsSettings->getSystemKey($option['virtual'].'_category_list',1,$option['virtual'],1,1)">
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
					{$option['list']}
					{$option['error']}
					</div>
					<div class="clear"></div>
				</div>
			
EOF;
					return $BWHTML;
	}
}
?>