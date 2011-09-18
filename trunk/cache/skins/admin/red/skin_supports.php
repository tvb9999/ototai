<?php
class skin_supports{

//===========================================================================
// <rsf:objListHtml:desc::trigger:>
//===========================================================================
function objListHtml($objItems=array(),$option=array()) {global $bw, $vsLang, $vsSettings;
$this->arrType = array(1=>"Yahoo",2=>'Skype');

//--starthtml--//
$BWHTML .= <<<EOF
		<div class="red">{$option['message']}</div>
<form id="obj-list-form">
<input type="hidden" name="checkedObj" id="checked-obj" value="" />
<input type="hidden" name="categoryId" value="{$option['categoryId']}" id="categoryId" />
<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
        <span class="ui-icon ui-icon-note"></span>
        <span class="ui-dialog-title">{$vsLang->getWords('obj_objListHtmlTitle',"Support Item List")}</span>
    </div>
    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">
    <li class="ui-state-default ui-corner-top" id="add-objlist-bt">
    <a href="#" title="{$vsLang->getWords('add_obj_alt_bt',"Add")}">
{$vsLang->getWords('add_obj_alt_bt',"Add")}
</a>
</li>
        <li class="ui-state-default ui-corner-top" id="hide-objlist-bt">
        <a href="#" title="{$vsLang->getWords('hide_obj_alt_bt',"Hide")}">
{$vsLang->getWords('hide_obj_bt','Hide')}
</a>
</li>

        <li class="ui-state-default ui-corner-top" id="visible-objlist-bt">
        <a href="#" title="{$vsLang->getWords('visible_obj_alt_bt',"Display")}">
{$vsLang->getWords('visible_obj_bt','Display')}
</a>
</li>

        <li class="ui-state-default ui-corner-top" id="delete-objlist-bt">
        <a href="#" title="{$vsLang->getWords('delete_obj_alt_bt',"Delete")}">
{$vsLang->getWords('delete_obj_bt','Delete')}
</a>
</li>
    </ul>
<table cellspacing="1" cellpadding="1" id='objListHtmlTable' width="100%">
<thead>
    <tr>
        <th width="15"><input type="checkbox" onclick="checkAll()" onclicktext="checkAll()" name="all" /></th>
        <th width="50">{$vsLang->getWords('obj_list_status', 'Status')}</th>
        <th>{$vsLang->getWords('obj_list_title', 'Nick name')}</td>
        <th width="50">{$vsLang->getWords('obj_list_type', 'Type')}</th>
        <th width="50">{$vsLang->getWords('obj_list_index', 'Index')}</th>
        
        
EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_option', 0, "supports", 1, 1) ) {
$BWHTML .= <<<EOF

        <th width="100">{$vsLang->getWords('obj_list_action', 'Option')}</th>
        
EOF;
}

$BWHTML .= <<<EOF

    </tr>
</thead>
<tbody>

EOF;
if( count($objItems) ) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e7418e710058($objItems,$option)}

EOF;
}

$BWHTML .= <<<EOF

</tbody>
                                                <tfoot>
<tr>
<th colspan='5'>
<div style='float:right;'>{$option['paging']}</div>
</th>
</tr>
</tfoot>
</table>
                                        {$option['info']}
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
$('#checked-obj').val(checkedString.substr(0,checkedString.lastIndexOf(',')));
}
function deleteObj(id, categoryId) {
jConfirm(
"{$vsLang->getWords('obj_delete_confirm', "Are you sure want to delete this {$bw->input[0]}?")}",
"{$bw->vars['global_websitename']} Dialog",
function(r) {
if(r) {
vsf.get('{$bw->input[0]}/delete-obj/'+id+'/','obj-panel');
vsf.get('{$bw->input[0]}/display-obj-list/'+ categoryId +'/','obj-panel');
}
}
);
}

