<?php
class skin_users {
	
	function MainPage() {
		global $bw, $vsLang, $vsSettings;
		$BWHTML = "";
		$BWHTML .= <<<EOF
<script></script>
<div id="page_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all-top">
	<ul id="tabs_nav" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all-inner">
    	<li class="ui-state-default ui-corner-top">
        	<a href="{$bw->base_url}users/display-obj-tab/&ajax=1">{$vsLang->getWords('tab_user_users','Users')}</a></li>
        <if="$vsSettings->getSystemKey($bw->input[0].'_Group_tab',1)">	
		<li class="ui-state-default ui-corner-top">
        	<a href="{$bw->base_url}users/display-group-tab/&ajax=1">{$vsLang->getWords('tab_user_groups','User Groups')}</a></li>
        </if>	
        <if="$vsSettings->getSystemKey($bw->input[0].'_setting_tab',1)">	
        <li class="ui-state-default ui-corner-top">
        	<a href="{$bw->base_url}settings/moduleObjTab/{$bw->input[0]}/&ajax=1">{$vsLang->getWords('tab_user_SS','SystemSetting')}</a></li>
        </if>	
        <if="$vsSettings->getSystemKey($bw->input[0].'_provice_tab',1)">	
        <li class="ui-state-default ui-corner-top">
        	<a href="{$bw->base_url}menus/display-category-tab/provice/&ajax=1"><span>{$vsLang->getWords('tab_obj_categories_provice','categories-provice')}</span></a>
        </if>	
		<div class="clear"></div>
</ul>
<div class="clear"></div>
</div>
<div id="temp"></div>

EOF;
		return $BWHTML;
	}
	
	function objListHtml($option) {
		global $vsLang, $bw,$vsSettings;
		$BWHTML = "";
		$count = 0;
$BWHTML .= <<<EOF
<script type='text/javascript'>

	function checkObject() {
		var checkedString = '';
		$("input[class=myCheckbox]").each(function(){
			if(this.checked) checkedString += $(this).val()+',';
		});
		$('#checked-obj').val(checkedString);
	}
	
	$('#hide-objlist-bt').click(function() {
		if($('#checked-obj').val()=='') {
			jAlert(
				"{$vsLang->getWords('visible_obj_confirm_hide', "You haven't choose any items to hide!")}",
				"{$bw->vars['global_websitename']} Dialog"
			);
			return false;
		}
		vsf.submitForm($('#obj-list-form'),'{$bw->input[0]}/hide-checked-obj/','obj-list');
	});
	
	$('#visible-objlist-bt').click(function() {
		if($('#checked-obj').val()=='') {
			jAlert(
				"{$vsLang->getWords('visible_obj_confirm_noitem', "You haven't choose any items to visible!")}",
				"{$bw->vars['global_websitename']} Dialog"
			);
			return false;
		}
		vsf.submitForm($('#obj-list-form'),'{$bw->input[0]}/visible-checked-obj/','obj-list');
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
					vsf.submitForm($('#obj-list-form'),'{$bw->input[0]}/delete-checked-obj/','obj-list');
					vsf.get('{$bw->input[0]}/add-obj-form/','obj-panel');
				}
			}
		);
	});
	
	$(document).ready(function(){
		<if="$option['message']">
			vsf.alert("{$option['message']}");
		</if>
	});
	function checkAllU() {
		var checked_status = $("input[name=allU]:checked").length;
		var checkedString = '';
		$("input[type=checkbox]").each(function(){
			if($(this).hasClass('myCheckboxU')){
			this.checked = checked_status;
			if(checked_status) checkedString += $(this).val()+',';
			}
		});
		$("span[acaica=myCheckboxU]").each(function(){
			if(checked_status)
				this.style.backgroundPosition = "0 -50px";
			else this.style.backgroundPosition = "0 0";
		});
		$('#checked-obj').val(checkedString);
	}
	function checkObjectU() {
		var checkedString = '';
		$("input[type=checkbox]").each(function(){
			if($(this).hasClass('myCheckboxU')){
				if(this.checked) checkedString += $(this).val()+',';
			}
		});
		checkedString = checkedString.substr(0,checkedString.lastIndexOf(','));
		$('#checked-obj').val(checkedString);
	}
	function deleteObj(id){
		$('#checked-obj').val(id);
		$('#delete-objlist-bt').click();
	}
