<?php
class skin_news{

//===========================================================================
// <rsf:objListHtml:desc::trigger:>
//===========================================================================
function objListHtml($objItems=array(),$option=array()) {global $bw, $vsLang, $vsSettings, $vsSetting;


//--starthtml--//
$BWHTML .= <<<EOF
		<div class="red">{$option['message']}</div>
<form id="obj-list-form">
<input type="hidden" name="checkedObj" id="checked-obj" value="" />
<input type="hidden" name="categoryId" value="{$option['categoryId']}" id="categoryId" />
<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
        <span class="ui-icon ui-icon-note"></span>
        <span class="ui-dialog-title">{$vsLang->getWords('obj_objListHtmlTitle',"News Item List")}</span>
    </div>
    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">
    <li class="ui-state-default ui-corner-top" id="add-objlist-bt"><a href="#" title="{$vsLang->getWords('add_obj_alt_bt',"Add {$bw->input[0]}")}">{$vsLang->getWords('add_obj_alt_bt',"Add {$bw->input[0]}")}</a></li>
        <li class="ui-state-default ui-corner-top" id="hide-objlist-bt"><a href="#" title="{$vsLang->getWords('hide_obj_alt_bt',"Hide selected {$bw->input[0]}")}">{$vsLang->getWords('hide_obj_bt','Hide')}</a></li>
        <li class="ui-state-default ui-corner-top" id="visible-objlist-bt"><a href="#" title="{$vsLang->getWords('visible_obj_alt_bt',"Visible selected {$bw->input[0]} ")}">{$vsLang->getWords('visible_obj_bt','Visible')}</a></li>
                                        
EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_status_home',0,$bw->input[0],  1, 1) ) {
$BWHTML .= <<<EOF

                                             <li class="ui-state-default ui-corner-top" id="home-objlist-bt"><a href="#" title="{$vsLang->getWords('home_obj_alt_bt',"home selected {$bw->input[0]} ")}">{$vsLang->getWords('home_obj_bt','Home')}</a></li>
                                        
EOF;
}

$BWHTML .= <<<EOF

        <li class="ui-state-default ui-corner-top" id="delete-objlist-bt"><a href="#" title="{$vsLang->getWords('delete_obj_alt_bt',"Delete selected {$bw->input[0]}")}">{$vsLang->getWords('delete_obj_bt','Delete')}</a></li>
    </ul>
<table cellspacing="1" cellpadding="1" id='objListHtmlTable' width="100%">
<thead>
    <tr>
        <th width="15"><input type="checkbox" onclick="vsf.checkAll()"  name="all" /></th>
        <th width="60">{$vsLang->getWords('obj_list_status', 'Active')}</th>
        <th>{$vsLang->getWords('obj_list_title', 'Title')}</td>
        <th width="30">{$vsLang->getWords('obj_list_index', 'Index')}</th>
        
EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_option', 0,  $bw->input[0], 1, 1) ) {
$BWHTML .= <<<EOF

        <th width="100" align="center">{$vsLang->getWords('obj_list_action', 'Action')}</th>
        
EOF;
}

$BWHTML .= <<<EOF

    </tr>
</thead>
<tbody>
{$this->__foreach_loop__id_4e13cf49b8725($objItems,$option)}
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
<div class="clear" id="file"></div>
<script type="text/javascript">

$('#add-objlist-bt').click(function(){

vsf.get('{$bw->input[0]}/add-edit-obj-form/&pageCate={$bw->input[2]}','obj-panel');
});

$('#hide-objlist-bt').click(function() {
if(vsf.checkValue())
                                          vsf.get('{$bw->input[0]}/hide-checked-obj/'+$('#checked-obj').val()+'/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}', 'obj-panel');
});

$('#visible-objlist-bt').click(function() {
if(vsf.checkValue())
vsf.get('{$bw->input[0]}/visible-checked-obj/'+$('#checked-obj').val()+'/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}', 'obj-panel');
});

                                $('#home-objlist-bt').click(function() {
if(vsf.checkValue())
vsf.get('{$bw->input[0]}/home-checked-obj/'+$('#checked-obj').val()+'/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}', 'obj-panel');
});