$('#add-objlist-bt').click(function(){
$("#obj-category option:selected").each(function () {
$("#idCategory").val($(this).val());
});
vsf.get('{$bw->input[0]}/add-edit-obj-form/','obj-panel');
});

$('#hide-objlist-bt').click(function() {
if($('#checked-obj').val()=='') {
jAlert(
"{$vsLang->getWords('hide_obj_confirm_noitem', "You haven't choose any items to hide!")}",
"{$bw->vars['global_websitename']} Dialog"
);
return false;
}
var categoryId =0;
$("#obj-category option:selected").each(function () {
categoryId = $(this).val();
});
vsf.submitForm($('#obj-list-form'),'{$bw->input[0]}/hide-checked-obj/','');
vsf.get('{$bw->input[0]}/display-obj-list/'+ categoryId +'/','obj-panel');
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
vsf.submitForm($('#obj-list-form'),'{$bw->input[0]}/visible-checked-obj/','');
vsf.get('{$bw->input[0]}/display-obj-list/'+categoryId+'/','obj-panel');
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
var categoryId ;
$("#obj-category option:selected").each(function () {
categoryId = $(this).val();
});
vsf.submitForm($('#obj-list-form'),'{$bw->input[0]}/delete-checked-obj/','');
vsf.get('{$bw->input[0]}/display-obj-list/'+ categoryId +'/','obj-panel');
}
}
);
});
</script>
EOF;
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e7418e710058($objItems=array(),$option=array())
{
global $bw, $vsLang, $vsSettings;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach(  $objItems as $obj  )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
<tr class="$class">
<td align="center">
<input type="checkbox" onclicktext="checkObject({$obj->getId()});" onclick="checkObject({$obj->getId()});" name="obj_{$obj->getId()}" value="{$obj->getId()}" class="myCheckbox" />
</td>
<td align="center">
{$obj->getStatus("image")}
</td>
<td>
<a href="javascript:vsf.get('{$bw->input[0]}/add-edit-obj-form/{$obj->getId()}/','obj-panel')" title='{$vsLang->getWords('newsItem_EditObjTitle',"Click here to edit")}' class="editObj" >
{$obj->getNick()}
</a>
</td>
<td>{$this->arrType[$obj->getType()]}</td>
<td align="center">{$obj->getIndex()}</td>

EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_option', 0, "supports", 1, 1) ) {
$BWHTML .= <<<EOF

<td></td>

EOF;
}

$BWHTML .= <<<EOF

</tr>

EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:addEditObjForm:desc::trigger:>
//===========================================================================
function addEditObjForm($objItem="",$option=array()) {global $vsLang, $bw, $vsSettings;
$option['setting'] = $vsSettings;
$option['bw'] = $bw;
      

//--starthtml--//
$BWHTML .= <<<EOF
		<div id="error-message" name="error-message"></div>
<form id='add-edit-obj-form' name="add-edit-obj-form" method="POST"  enctype='multipart/form-data'>
<input type="hidden" id="obj-cat-id" name="supportCatId" value="{$option['categoryId']}" />
<input type="hidden" name="supportId" value="{$objItem->getId()}" />
<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
<span class="ui-dialog-title">{$option['formTitle']}</span>
<span id="close" class="closePage" title="{$vsLang->getWords('global_undo','Trở lại')}"></span>
</div>
<table class="ui-dialog-content ui-widget-content">

EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_nick',1, "supports", 1, 1) ) {
$BWHTML .= <<<EOF

<tr>
<td class="label_obj">{$vsLang->getWords('obj_nick', 'Nick')}:</td>
<td><input size="35" type="text" name="supportNick" value="{$objItem->getNick()}" id="obj-nick"/></td>
</tr>

EOF;
}

$BWHTML .= <<<EOF



EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_index', 1, "supports", 1, 1) ) {
$BWHTML .= <<<EOF

