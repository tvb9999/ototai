<?php
class skin_wincards {
	
function getObjListPmt($option = array()) {
		global $bw, $vsLang,$vsPrint;
		
		$BWHTML .= <<<EOF
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
				
				$('#add-objlist-bt').click(function() {
					if($('#checked-obj').val()=='') {
						jAlert(
							"{$vsLang->getWords('delete_obj_confirm_noitem', "You haven't choose any items to delete!")}",
							"{$bw->vars['global_websitename']} Dialog"
						);
						return false;
					}
					
					var lists = $('#checked-obj').val();
					vsf.get('{$bw->input[0]}/addEditPromotion/{$option['promotionId']}/'+lists+'/','pmt');
				});
				$(window).ready(function() {
					<if="count($option['promotion'])">
					<foreach="$option['promotion'] as $key =>$value">
						<if="method_exists($value,getRelId)">
							vsf.jCheckbox('{$key}','obj_{$key}');
						<else />
							vsf.jCheckbox('{$value}','obj_{$value}');
						</if>
					</foreach>
					checkObject()
					</if>
					
									 $("#objListHtmlTable").tableSorter({
									 	sortClassAsc: 'headerSortUp',		// class name for ascending sorting action to header
										sortClassDesc: 'headerSortDown',	// class name for descending sorting action to header
										headerClass: 'header'
									 })
				    
				});
			</script>
			<div id="pmt">
			<div class="red">{$option['message']}</div>
			<form id="obj-list-form">
				<input type="hidden" name="checkedObj" id="checked-obj" value="" />
				<div class='ui-widget ui-widget-content ui-corner-all ui-dialog-modal'>
				    	<a  id="add-objlist-bt" style="padding: 3px 5px;" href="#" title="{$vsLang->getWords('add_obj_al_bt',"Chọn")}" class="ui-state-default ui-corner-all">{$vsLang->getWords('add_obj_al_bt',"Add {$bw->input[0]}")}</a>
					<table cellspacing="1" cellpadding="1" id='objListHtmlTable' width="100%" class="tablesorter">
						<thead>
						    <tr>
						        <th width="15"><input type="checkbox" onclick="checkAll()" onclicktext="checkAll()" name="all" /></th>
						        <th>{$vsLang->getWords('obj_list_title', 'Title')}</th>
						    </tr>
						</thead>
						<tbody>
							<foreach="$option['pageList'] as $obj">
								<tr class="$vsf_class">
									<td align="center">
										<input type="checkbox" onclick="checkObject({$obj->getId()});" id="obj_{$obj->getId()}" name="obj_{$obj->getId()}" value="{$obj->getId()}" class="myCheckbox" />
									</td>
									<td>
										{$obj->getTitle()}
									</td>
								</tr>
							</foreach>
						</tbody>
						<tfoot>
							<tr>
								<th colspan='6'>
									<div style='float:right;' id="pager" class="pager">{$option['paging']}</div>
								</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</form>
			</div>		
			
EOF;
	}
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
				    </ul>
					<table cellspacing="1" cellpadding="1" id='objListHtmlTable' width="100%">
						<thead>
						    <tr>
						        <th>{$vsLang->getWords('obj_list_title', 'Title')}</th>
						        <th width="30">{$vsLang->getWords('obj_list_state', 'Tình trạng')}</th>
						        <th width="30">{$vsLang->getWords('obj_list_index', 'Index')}</th>
						        <th width="65">{$vsLang->getWords('obj_list_option', 'Tùy chọn')}</th>
						        <th width="20">{$vsLang->getWords('obj_list_status', 'Trạng Thái')}</th>
						    </tr>
						</thead>
						<tbody>
							<foreach="$objItems as $obj">
								<tr class="$vsf_class">
									<td>
										{$obj->getTitle()}
									</td>
									<td>
										{$obj->getCode()}
									</td>
									<td algin="center">
										{$obj->getScore()}
									</td>
									<td align="center">
										