$('#delete-objlist-bt').click(function() {
if(vsf.checkValue())
jConfirm(
"{$vsLang->getWords('obj_delete_confirm', "Are you sure want to delete this {$bw->input[0]}?")}",
"{$bw->vars['global_websitename']} Dialog",
function(r) {
if(r) {
                                                               vsf.get('{$bw->input[0]}/delete-obj/'+$('#checked-obj').val()+'/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}', 'obj-panel');

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
function __foreach_loop__id_4e13cf49b8725($objItems=array(),$option=array())
{
global $bw, $vsLang, $vsSettings, $vsSetting;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $objItems as $obj )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
<tr class="$vsf_class">
<td align="center">
<input type="checkbox" onclick="vsf.checkObject();" name="obj_{$obj->getId()}" value="{$obj->getId()}" class="myCheckbox" />
</td>
<td style='text-align:center'>{$obj->getStatus('image')}</td>

<td>
<a href="javascript:vsf.get('{$bw->input[0]}/add-edit-obj-form/{$obj->getId()}/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}','obj-panel')" title='{$vsLang->getWords('newsItem_EditObjTitle',"Click here to edit this {$bw->input[0]}")}' class="editObj" >
{$obj->getTitle()}
</a>
</td>
<td>{$obj->getIndex()}</td>

EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_option', 0, $bw->input[0], 1, 1) ) {
$BWHTML .= <<<EOF

<td>

EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_multi_file',0,  $bw->input[0], 1, 1)) {
$BWHTML .= <<<EOF

<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" onclick="vsf.popupGet('gallerys/display-album-tab/news/{$obj->getId()}&albumCode=image','albumn1')">
{$vsLang->getWords('global_album','Album')}
</a>

EOF;
}

$BWHTML .= <<<EOF

</td>

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
function addEditObjForm($objItem="",$option=array()) {global $vsLang, $bw,$vsSettings, $vsStd;
$max_upload_size = min($vsStd->let_to_num(ini_get('post_max_size')), $vsStd->let_to_num(ini_get('upload_max_filesize')));

//--starthtml--//
$BWHTML .= <<<EOF
		<style>
                .label_obj {
                   width:80px;
                  }
                </style>
<div id="error-message" name="error-message"></div>
<form id='add-edit-obj-form' name="add-edit-obj-form" method="POST" enctype='multipart/form-data'>
<input type="hidden" id="obj-cat-id" name="newsCatId" value="{$option['categoryId']}" />
<input type="hidden" name="newsId" value="{$objItem->getId()}" />
<input type="hidden" name="pageIndex" value="{$bw->input['pageIndex']}" />
<input type="hidden" name="pageCate" value="{$bw->input['pageCate']}" />
<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
<span class="ui-dialog-title">{$option['formTitle']}</span>
                                                <p style="float:right; cursor:pointer;">
<span id="closeSetting" class="closePage" title="{$vsLang->getWords('global_undo','Trở lại')}"></span>
</p>
</div>
<table class="ui-dialog-content ui-widget-content" style="width:100%;">

EOF;
if($vsSettings->getSystemKey($bw->input[0].'_title',1, $bw->input[0], 1, 1)) {
$BWHTML .= <<<EOF

<tr class='smalltitle'>
<td class="label_obj" width="75">{$vsLang->getWords('obj_title', 'Title')}:</td>
<td colspan="3">
<input style="width:100%;" name="newsTitle" value="{$objItem->getTitle()}" id="obj-title" type="text"/>
</td>
</tr>

EOF;
}

$BWHTML .= <<<EOF



EOF;
if($vsSettings->getSystemKey($bw->input[0].'_author',0, $bw->input[0], 1, 1)) {
$BWHTML .= <<<EOF

<tr class='smalltitle'>
<td class="label_obj"  width="75">
{$vsLang->getWords('obj_Author', 'Author')}:
</td>
<td colspan="3">
<input style="width:100%;" name="newsAuthor" value="{$objItem->getAuthor()}"/>
</td>
</tr>

EOF;
}

$BWHTML .= <<<EOF



EOF;
if($vsSettings->getSystemKey($bw->input[0].'_index',1, $bw->input[0], 1, 1)) {
$BWHTML .= <<<EOF

<tr class='smalltitle'>
<td class="label_obj"  width="75">
{$vsLang->getWords('obj_index', 'Index')}:
</td>
<td width="170" colspan="3">
<input size="10" name="newsIndex" value="{$objItem->getIndex()}" />
</td>
</tr>

EOF;
}

$BWHTML .= <<<EOF



EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_status',1, $bw->input[0], 1, 1) ) {
$BWHTML .= <<<EOF

<tr class='smalltitle'>
<td class="label_obj" width="75">{$vsLang->getWords('obj_Status', 'Status')}:</td>
<td colspan="3">

<input name="newsStatus" id="newsStatus1" value='1' class='c_noneWidth' type="radio" checked />
<label>{$vsLang->getWords('status_1','Display')}</label>


<input name="newsStatus" id="newsStatus0" value='0' class='c_noneWidth' type="radio" />
<label>{$vsLang->getWords('status_0','Hide')}</label>


EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_status_home',0,  $bw->input[0], 1, 1) ) {
$BWHTML .= <<<EOF


<input name="newsStatus" id="newsStatus2" value='2' class='c_noneWidth' type="radio" />
                                                                <label>{$vsLang->getWords('status_2','Special')}</label>

EOF;
}

$BWHTML .= <<<EOF

</td>
</tr>

EOF;
}

$BWHTML .= <<<EOF



EOF;
if($vsSettings->getSystemKey($bw->input[0].'_image',1,$bw->input [0],1,1)) {
$BWHTML .= <<<EOF

<tr class='smalltitle'>
<td>{$vsLang->getWords('obj_image', "Hình ảnh")}</td>
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
swfu = new SWFUpload(vsf.uploadSWF($max_upload_size,['add-edit-obj-form','spanButtonPlaceholder','fsUploadProgress',''],'news','news',["{$vsSettings->getSystemKey('global_file_image_extend',"*.jpg;*.png;*.gif","global",  0, 1)}","Images"]));
});
</script>
</td>
</tr>
</table>
</td>
<td colspan="2" rowspan="1">
{$objItem->createImageCache($objItem->getImage(), $vsSettings->getSystemKey($bw->input[0]."_image_timthumb_width", 100, $bw->input[0], 1, 1), $vsSettings->getSystemKey($bw->input[0]."_image_timthumb_height", 100, $bw->input[0], 1, 1), $vsSettings->getSystemKey($bw->input[0]."_image_timthumb_type", 0, $bw->input[0], 1, 1), $vsSettings->getSystemKey($bw->input[0]."_image_timthumb_noimage", 0, $bw->input[0], 1, 1))}


EOF;
if( $objItem->getImage() && $vsSettings->getSystemKey($bw->input[0].'_image_delete',1,  $bw->input[0], 1, 1) ) {
$BWHTML .= <<<EOF
<br>
                                                                   <input type="checkbox" name="deleteImage" id="deleteImage" />
                                                                   <label for="deleteImage">{$vsLang->getWords('delete_image','Delete Image')}</lable>

EOF;
}

$BWHTML .= <<<EOF

</td>
</tr>

EOF;
}

$BWHTML .= <<<EOF



EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_intro',1,  $bw->input[0], 1, 1) ) {
$BWHTML .= <<<EOF

<tr class='smalltitle'>
<td class="label_obj" width="75">
{$vsLang->getWords('obj_Intro', 'Intro')}:
</td>
<td colspan="3" valgin="left">
{$objItem->getIntro()}
</td>
</tr>

EOF;
}