</script>
<form id="obj-list-form">
	<input type="hidden" name="checkedObj" id="checked-obj" value="" />
	<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
		<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
			<span class="ui-dialog-title">{$vsLang->getWords('users_list','List of users')}</span>
		</div>
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">
	        <li class="ui-state-default ui-corner-top" id="hide-objlist-bt"><a href="#" title="{$vsLang->getWords('hide_obj_alt_bt',"Hide selected {$bw->input[0]}")}">{$vsLang->getWords('hide_obj_bt','Hide')}</a></li>
	        <li class="ui-state-default ui-corner-top" id="visible-objlist-bt"><a href="#" title="{$vsLang->getWords('visible_obj_alt_bt',"Visible selected {$bw->input[0]} ")}">{$vsLang->getWords('visible_obj_bt','Visible')}</a></li>
	        <li class="ui-state-default ui-corner-top" id="delete-objlist-bt"><a href="#" title="{$vsLang->getWords('delete_obj_alt_bt',"Delete selected {$bw->input[0]}")}">{$vsLang->getWords('delete_obj_bt','Delete')}</a></li>
	        <li class="ui-state-default ui-corner-top" id="export-objlist-bt"><a href="{$bw->base_url}users/export" title="{$vsLang->getWords('delete_obj_alt_bt',"Delete selected {$bw->input[0]}")}">{$vsLang->getWords('Expotre_bt','Exports Excels')}</a></li>
	    </ul>
		<table  cellpadding="1" cellspacing="1" style="width:100%">
			<thead>
				<tr>
					<th width='10'>	<input type="checkbox" onclick="checkAllU()" onclicktext="checkAllU()" name="allU" /></th>
					<th width='50'>{$vsLang->getWords('user_name_status',"Hiển thị")}</th>						
					<th width='150'>{$vsLang->getWords('user_name_title',"Username")}</th>
					<th>{$vsLang->getWords('user_email_title',"Email")}</th>
					<th width='100'>{$vsLang->getWords('user_join_date',"Joined date")}	</th>
				</tr>
			</thead>
			<tbody>
				<if="count($option['pageList'])">
				<foreach="$option['pageList'] as $obj">
					<php> 
						$count++;
						if($count%2)
                               $class='old';
                       	else $class= 'even';
           			</php>     
			    	<tr class='$class'>
						<td width='10'>
							<input type="checkbox" onclicktext="checkObjectU({$obj->getId()});" onclick="checkObjectU({$obj->getId()});" name="obj_{$obj->getId()}" value="{$obj->getId()}" class="myCheckboxU" />
						</td>						
						<td style="text-align:center">{$obj->getStatus('image')}</td>
						<if="$obj->getDealer()">
						<td style="font-weight:bold">
						<else />
						<td>
						</if>
							<a href="javascript:vsf.get('{$bw->input[0]}/edit-obj-form/{$obj->getId()}/','obj-panel')" title='{$vsLang->getWords('newsItem_EditObjTitle',"Click here to edit this {$bw->input[0]}")}'>
                               {$obj->getName()}
                            </a>
                        </td>
						<td>{$obj->getEmail()}</td>
						<td width='75'>	{$obj->getJoindate("SHORT")}</td>
					</tr>
				</foreach>
				<tr>
					<td colspan='6' style='text-align: right;'>	{$option['paging']}</td>
				</tr>
				</if>
			</tbody>
		</table>
	</div>