									</td>
									<td style='text-align:center'>
										<if="!$obj->getStatus()">
										<a href="javascript:vsf.get('{$bw->input[0]}/visible-checked-obj/{$obj->getId()}//&pageIndex={$bw->input[3]}','obj-panel')" title='{$vsLang->getWords('newsItem_EditObjTitle',"Click here to edit this {$bw->input[0]}")}' style='color:#CA59AA !important;' >
											{$obj->getStatus('image')}
										</a>
										<else />
											{$obj->getStatus('image')}
										</if>
									</td>
								</tr>
							</foreach>
						</tbody>
						<tfoot>
							<tr>
								<th colspan='6'>
									<div style='float:right;'>{$option['paging']}</div>
								</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</form>
			<div class="clear" id="file"></div>
		
			
EOF;
	}

	function addEditObjForm($objItem, $option = array()) {
		global $vsLang, $bw,$vsSettings;
		$active = $objItem->getStatus()!=''?$objItem->getStatus():1;
		$BWHTML .= <<<EOF
			<div id="error-message" name="error-message"></div>
			<form id='add-edit-obj-form' name="add-edit-obj-form" method="POST" enctype='multipart/form-data'>
				<input type="hidden" id="obj-cat-id" name="wincardCatId" value="{$option['categoryId']}" />
				<input type="hidden" name="wincardId" value="{$objItem->getId()}" />
				<input type="hidden" name="pageIndex" value="{$bw->input['pageIndex']}" />
				<input type="hidden" name="pageCate" value="{$bw->input['pageCate']}" />
					
				<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
					<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
						<span class="ui-dialog-title">{$option['formTitle']}</span>
					</div>
					<table cellpadding="1" cellspacing="1" border="0" class="ui-dialog-content ui-widget-content" style="width:100%;">
						<tr class='smalltitle'>
							<if="$vsSettings->getSystemKey("{$bw->input[0]}_title",1,$bw->input[0])">
							<td >{$vsLang->getWords('obj_title', 'Title')}:</td>
							<td colspan="3"><input size="90" type="text" name="wincardTitle" value="{$objItem->getTitle()}" id="obj-title"/></td>
							</if>
						</tr>
						<tr class='smalltitle'>
							<if="$vsSettings->getSystemKey("{$bw->input[0]}_code",1,$bw->input[0])">
							<td class="label_obj">{$vsLang->getWords('obj_code', 'Code')}: </td>
							<td><input size="40" type="text" name="wincardCode" value="{$objItem->getCode()}" /></td>
							</if>
							<if="$vsSettings->getSystemKey("{$bw->input[0]}_index",1,$bw->input[0])">
							<td class="label_obj" style='text-align:center'>
							{$vsLang->getWords('obj_Index', 'Index')}
							</td>
							<td>
								<div style="padding:2px 0px; float:left;">
									<input size="5" type="text" name="wincardIndex" id="wincardIndex" value="{$objItem->getIndex()}" class="numeric" />
								</div>
							</td>
							</if>
						</tr>
						<tr class='smalltitle'>
							<if="$vsSettings->getSystemKey("{$bw->input[0]}_price",1,$bw->input[0])">
							<td class="label_obj">{$vsLang->getWords('obj_price', 'Price')}: </td>
							<td><input size="40" type="text" name="wincardPrice" value="{$objItem->getPrice()}" /></td>
							</if>
							<if="$vsSettings->getSystemKey("{$bw->input[0]}_image",1,$bw->input[0])">
							<td class="label_obj" style='text-align:center'>
							{$vsLang->getWords('obj_image_link', "Link")}:<input onclick="checkedLinkFile('link');" type="radio" name="link-file" value="link" checked="checked"/><br/>
							</td>
							<td>
								<input size="39" type="text" name="txtlink" id="txtlink""/><br/>
							</td>
							</if>
						</tr>
						<tr class='smalltitle'>
							<if="$vsSettings->getSystemKey("{$bw->input[0]}_active",1,$bw->input[0])">
							<td class="label_obj">{$vsLang->getWords('obj_status', 'Trạng thái')}:</td>
							<td>
							{$vsLang->getWords('obj_Status_Hide', 'Hide')}
		            			<input name="wincardStatus" type="radio"  class='checkbox' value="0" />
		            			{$vsLang->getWords('obj_Status_Display', 'Display')}
		            			<input name="wincardStatus" type="radio"  class='checkbox' value="1" />
		            			<if="$vsSettings->getSystemKey("{$bw->input[0]}_status_special",1,$bw->input[0])">
		            			{$vsLang->getWords('obj_Status_Special', 'Special')}
		            			<input name="wincardStatus" type="radio"  class='checkbox' value="2" />
		            			</if>
							</td>
							</if>
							<if="$vsSettings->getSystemKey("{$bw->input[0]}_image",1,$bw->input[0])">
							<td class="label_obj" style='text-align:center'>
							{$vsLang->getWords('obj_image_file', "File")}:<input onclick="checkedLinkFile('file');" type="radio" name="link-file" value="file" />
							</td>
							<td>
								<div style="padding: 0px;">
									<input size="27" type="file" name="wincardIntroImage" id="wincardIntroImage" />
								</div>
							</td>
							</if>
						</tr>
						<tr class='smalltitle' style="vertical-align:top">
							<if="$vsSettings->getSystemKey("{$bw->input[0]}_status",1,$bw->input[0])">
							<td >{$vsLang->getWords('obj_state', 'Tình trạng')}:</td>
							<td><input  type="text" name="wincardState" value="{$objItem->getState()}" id="obj-title"/>
							</td>
							</td>
							
							</if>
							<td valgin="center" colspan="2" rowspan="2">
								<if="$vsSettings->getSystemKey("{$bw->input[0]}_image",1,$bw->input[0])">
								<div style="float:right; border: 1px solid;" id="td-obj-image">{$objItem->createImageCache($objItem->getImage(),100,100)}</div>
								</if>
							</td>
						</tr>
						<tr class='smalltitle'>
							<td>{$vsLang->getWords('obj_option', 'Loại')}:</td>
							<td><a class="ui-state-default ui-corner-all" href="javascript:vsf.popupGet('wincards/display-option/{$objItem->getId()}','panel',587)" style="float:left; padding:3px 5px;" >
								{$vsLang->getWords('obj_more_opt', 'Loại')}
								</a>
							</td>
						</tr>
						<if="$vsSettings->getSystemKey("{$bw->input[0]}_intro",1,$bw->input[0])">
						<tr class='smalltitle'>
							
							<td colspan="4">
							{$vsLang->getWords('obj_Intro', 'Intro')}:
							</td>
							
						</tr>
						</if>
						<if="$vsSettings->getSystemKey("{$bw->input[0]}_content",1,$bw->input[0])">
						<tr class='smalltitle'>
							<td colspan="4" align="center">{$objItem->getContent()}</td>
						</tr>
						</if>
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
					checkedLinkFile('link');
					vsf.jRadio('{$active}','wincardStatus');
					vsf.jSelect('{$objItem->getCatId()}','obj-category');
				
				});
				$('#txtlink').change(function() {
					var img_html = '<img src="'+$(this).val()+'" style="width:100px; max-height:115px;" />'; 
					$('#td-obj-image').html(img_html);
				});
				$('#wincardIntroImage').change(function() {
					var img_name = '<input type="hidden" id="image-name" name="image-name" value="'+$(this).val() +'"/>';
					$('#td-obj-image').html(img_name);
				});
					
				function checkedLinkFile(value){
					if(value=='link'){
						$("#txtlink").removeAttr('disabled');
						$("#wincardIntroImage").attr('disabled', 'disabled');
					}else{
						$("#txtlink").attr('disabled', 'disabled');
						$("#wincardIntroImage").removeAttr('disabled');
					}
				}
				
				$('#add-edit-obj-form').submit(function(){
					var flag  = true;
					var error = "";
					var categoryId = "";
					var count=0;
					$("#obj-category option").each(function () {
						count++;
					});
					
					$("#obj-category option:selected").each(function () {
						categoryId += $(this).val() + ",";
					});
					categoryId = categoryId.slice(0, -1);
					$('#obj-cat-id').val(categoryId);
					
					if(($('#obj-category').val()=="" || $('#obj-category').val()==0) &&count>1){
						error = "<li>{$vsLang->getWords('not_select_category', 'Vui lòng chọn danh mục!!!')}</li>";
						flag  = false;
						$('#obj-category').addClass('ui-state-error ui-corner-all-inner');
					}
					
					var title = $("#obj-title").val();
					if(title == 0 || title == ""){
						error += "<li>{$vsLang->getWords('null_title', 'Tiêu đề không được trống !!!')}</li>";
						flag  = false;
						$('#obj-title').addClass('ui-state-error ui-corner-all-inner');
					}
					

					
					if(!flag){
						error = "<ul class='ul-popu'>" + error + "</ul>";
						vsf.alert(error);
						return false;
					}
					$('#obj-category').removeClass('ui-state-error ui-corner-all-inner');
					vsf.uploadFile("add-edit-obj-form", "{$bw->input[0]}", "add-edit-obj-process", "obj-panel", "wincard");
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
				        <td >
					        <if="$data['list']">
						        <select  multiple="" size="18" style="width: 100%;" id="obj-category">
						        	<option  value="0">Root</option>
							       <foreach="$data['list'] as $obj">
							       		<if="$obj->getStatus() > time()">
							       	 		<option style="color:blue" value="{$obj->getId()}">| -[{$obj->getId()}]- {$obj->getName()}</option>
							       	 	<else />
											<option value="{$obj->getId()}">| -[{$obj->getId()}]- {$obj->getName()}</option>
							       	 	</if>
							        </foreach>
						        </select>
					        </if>
				        </td>
				    	<td align="center">
				        	<img src="{$bw->vars['img_url']}/view.png" alt="{$vsLang->getWords('view_obj_bt',"View {$bw->input[0]}")}" id="view-obj-bt" />
				            <img src="{$bw->vars['img_url']}/add.png" align="{$vsLang->getWords('add_obj_bt',"Add {$bw->input[0]}")}" id="add-obj-bt" />
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
		$BWHTML .= <<<EOF
			<div class='left-cell'><div id='category-panel'>{$option['categoryList']}</div></div>
			<input type="hidden" id="idCategory" name="idCategory" />
			<div id="obj-panel" class="right-cell">{$option['objList']}</div>
			<div class="clear"></div>
EOF;
		return $BWHTML;
	}

	function mainPage() {
		global $bw, $vsLang,$vsSettings;

		$BWHTML .= <<<EOF
			<div id="page_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all-top">
				<ul id="tabs_nav" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner">
			    	<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
			        	<a href="{$bw->base_url}{$bw->input[0]}/display-obj-tab/&ajax=1"><span>{$vsLang->getWords('tab_obj_objes',"{$bw->input[0]}")}</span></a>
			        </li>
			      
			       
				</ul>
			</div>
EOF;
		return $BWHTML;
	}

	function addEditOptionForm($objItem='',$option=array()){
		global $bw,$vsLang;
		$active = $objItem->getStatus()!=''?$objItem->getStatus():1;
		$BWHTML .= <<<EOF
			<div id="error-message" name="error-message"></div>
			<form id='add-edit-opt-form' name="add-edit-opt-form" method="POST">
				<input type="hidden" name="wincardId" value="{$option['wincardId']}" />
				<input type="hidden" name="optId" value="{$objItem->getId()}" />
				<div class='ui-widget ui-widget-content ui-corner-all'>
					<div class="ui-title ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
						<span class="ui-icon ui-icon-note"></span>
						<span class="ui-dialog-title">{$option['formTitle']}</span>
					</div>
					<table cellpadding="1" cellspacing="1" border="0" class="ui-dialog-content ui-widget-content" style="width:100%;">
						<tr class='smalltitle'>
								<td >{$vsLang->getWords('obj_title', 'Title')}:</td>
								<td colspan="3"><input size="64%" type="text" name="optTitle" value="{$objItem->getTitle()}" id="optTitle"/></td>
						</tr>
						<tr class='smalltitle'>
							<td class="label_obj">{$vsLang->getWords('obj_price', 'Price')}: </td>
							<td><input size="40" type="text" name="optPrice" value="{$objItem->getPrice()}" /></td>
							<td>{$vsLang->getWords('obj_defualt', 'Mặc định')} <input name="optDefault" type="checkbox" id="sa" value="1"  class='checkbox' /></td>
		            			
						</tr>
						<tr class='smalltitle'>
							<td>{$vsLang->getWords('obj_status', 'Trạng thái')}:</td>
							<td colspan="2">
								{$vsLang->getWords('obj_Status_Hide', 'Hide')}
		            			<input name="optStatus" type="radio"  class='checkbox' value="0" />
		            			{$vsLang->getWords('obj_Status_Display', 'Display')}
		            			<input name="optStatus" type="radio"  class='checkbox' value="1" />
							</td>
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
vsf.jRadio('{$active}','optStatus');
					vsf.jCheckbox('{$objItem->getDefault()}','sa');				
				$('#add-edit-opt-form').submit(function(){
					var title = $("#optTitle").val();
					var flag = true;
					var error = "";
					if(title == 0 || title == ""){
						error += "<li>{$vsLang->getWords('null_title', 'Tiêu đề không được trống !!!')}</li>";
						flag  = false;
					}
					
					if(!flag){
						error = "<ul class='ul-popu'>" + error + "</ul>";
						vsf.alert(error);
						return false;
					}
					vsf.submitForm($("#add-edit-opt-form"), "wincards/addEditOption", "opt-panel");
					vsf.get('wincards/addOption/','opt-form')
					return false;
				});
			</script>
EOF;
		return $BWHTML;
	}

	function mainwincardOpt($option) {
		$BWHTML .= <<<EOF
			<div id="opt-form">{$option['objForm']}</div>
			<div id="opt-panel">{$option['objList']}</div>
			<div class="clear"></div>
EOF;
		return $BWHTML;
	}
	
	function displayListOption($objItems){
		global $vsLang;
		$BWHTMl .= <<<EOF
			<div class='ui-widget ui-widget-content ui-corner-all' style="margin-top:15px;">
				    <div class="ui-title ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
				        <span class="ui-icon ui-icon-note"></span>
				        <span class="ui-dialog-title">{$vsLang->getWords('wincard_opt_title',"Danh sách các loại")}</span>
				    </div>
					<table cellspacing="1" cellpadding="1" id='objListHtmlTable' width="100%">
						<thead>
						    <tr>
						        <th width="20">{$vsLang->getWords('obj_list_status', 'Trạng Thái')}</th>
						        <th>{$vsLang->getWords('obj_list_title', 'Title')}</th>
						        <th>{$vsLang->getWords('obj_list_price', 'Giá')}</th>
						        <th width="20">{$vsLang->getWords('obj_list_defualt', 'Giá mặc định')}</th>
						        <th width="75">{$vsLang->getWords('obj_list_option', 'Tùy chọn')}</th>
						    </tr>
						</thead>
						<tbody>
							<if="count($objItems)">
							<foreach="$objItems as $key=>$obj">
								<php>
								if(is_string($obj))
									$obj = unserialize($obj);
								</php>
								<tr class="$vsf_class">
									<td style='text-align:center'>{$obj->getStatus('image')}</td>
									<td>
										{$obj->getTitle()}
									</td>
									<td>
									{$obj->getPrice()}
									</td>
									<td algin="center">{$obj->getDefault()}</td>
									<td align="center">
										<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" onclick="vsf.get('wincards/editOption/{$obj->getwincardId()}/{$key}','opt-form')">Sửa</a>
									<a href="javascript:;" onclick="vsf.get('wincards/delOption/{$obj->getwincardId()}/{$key}','opt-panel')" class="ui-state-default ui-corner-all ui-state-focus">
										Xóa
									</a>
									</td>
								</tr>
							</foreach>
							</if>
						</tbody>
					</table>
				</div>
			<div class="clear" id="file"></div>
EOF;
		return $BWHTML;
	}
}
?>