<tr>
<td class="label_obj">{$vsLang->getWords('obj_Index', 'Index')}:</td>
<td><input size="4" type="text" name="supportIndex" value="{$objItem->getIndex()}" id="obj-Index"/></td>
</tr>

EOF;
}

$BWHTML .= <<<EOF



EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_type', 1, "supports", 1, 1) ) {
$BWHTML .= <<<EOF

<tr>
<td class="label_obj">{$vsLang->getWords('obj_type', 'Type')}:</td>
<td>
<select name="supportType" id="supportType">
<option value="1"> Yahoo </option>
<option value="2"> Skype </option>
</select>
</td>
</tr>

EOF;
}

$BWHTML .= <<<EOF



EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_image', 0, "supports", 1, 1) ) {
$BWHTML .= <<<EOF

<tr>
<td class="label_obj">{$vsLang->getWords('obj_image_file', "Avatar")}:</td>
<td>
<input size="27" type="file" name="avatar" id="avatar" />
</td>
</tr>
<tr>
<td class="label_obj" colspan="2" align="left">
{$objItem->createImageCache($objItem->getAvatar(), $vsSettings->getSystemKey($bw->input[0]."_image_timthumb_width", 100, $bw->input[0], 1, 1), $vsSettings->getSystemKey($bw->input[0]."_image_timthumb_height", 100, $bw->input[0], 1, 1), $vsSettings->getSystemKey($bw->input[0]."_image_timthumb_type", 0, $bw->input[0], 1, 1), $vsSettings->getSystemKey($bw->input[0]."_image_timthumb_noimage", 0, $bw->input[0], 1, 1))}<br />
</td>
</tr>

EOF;
}

$BWHTML .= <<<EOF




EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_intro', 0, "supports", 1, 1) ) {
$BWHTML .= <<<EOF

<tr>
<td class="label_obj">{$vsLang->getWords('obj_Intro', 'Intro')}:</td>
<td colspan="2">{$objItem->getIntro()}</td>
</tr>

EOF;
}

$BWHTML .= <<<EOF



EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_nickicon', 1, "supports", 1, 1) ) {
$BWHTML .= <<<EOF

<tr>
<td class="label_obj">{$vsLang->getWords('obj_image_online','Icon Online')}:</td>
<td colspan="4" align="center">

EOF;
if(count($option['icon_online'])) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e7418e7104bc($objItem,$option)}

EOF;
}

$BWHTML .= <<<EOF

</td>
</tr>
<tr>
<td class="label_obj">{$vsLang->getWords('obj_image_offline','Icon Offline')}:</td>
<td colspan="4" align="center">

EOF;
if(count($option['icon_offline'])) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e7418e710562($objItem,$option)}

EOF;
}

$BWHTML .= <<<EOF

</td>
</tr>

EOF;
}

$BWHTML .= <<<EOF



EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_status', 1, "supports", 1, 1) ) {
$BWHTML .= <<<EOF

<tr>
<td class="label_obj">{$vsLang->getWords('obj_Status_active', 'Status')}:</td>
<td>
{$vsLang->getWords('obj_Status_Display', 'Display')}
            <input name="supportStatus" type="radio" class='checkbox' value="1" />
            
{$vsLang->getWords('obj_Status_Hide', 'Hide')}
            <input name="supportStatus" type="radio" class='checkbox' value="0" />
</td>
</tr>

EOF;
}

$BWHTML .= <<<EOF


<tr>
<td class="ui-dialog-buttonpanel" colspan="4" align="center">
<input type="submit" name="submit" value="{$option['formSubmit']}" />
</td>
</tr>
</table>
</div>
</form>
<script type="text/javascript">
vsf.jSelect('{$objItem->getCatId()}',"obj-category");
vsf.jSelect('{$objItem->getType()}',"supportType");
vsf.jRadio('{$objItem->getStatus()}',"supportStatus");



EOF;
if(count($option['icon_offline'])) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e7418e710602($objItem,$option)}