$BWHTML .= <<<EOF



EOF;
if($vsSettings->getSystemKey($bw->input[0].'_content',1,  $bw->input[0], 1, 1)) {
$BWHTML .= <<<EOF

                                                 <tr class='smalltitle'>
<td colspan="4" >{$vsLang->getWords('obj_Content', 'Content')}:</td>
</tr>
<tr class='smalltitle'>
<td colspan="4" align="center">{$objItem->getContent()}</td>
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
<script language="javascript">
$(window).ready(function() {
$("input.numeric").numeric();
checkedLinkFile();
vsf.jRadio('{$objItem->getStatus()}','newsStatus');
vsf.jSelect('{$objItem->getCatId()}','obj-category');
                                        $("#closeSetting").click(function(){
                                            vsf.get('{$bw->input[0]}/'+'display-obj-list/'+'{$objItem->getCatId()}','obj-panel');

                                        });

});

$('#txtlink').change(function() {
var img_html = '<img src="'+$(this).val()+'" style="width:100px; max-height:115px;" />'; 
$('#td-obj-image').html(img_html);
});

$('#newsIntroImage').change(function() {
var img_name = '<input type="hidden" id="image-name" name="image-name" value="'+$(this).val() +'"/>';
$('#td-obj-image').html(img_name);
});

function checkedLinkFile(value){
if(value=='link'){
$("#txtlink").removeAttr('disabled');
$("#pageImage").css('visibility','hidden');
}else{
$("#txtlink").attr('disabled', 'disabled');
$("#pageImage").css('visibility','visible');
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
error = "<li>{$vsLang->getWords('not_select_category', 'Please chose category')}</li>";
flag  = false;
}

var title = $("#obj-title").val();
if(title == 0 || title == ""){
error += "<li>{$vsLang->getWords('null_title', 'Title cannot be blank')}</li>";
flag  = false;
}
if(!flag){
error = "<ul class='ul-popu'>" + error + "</ul>";
vsf.alert(error);
return false;
}
vsf.submitForm($('#add-edit-obj-form'), 'news/add-edit-obj-process',"obj-panel");
return false;
});
</script>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:categoryList:desc::trigger:>
//===========================================================================
function categoryList($data=array()) {global $vsLang, $bw;

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
        {$data['html']}
        </td>
    <td align="center">
    <a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" id="view-obj-bt" title='{$vsLang->getWords('category_view',"Click here to view this category")}'>
        {$vsLang->getWords('global_view','View')}
        </a>
    <a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" id="add-obj-bt" title='{$vsLang->getWords('category_add',"Click here to add news")}'>
        {$vsLang->getWords('global_add','Add')}
        </a>
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
// <rsf:displayObjTab:desc::trigger:>
//===========================================================================
function displayObjTab($option="") {global $bw,$vsSettings;

//--starthtml--//
$BWHTML .= <<<EOF
		
EOF;
if($vsSettings->getSystemKey($bw->input[0].'_category_tab',1,  $bw->input[0], 1, 1)) {
$BWHTML .= <<<EOF

        <div class='left-cell'><div id='category-panel'>{$option['categoryList']}</div></div>
<input type="hidden" id="idCategory" name="idCategory" />
<div id="obj-panel" class="right-cell">{$option['objList']}</div>
<div class="clear"></div>

EOF;
}

else {
$BWHTML .= <<<EOF

<input type="hidden" id="idCategory" name="idCategory" />
<div id="obj-panel" style="width:100%" class="right-cell">{$option['objList']}</div>
<div class="clear"></div>
        
EOF;
}
$BWHTML .= <<<EOF

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
if($vsSettings->getSystemKey($bw->input[0].'_category_tab',1,  $bw->input[0], 1, 1)) {
$BWHTML .= <<<EOF

<li class="ui-state-default ui-corner-top">
        <a href="{$bw->base_url}menus/display-category-tab/news/&ajax=1"><span>{$vsLang->getWords('tab_obj_categories','Categories')}</span></a>
        </li>
        
EOF;
}

$BWHTML .= <<<EOF

        
        
EOF;
if($vsSettings->getSystemKey($bw->input[0].'_setting_tab',1,  $bw->input[0], 1, 1)) {
$BWHTML .= <<<EOF

        <li class="ui-state-default ui-corner-top">
        <a href="{$bw->base_url}settings/moduleObjTab/{$bw->input[0]}/&ajax=1">
{$vsLang->getWords('tab_news_setting','News Settings')}
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