</form>
EOF;
		
		return $BWHTML;
	}
	
	function addEditObjForm($obj, $option = array()) {
		global $vsLang, $bw, $vsSettings;
	
		$BWHTML .= <<<EOF
			<div id="error-message" name="error-message"></div>
			<form id='form-obj' name="add-edit-obj-form" method="POST"  enctype='multipart/form-data'>
				<input type="hidden" name="userId" value="{$obj->getId()}" />
				<input type="hidden" name="group" id="group" value="" />
				<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
					<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
						<span class="ui-dialog-title">{$option['formTitle']}</span>
					</div>
					<table class="ui-dialog-content ui-widget-content">
						<tr>
							<if="$vsSettings->getSystemKey($bw->input[0].'_name',1)">
							<td class="label_obj">{$vsLang->getWords('obj_name', 'User Name')}:</td>
								<if="$obj->getName()">
									<td colspan="2"><input size="25" type="text" name="userName"  value="{$obj->getName()}" id="obj-title" disabled /></td>
								<else />
									<td colspan="2"><input size="25" type="text" name="userName"  value="" id="obj-title"/></td>
								</if>
							</if>
						</tr>
						
						<tr>
							<if="$vsSettings->getSystemKey($bw->input[0].'_password',1)">
							<td class="label_obj">{$vsLang->getWords('obj_password', 'Password')}:</td>
							<td colspan="2"><input size="25" type="password" name="userPassword" value="" id="obj-password"/></td>
							</if>
						</tr>
						<tr>
							<if="$vsSettings->getSystemKey($bw->input[0].'_birthday',1)">
							<td class="label_obj">{$vsLang->getWords('obj_birthday', 'Birthday')}:</td>
							<td colspan="2"><input size="25" type="text" name="userBirthday" value="{$obj->getBirthday()}" id="obj-birthday"/></td>
							</if>
						</tr>
						<tr>
							<if="$vsSettings->getSystemKey($bw->input[0].'_adress',1)">
							<td class="label_obj">{$vsLang->getWords('obj_address', 'Address')}:</td>
							<td colspan="2"><input size="25" type="text" name="userAddress" value="{$obj->getAddress()}" id="obj-address"/></td>
							</if>
						</tr>
						<tr>
							<if="$vsSettings->getSystemKey($bw->input[0].'_email',1)">
							<td class="label_obj">{$vsLang->getWords('obj_email', 'Email')}:</td>
							<td colspan="2"><input size="25" type="text" name="userEmail" value="{$obj->getEmail()}" id="obj-email"/></td>
							</if>
						</tr>
						
						<tr >
							<if="$vsSettings->getSystemKey($bw->input[0].'_status',1)">
								<td class="label_obj">{$vsLang->getWords('obj_show', 'Hiển thị')}:</td>
								<td colspan="3">
									<input class='c_noneWidth' type="checkbox" name="userStatus" id="userStatus" value='1' style='margin-right:10px;'/>
								</td>
							</if>
						</tr>
						
						<tr >
							<if="$vsSettings->getSystemKey($bw->input[0].'_phone',1)">
							<td class="label_obj">{$vsLang->getWords('obj_phone', 'Phone')}:</td>
							<td colspan="2"><input class='c_noneWidth' type="text" name="userPhone" value="{$obj->getPhone()}" style='margin-right:10px;'/></td>
							</if>
						</tr>
						
						<tr >
							<if="$vsSettings->getSystemKey($bw->input[0].'_gender',1)">
							<td class="label_obj">{$vsLang->getWords('obj_gender', 'Gender')}:</td>
							<td colspan="2">
							{$vsLang->getWords('obj_female', 'Female')}   	<input class='c_noneWidth' type="radio" name="userGender"  checked value="0" style='margin-right:10px;'/>
							{$vsLang->getWords('obj_male', 'Male')} 		<input class='c_noneWidth' type="radio" name="userGender"   value="1" style='margin-right:10px;'/>
							<!-- <input class='c_noneWidth' type="text" name="userGender" value="{$obj->getGender()}" style='margin-right:10px;'/> -->
							</td>
							</if>
						</tr>
						<tr >
							<if="$vsSettings->getSystemKey($bw->input[0].'_company',1)">
							<td class="label_obj">{$vsLang->getWords('obj_company', 'Company')}:</td>
							<td colspan="2"><input class='c_noneWidth' type="text" name="userCompany" value="{$obj->getCompany()}" style='margin-right:10px;'/></td>
							</if>
						</tr>
						<tr >
							<if="$vsSettings->getSystemKey($bw->input[0].'_fax',1)">
							<td class="label_obj">{$vsLang->getWords('obj_fax', 'Fax')}:</td>
							<td colspan="2"><input class='c_noneWidth' type="text" name="userFax" value="{$obj->getFax()}" style='margin-right:10px;'/></td>
							</if>
						</tr>
						<tr>
							<if="$vsSettings->getSystemKey($bw->input[0].'_skype',1)">
							<td class="label_obj">{$vsLang->getWords('obj_skype', 'Nick Skype')}:</td>
							<td colspan="2"><input class='c_noneWidth' type="text" name="userSkype" value="{$obj->getSkype()}" style='margin-right:10px;'/></td>
							</if>
						</tr>
						<tr>
							<if="$vsSettings->getSystemKey($bw->input[0].'_yahoo',1)">
							<td class="label_obj">{$vsLang->getWords('obj_yahoo', 'Nick Yahoo')}:</td>
							<td colspan="2"><input class='c_noneWidth' type="text" name="userYahoo" value="{$obj->getYahoo()}" style='margin-right:10px;'/></td>
							</if>
						</tr>
						<tr>
							<if="$vsSettings->getSystemKey($bw->input[0].'_mobile',1)">
							<td class="label_obj">{$vsLang->getWords('obj__mobile', 'Mobile')}:</td>
							<td colspan="2"><input class='c_noneWidth' type="text" name="userMobile" value="{$obj->getYahoo()}" style='margin-right:10px;'/></td>
							</if>
						</tr>
						<if="$vsSettings->getSystemKey($bw->input[0].'_group',1)&&count($option['group'])">
						<tr>
							<td colspan="3" align="left">	{$vsLang->getWords('obj_group','Group')}</td>
						</tr>
						<tr>
							<td colspan="3" align="left">
							<if="count($option['group'])">
							<foreach="$option['group'] as $group">
								<p style="width:130px" class="benifit">
									<span>{$group->getTitle()}</span>
									<input type="checkbox" class="myCheckboxG" value="{$group->getId()}" id="group{$group->getId()}" name="group[{$group->getId()}]" >
								</p>
							</foreach>		
							</if>
							</td>
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
				function checkObject() {
					var checkedString = '';
					$("input[type=checkbox]").each(function(){
						if($(this).hasClass('myCheckboxG')){
							if(this.checked) checkedString += $(this).val()+',';
						}
					});
					checkedString = checkedString.substr(0,checkedString.lastIndexOf(','));
					$('#group').val(checkedString);
				}
				function updateobjListHtml(categoryId){
					vsf.get('{$bw->input[0]}/display-obj-list/'+categoryId+'/','obj-panel');
				}
				function alertError(message){
					jAlert(
						message,
						'{$bw->vars['global_websitename']} Dialog'
					);
				}
				$(window).ready(function() {
					vsf.jRadio('{$obj->getGender()}','userGender');
					vsf.jCheckbox('{$obj->getStatus()}','userStatus');
					vsf.jCheckbox('{$obj->getDealer()}','userDealer');
					<if="count($option['cur_groups'])">
						<foreach="$option['cur_groups'] as $key=>$group">
						vsf.jCheckbox('{$key}',"group{$key}");
						</foreach>
					</if>
					<if="$obj->getProvince()">
						vsf.jSelect("{$obj->getProvince()}",'userProvince');
				 	</if>
				});
				$('#form-obj').submit(function(){
					var flag  = true;
					var error = "";
					var categoryId;
					var count=0;
					$("#obj-category option:selected").each(function () {
						categoryId = $(this).val();
						count=1;
					});
					$('#obj-cat-id').val(categoryId);
					
					if(categoryId == null && count){
						error = "<li>{$vsLang->getWords('not_select_category', 'Vui lòng chọn category!!!')}</li>";
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
					checkObject();
					vsf.uploadFile("form-obj", "{$bw->input[0]}", "add-edit-obj", "obj-list","users");
					vsf.get('users/add-obj-form','obj-panel');
					return false;
				});
				
			</script>
EOF;
		
	}
	
	function mainUserHtml($userFormHTML, $usertable = "", $message = "") {
		$BWHTML = "";
		$BWHTML .= <<<EOF
<div id="vswrapper"></div>
	{$message}
<div class="left-cell" id="obj-panel">
	<div id="user-form">{$userFormHTML}</div>
</div>
<div class="right-cell" id="obj-list">
	{$usertable}
</div>
<div class="clear"></div>
EOF;
		return $BWHTML;
	}
	
	function groupUserBox($groupOption = array()) {
		global $vsLang;
		$BWHTML = "";
		$BWHTML .= <<<EOF
<script type="text/javascript">
	var chosenGroup = "";
	$('#bt-view-user').click(function(){
		if(chosenGroup==0 || chosenGroup=="") {
		alert('{$vsLang->getWords('err_admin_choose_group',"Please choose a group to view admin in it!")}');
		$('#group-users').addClass('ui-state-error');	
		return false;
		}
		$('#group-users').removeClass('ui-state-error');
		vsf.get('users/get-object-list/'+chosenGroup+'/','user-table');
	});
	
	$('#bt-view-Alluser').click(function(){
		vsf.get('users/get-object-list/all/','user-table');
	});
	
	$('#group-users').change(function() {
		var str = "";
		$("#group-users option:selected").each(function () {
			str += $(this).val() + ",";
		});
		chosenGroup = str.substr(0,str.length-1);
		$("#group-id").val(chosenGroup);
		$('#td-chosen-group-user').html('{$vsLang->getWords('group_box_chosen','Chosen groups')}: '+chosenGroup);
	});
</script>
<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
	<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
		<span class="ui-dialog-title">{$vsLang->getWords('group_box_title','Group Admin List')}
	</div>
	<div class='pandog'>
		<span id="td-chosen-group-user">
		{$vsLang->getWords('group_box_chosen','Chosen groups')}: {$vsLang->getWords('group_box_chosen_none','None')}
		</span>
	</div>
	<table  cellpadding="0" cellspacing="0">			    
		<tr>
			<td class="ui-dialog-selectpanel">
			<select style='width:100%;' id="group-users" name="groupUsers" multiple>
				<foreach="$groupOption as $group">
					<option value="{$group->getId()}"  >{$group->getTitle()}</option>
				</foreach>
			</select>
			</td>
		</tr>
		<tr>
			<td valign="middle" class="ui-dialog-buttonpanel">
			<input id="bt-view-Alluser" type="button" value="{$vsLang->getWords('group_box_bt_viewAll','View All users')}" />
			<input id="bt-view-user" type="button" value="{$vsLang->getWords('group_box_bt_view','View users')}" />
			</td>
		</tr>
	</table>
</div>
EOF;
		return $BWHTML;
	}
	
	//------------------------------------------------
	// USER GROUP ZONE
	//------------------------------------------------
	function groupTable($groupList = array(), $message = "") {
		global $vsLang, $bw;
		$count = 1;
		$BWHTML = "";
		//--starthtml--//
		

		$BWHTML .= <<<EOF
<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
<span class="ui-dialog-title">{$vsLang->getWords('group_list','List of groups')}
</div>
<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">
    <li class="ui-state-default ui-corner-top" id="add-objlist-bt"><a href="#" title="{$vsLang->getWords('add_obj_alt_bt',"Add {$bw->input[0]}")}">{$vsLang->getWords('add_obj_alt_bt',"Add {$bw->input[0]}")}</a></li>
    <li class="ui-state-default ui-corner-top" id="delete-objlist-btG"><a href="#" title="{$vsLang->getWords('delete_obj_alt_bt',"Delete selected {$bw->input[0]}")}">{$vsLang->getWords('delete_obj_bt','Delete')}</a></li>
    </ul>
<div class="red">{$message}</div>
<table  cellpadding="0" cellspacing="0" style="width:100%">
<thead>
<tr>
<th width="15"><input type="checkbox" onclick="checkAll()" onclicktext="checkAll()" name="all" /></th>
<th>{$vsLang->getWords('group_header_id','Group ID')}</th>
<th>{$vsLang->getWords('group_header_name','Name')}</th>
<th>{$vsLang->getWords('group_header_description','Description')}</th>
<th>{$vsLang->getWords('global_options','Options')}</th>
</tr>
</thead>
<tbody>
	<foreach="$groupList as $group">
	<php> 
	$count++;
	if($count%2)
	        $class='old';
	else $class= 'even';
    </php>     
		<tr class='$class'>
			<td style="text-align:center">
				<input type="checkbox" onclicktext="checkObject({$group->getId()});" onclick="checkObject({$group->getId()});" name="obj_{$group->getId()}" value="{$group->getId()}" class="myCheckbox" />
			</td>
			<td>{$group->getId()}</td>
			<td>
				<a title="{$vsLang->getWords('global_a_title_edit',"Edit this")}_{$group->getTitle()}" href="javascript:vsf.get('users/edit-group/{$group->getId()}/','user-group-form');">
		        	{$group->getTitle()}
		        </a>
	        </td>
			<td>{$group->getIntro()}</td>
			<td>
				<a title="{$vsLang->getWords('global_a_title_edit',"Edit this")}" href="javascript:vsf.get('users/edit-group/{$group->getId()}/','user-group-form');">
					<img alt="{$vsLang->getWords('global_bt_alt_edit','Edit this')}" src="{$bw->vars['img_url']}/edit.png" />
				</a>
				<a title="{$vsLang->getWords('global_a_title_delete',"Delete this")}" href="javascript:deleteUserS({$group->getId()})">
					<img alt="{$vsLang->getWords('global_bt_alt_delete','Delete this')}" src="{$bw->vars['img_url']}/del.png" />
				</a>
			</td>
		</tr>
	</foreach>
</tbody>
</table>
</div>
<script>
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
	$('#checked-obj').val(checkedString);
}

$('#add-objlist-bt').click(function(){	
	vsf.get('{$bw->input[0]}/edit-group/','user-group-form');
});
$('#delete-objlist-btG').click(function(){	
	
	deleteUserS($('#checked-obj').val());
});



function deleteUserS(id){
jConfirm(
		'{$vsLang->getWords("contact_deleteContactConfirm","Are you sure to delete this contact information?")}', 
		'{$bw->vars['global_websitename']} Dialog', 
		function(r){
		if(r){
			
			vsf.get('users/delete-user-group/'+id+'/','user-group-table');
		}
	});
}
</script>
EOF;
		//--endhtml--//
		return $BWHTML;
	}
	
	function addEditGroupForm($form = array(), $group) {
		global $vsLang;
		$BWHTML = "";
		//--starthtml--//
		

		$switchButton = "";
		if ($form ['type'] == 'edit')
			$switchButton = <<<EOF
	<script type="text/javascript">
		$('#user-group-switch-bt').click(function() {
		vsf.get('users/add-group','user-group-form');
		});
	</script>
	<input class="ui-state-default ui-corner-all-inner" type="button" value="{$vsLang->getWords('user_bt_switch',"Switch to add form")}" id="user-group-switch-bt" />
EOF;

$BWHTML .= <<<EOF
<script type="text/javascript">
$('#add-edit-group').submit(function() {

	if(!$('#group-user-name').val()) {
		alert('{$vsLang->getWords('err_group_name_blank','Please enter the group name!')}');
		$('#group-user-name').addClass('ui-state-error ui-corner-all-inner');
		$('#group-user-name').focus();
		return false;
	}
	vsf.submitForm($(this),'users/add-group/','user-group-form');
	vsf.get('users/display-list-group/','user-group-table');
	return false;
});
	<if="$form['message']">
		vsf.alert('{$form['message']}');
	</if>
</script>
<form id="add-edit-group">
	<input type="hidden" name="formType" value="{$form['type']}" />
	<input type="hidden" name="groupId" value="{$group->getId()}" />
	<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
		<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
		<span class="ui-dialog-title">{$form['title']}</span></div>
		<table  cellpadding="0" cellspacing="0">
			<tr>
				<td>{$vsLang->getWords('group_header_name','Group name')}</td>
				<td><input id="group-user-name" style="width:203px;" type="text" name="groupTitle" value="{$group->getTitle()}" /></td>
			</tr>
			<tr>
				<td>{$vsLang->getWords('group_header_description','Description')}</td>
				<td><textarea rows="7" cols="23" name="groupIntro">{$group->getIntro()}</textarea></td>
			</tr>
			<tr>
				<td colspan="2" align="center" class="ui-dialog-buttonpanel">
				<input class="ui-state-default ui-corner-all-inner" type="submit" value="{$form['submit']}" />
				{$switchButton}
				</td>
			</tr>
		</table>
	</div>
</form>
EOF;
		
		//--endhtml--//
		return $BWHTML;
	}
	
	function mainGroup($addEditGroupHTML = "", $groupTableHTML = "") {
		$BWHTML = "";
		$BWHTML .= <<<EOF
<div class="left-cell" id="user-group-form">{$addEditGroupHTML}</div>
<div class="right-cell" id="user-group-table">{$groupTableHTML}</div>
<div class="clear"></div>
EOF;
		return $BWHTML;
	}
	
	function mainPermission($modulelist = "", $groupbox = "") {
		global $bw, $vsLang;
		$BWHTML = "";
		//--starthtml--//
		$BWHTML .= <<<EOF
<script type="text/javascript">
$('#bt_setperm').click(function() {
var error = "";

if(permGroup=="") {
error += '{$vsLang->getWords('err_perm_choose_module',"Please choose group!")}<br />';
$('#adminperm_group').addClass('ui-state-error');
}
else { $('#adminperm_group').removeClass('ui-state-error'); }

if(permModule=="") {
error += '{$vsLang->getWords('err_perm_choose_group',"Please choose module!")}<br />';
$('#adminperm_module').addClass('ui-state-error');
}
else { $('#adminperm_module').removeClass('ui-state-error'); }

if(error!="") {
$('#permbox_message').html(error);
return;
}

$('#permbox_message').html('');
vsf.get('users/getpermission/'+permModule+'/'+permGroup+'/','perm_list');
});
</script>
<div id="perm_modulebox" class="left-cell" style="width:210px !important; padding:0px 5px">{$modulelist}</div>
<div id="perm_groupbox" class="left-cell" style="width:210px !important; padding:0px 5px">{$groupbox}</div>
<div style="float:left; padding:0px 10px" class="ui-dialog">
<div class="ui-dialog-buttonpanel">
<input id="bt_setperm" type="button" value="{$vsLang->getWords('perm_bt_set','Set Permission')}" />
</div>
<div class="red" id="permbox_message"></div>
</div>
<div id="perm_list" class="left-cell" style="width:210px !important; padding:0px 5px"></div>
<div class="clear"></div>
EOF;
		//--endhtml--//
		return $BWHTML;
	}
	
	function permGroupBox($groupOption = "", $message = '') {
		global $vsLang;
		$BWHTML = "";
		$BWHTML .= <<<EOF
<script type="text/javascript">
var permGroup = "";
$('#adminperm_group').change(function() {
var str ="";
$("#adminperm_group option:selected").each(function () {
str = $(this).val();
});
permGroup = str;
});
</script>
<div class="ui-dialog ui-widget ui-widget-content ui-corner-all" style="width:200px !important;">
<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
<span class="ui-dialog-title">{$vsLang->getWords('group_box_title','Group Admin List')}
</div>
<div class="red">{$message}</div>
<table  cellpadding="0" cellspacing="0">
<tr>
<td class="ui-dialog-selectpanel">
	<select size='15' id="adminperm_group" name="adminperm_group" multiple>
	<if="count($groupOption)">
		<foreach="$groupOption as $group">
		<option value="{$group->getId()}" {$selected} >{$group->getName()}</option>
		</foreach>
	</if>
	</select>
</td>
</tr>
</table>
</div>
EOF;
		return $BWHTML;
	}

	/**
	 * Gift
	 */
	
	function addEditGiftForm($objItem,$option){
		global $vsLang,$bw;
		
		$BWHTML .= <<<EOF
			<div id="error-message" name="error-message"></div>
			<form id='add-edit-opt-form' name="add-edit-opt-form" method="POST">
				<input value="{$bw->input[2]}" type="hidden" name="userId">
				<input value="{$objItem['objectId']}" type="hidden" name="objId">
				<div class='ui-widget ui-widget-content ui-corner-all'>
					<div class="ui-title ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
						<span class="ui-icon ui-icon-note"></span>
						<span class="ui-dialog-title">{$option['formTitle']}</span>
					</div>
					<table cellpadding="1" cellspacing="1" border="0" class="ui-dialog-content ui-widget-content" style="width:100%;">
						<tr class='smalltitle'>
								<td >{$vsLang->getWords('obj_title_gift', 'Nội dung đổi quà')}:</td>
								<td colspan="3"><input size="64%" type="text" name="giftInfo" value="{$objItem['giftInfo']}" id="giftInfo"/></td>
						</tr>
						<tr class='smalltitle'>
							<td class="label_obj">{$vsLang->getWords('obj_tick', 'Điểm')}: </td>
							<td><input size="40" type="text" name="giftTick" value="{$objItem['giftTick']}" class="numeric" /> (Vui lòng nhập số) </td>
						</tr>
						<!--<tr class='smalltitle'>
							<td class="label_obj">{$vsLang->getWords('gift_list_sd', 'Số dư')}: </td>
							<td><input size="40" type="text" name="giftBalance" value="{$objItem['giftBalance']}" class="numeric" /> (Vui lòng nhập số)</td>
						</tr>-->
						<tr class='smalltitle'>
							<td class="ui-dialog-buttonpanel" colspan="4" align="center">
								<input type="submit" name="submit" value="{$option['formSubmit']}"  />
							</td>
						</tr>
					</table>
				</div>
			</form>
			<script language="javascript">
				$(window).ready(function() {
					$("input.numeric").numeric();
				});
				$('#add-edit-opt-form').submit(function(){
					var title = $("#giftInfo").val();
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
					vsf.submitForm($("#add-edit-opt-form"), "users/add-edit-gift", "opt-panel");
					vsf.get('users/add-edit-gift-form/{$bw->input[2]}','opt-form');
					return false;
				});
			</script>
EOF;
	}
	
	function displayListGift($objItems){
		global $vsLang;		
		if(count($objItems)>9) $height = "235px";	
		$BWHTMl .= <<<EOF
			<div class='ui-widget ui-widget-content ui-corner-all' style="margin-top:15px;">
				    <div class="ui-title ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
				        <span class="ui-icon ui-icon-note"></span>
				        <span class="ui-dialog-title">{$vsLang->getWords('user_gift_title',"Danh sách đổi quà")}</span>
				    </div>
					<table cellspacing="1" cellpadding="1" id='objListHtmlTable' width="100%">
						<thead>
						    <tr>
						        <th width="75">{$vsLang->getWords('gift_list_date', 'Ngày')}</th>
						        <th>{$vsLang->getWords('gift_list_title', 'Nội dung')}</th>
						        <th>{$vsLang->getWords('gift_list_tick', 'Điểm')}</th>
						        <th width="75">{$vsLang->getWords('gift_list_sd', 'Số dư')}</th>
						    </tr>
						</thead>
						<tbody style="height: $height;  overflow-x: hidden;">
							<if="count($objItems)">
								<foreach="$objItems as $key=>$gift">
									<php>
										$gift['giftDate'] = VSFDateTime::getDate($gift['giftDate'],"SHORT");
									</php>
									<tr class="$vsf_class">
										<td style='text-align:center'>{$gift['giftDate']}</td>
										<td>
											<a href="javascript:;" onclick="vsf.get('users/edit-gift/{$gift['relId']}/{$gift['objectId']}','opt-form')" title="Chỉnh sửa">{$gift['giftInfo']}</a>
										</td>
										<td>
											{$gift['giftTick']}
										</td>
										<td algin="center">{$gift["giftBalance"]}</td>
									</tr>
								</foreach>
							</if>
						</tbody>
					</table>
				</div>
EOF;
	}
	
	function displayGift($option){
		$BWHTML .= <<<EOF
			<div id="opt-form">{$option['objForm']}</div>
			<div id="opt-panel">{$option['objList']}</div>
			<div class="clear"></div>
EOF;
	}

}
?>