EOF;
}

$BWHTML .= <<<EOF


EOF;
if(count($option['icon_online'])) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4e7418e71068d($objItem,$option)}

EOF;
}

$BWHTML .= <<<EOF



$(window).ready(function(){
                                    $('#close').click(function(){
vsf.get('supports/display-obj-list/{$objItem->getCatId()}','obj-panel');
});

                                });

$('#add-edit-obj-form').submit(function(){
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

var title = $("#obj-nick").val();
if(title == null || title == ""){
error += "<li>{$vsLang->getWords('null_title', 'Tiêu đề không được để trống!!!')}</li>";
flag  = false;
}

if(!flag){
error = "<ul class='ul-popu'>" + error + "</ul>";
alertError(error);
return false;
}

vsf.uploadFile("add-edit-obj-form", "{$bw->input[0]}", "add-edit-obj-process", "obj-panel","{$bw->input[0]}");
return false;
});

function updateobjListHtml(categoryId){
vsf.get('{$bw->input[0]}/display-obj-list/'+categoryId+'/','obj-panel');
}
function alertError(message){
jAlert(
message,
'{$bw->vars['global_websitename']} Dialog'
);
}
</script>
EOF;
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e7418e7104bc($objItem="",$option=array())
{
global $vsLang, $bw, $vsSettings;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $option['icon_online'] as $icon )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
<p class="nickicon" style="width:auto">
<input type="radio" value="{$icon->getId()}" name="supportImageOnline" >
<span>
{$icon->createImageCache($icon->getFileId(), $option['setting']->getSystemKey($option['bw']->input[0]."_icon_timthumb_width", 60, $option['bw']->input[0], 1, 1), $option['setting']->getSystemKey($option['bw']->input[0]."_icon_timthumb_height", 20, $option['bw']->input[0], 1, 1), $option['setting']->getSystemKey($option['bw']->input[0]."_icon_timthumb_type", 0, $option['bw']->input[0], 1, 1), $option['setting']->getSystemKey($option['bw']->input[0]."_icon_timthumb_noimage", 0, $option['bw']->input[0], 1, 1))}<br />
</span>
</p>

EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e7418e710562($objItem="",$option=array())
{
global $vsLang, $bw, $vsSettings;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $option['icon_offline'] as $icon )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
<p class="nickicon" style="width:auto">
<input type="radio" value="{$icon->getId()}"  name="supportImageOffline" >
<span>
{$icon->createImageCache($icon->getFileId(), $option['setting']->getSystemKey($option['bw']->input[0]."_icon_timthumb_width", 60, $option['bw']->input[0], 1, 1), $option['setting']->getSystemKey($option['bw']->input[0]."_icon_timthumb_height", 20, $option['bw']->input[0], 1, 1), $option['setting']->getSystemKey($option['bw']->input[0]."_icon_timthumb_type", 0, $option['bw']->input[0], 1, 1), $option['setting']->getSystemKey($option['bw']->input[0]."_icon_timthumb_noimage", 0, $option['bw']->input[0], 1, 1))}<br />
</span>
</p>

EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e7418e710602($objItem="",$option=array())
{
global $vsLang, $bw, $vsSettings;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $option['icon_offline'] as $key=>$icon )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
vsf.jRadio('{$objItem->getImageOffline()}','supportImageOffline');

EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e7418e71068d($objItem="",$option=array())
{
global $vsLang, $bw, $vsSettings;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $option['icon_online'] as $key=>$icon )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
vsf.jRadio('{$objItem->getImageOnline()}','supportImageOnline');

EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:categoryList:desc::trigger:>
//===========================================================================
function categoryList($categoryGroup=array()) {global $vsLang, $bw;

//--starthtml--//
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
       <select size="18" style="width: 100%;" id="obj-category">
        <option value="0">{$vsLang->getWords('menus_option_root',"Root")}</option>
        
EOF;
if(count($categoryGroup->getChildren())) {
$BWHTML .= <<<EOF

        {$this->__foreach_loop__id_4e7418e710dae($categoryGroup)}
        
EOF;
}

$BWHTML .= <<<EOF

        </select>
        </td>
    <td align="center">
        <a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" id="view-obj-bt" title='{$vsLang->getWords('view_list_in_cat',"Click here to edit this {$bw->input[0]}")}'>{$vsLang->getWords('global_view','Xem')}</a>
    <a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" id="add-obj-bt" title='{$vsLang->getWords('add_object_for_cat',"Click here to add this {$bw->input[0]}")}'>{$vsLang->getWords('global_add','Thêm')}</a>
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
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e7418e710dae($categoryGroup=array())
{
global $vsLang, $bw;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $categoryGroup->getChildren() as $oMenu )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
        <option title="{$oMenu->getAlt()}" value="{$oMenu->id}">| - - {$oMenu->title} ({$oMenu->getIndex()} - $oMenu->id)</option>
        
EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:displayObjTab:desc::trigger:>
//===========================================================================
function displayObjTab($option="") {global $bw,$vsSettings;

//--starthtml--//
$BWHTML .= <<<EOF
		
EOF;
if($vsSettings->getSystemKey($bw->input[0].'_category_tab',1)) {
$BWHTML .= <<<EOF

        <div class='left-cell'><div id='category-panel'>{$option['categoryList']}</div></div>
        <input type="hidden" id="idCategory" name="idCategory" />
<div id="obj-panel" class="right-cell">

EOF;
}

else {
$BWHTML .= <<<EOF

<div id="obj-panel" style="width:100%" class="right-cell">

EOF;
}
$BWHTML .= <<<EOF

{$option['objList']}</div>
<div class="clear"></div>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:managerObjHtml:desc::trigger:>
//===========================================================================
function managerObjHtml() {global $bw, $vsLang,$vsSettings;

//--starthtml--//
$BWHTML .= <<<EOF
		<div id="page_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all-top">
<ul id="tabs_nav" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner">
    <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
        <a href="{$bw->base_url}{$bw->input[0]}/display-obj-tab/&ajax=1"><span>{$vsLang->getWords('tab_obj_objes',"{$bw->input[0]}")}</span></a>
        </li>

EOF;
if($vsSettings->getSystemKey($bw->input[0].'_category_tab',1, "supports", 1, 1)) {
$BWHTML .= <<<EOF

<li class="ui-state-default ui-corner-top">
        <a href="{$bw->base_url}menus/display-category-tab/{$bw->input[0]}/&ajax=1"><span>{$vsLang->getWords('global_categories','Categories')}</span></a>
        </li>
        
EOF;
}

$BWHTML .= <<<EOF

        
EOF;
if($vsSettings->getSystemKey($bw->input[0].'_nickicon_tab', 0, "supports", 1, 1)) {
$BWHTML .= <<<EOF

        <li class="ui-state-default ui-corner-top">
        <a href="{$bw->base_url}menus/display-category-tab/nickicons/&ajax=1"><span>{$vsLang->getWords('tab_obj_nickicons','Tiện ích')}</span></a>
        </li>
        
EOF;
}

$BWHTML .= <<<EOF

        
EOF;
if($vsSettings->getSystemKey($bw->input[0].'_setting_tab',1, "supports", 1, 1)) {
$BWHTML .= <<<EOF

        <li class="ui-state-default ui-corner-top">
        <a href="{$bw->base_url}settings/moduleObjTab/{$bw->input[0]}/&ajax=1">
<span>{$vsLang->getWords("tab_{$bw->input[0]}_Setting",'Support Settings')}</span>
</a>
        </li>
        
EOF;
}

$BWHTML .= <<<EOF

</ul>
</div>
EOF;
//--endhtml--//
return $BWHTML;
}


}?>