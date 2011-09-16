<?php
class skin_products{

//===========================================================================
// <rsf:objListHtml:desc::trigger:>
//===========================================================================
function objListHtml($objItems=array(),$option=array()) {global $bw, $vsLang, $vsSettings;

$stringSearch = ($vsLang->getWords ( 'global_string_search', 'Tìm kiếm ...' ));

//--starthtml--//
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
                                function search(){
                                        var typeSearch = '';
                                        var searchContent = $("#searchTitle").val();
                                        typeSearch = 0;
                                        if(searchContent==''){
                                            searchContent = $("#searchId").val();
                                            typeSearch = 1;
                                            }
                                        if(searchContent==''||searchContent=='{$stringSearch}'){vsf.alert("{$vsLang->getWords('global_search_null', 'Please enter search infomation!')}"); return;}
                                        vsf.get('{$bw->input[0]}/display-obj-list-search/'+searchContent+'/'+typeSearch,'obj-panel');
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
if($('#checked-obj').val()=='') {
jAlert(
"{$vsLang->getWords('hide_obj_confirm_noitem', "You haven't choose any items to hide!")}",
"{$bw->vars['global_websitename']} Dialog"
);
return false;
}
checkObject();

vsf.get('{$bw->input[0]}/hide-checked-obj/'+$('#checked-obj').val()+'/'+ $("#obj-category").val(), 'obj-panel');
});

$('#visible-objlist-bt').click(function() {
if($('#checked-obj').val()=='') {
jAlert(
"{$vsLang->getWords('visible_obj_confirm_noitem', "You haven't choose any items to visible!")}",
"{$bw->vars['global_websitename']} Dialog"
);
return false;
}

checkObject();
vsf.get('{$bw->input[0]}/visible-checked-obj/'+$('#checked-obj').val()+'/'+ $("#obj-category").val(), 'obj-panel');
});

                                $('#home-objlist-bt').click(function() {
if($('#checked-obj').val()=='') {
jAlert(
"{$vsLang->getWords('visible_obj_confirm_noitem', "You haven't choose any items to visible!")}",
"{$bw->vars['global_websitename']} Dialog"
);
return false;
}
checkObject();
vsf.get('{$bw->input[0]}/home-checked-obj/'+$('#checked-obj').val()+'/'+ $("#obj-category").val(), 'obj-panel');
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
vsf.get('{$bw->input[0]}/display-obj-list/'+$("#obj-category").val(),'obj-panel');
}
}
);
});
                        $(window).ready(function(){
                            $("input#searchTitle").autocomplete({
    source: [{$option['searchStrings']['title']}],delay: 2
});
 $("input#searchId").autocomplete({
    source: [{$option['searchStrings']['id']}],delay: 2
});
                            if($("#issearch").val()){
                                $("#search-bt").text('{$vsLang->getWords('obj_hide_search', 'Hide search')}');
                                $("#search-form").css('display', 'block');
                            }
                                else{
                                    $("#search-form").css('display', 'none');
                                }

                            $("input.numeric").numeric();
                            $("#searchTitle").focus();
                            $("#search-bt").click(function(){
                                if($("#search-bt").text()=='{$vsLang->getWords('obj_search', 'Search')}'){
                                    $("#search-bt").text('{$vsLang->getWords('obj_hide_search', 'Hide search')}');
                                    $("#search-form").fadeIn('slow',function(){
                                        $("#search-form").css('display', 'block');
                                    });

                                    }
                                    else{
                                    $("#search-bt").text('{$vsLang->getWords('obj_search', 'Search')}');
                                    $("#search-form").fadeOut('slow',function(){
                                        $("#search-form").css('display', 'none');
                                    });
                                    }
                            });
                            
                        });
</script>

<div class="red">{$option['message']}</div>
<form id="obj-list-form">
<input type="hidden" name="checkedObj" id="checked-obj" value="" />
<input type="hidden" name="categoryId" value="{$option['categoryId']}" id="categoryId" />
                                <input type="hidden" name="issearch" value="{$option['search']}" id="issearch" />
<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
        <span class="ui-icon ui-icon-note"></span>
        <span class="ui-dialog-title">{$vsLang->getWords('obj_objListHtmlTitle',"Product Item List")}</span>
                                        <p style="align:right; float: right; color: #FFFFFF; cursor: pointer"><span id="search-bt">{$vsLang->getWords('obj_search', 'Search')}</span></p>
    </div>
                                   <div id="search-form" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">
                                        <span style="padding-left:10px; color: #222222; line-height:20px;">{$vsLang->getWords("global_id_search", "Id")} <input type="text" name="searchId" class="numeric" id="searchId" size="10"   onclick="this.value=null"/></span>
                                        <span style="padding-left:10px; color: #222222; line-height:20px;">{$vsLang->getWords("global_title_search", "Title")} <input type="text" name="searchTitle" id="searchTitle" size="65" value="" onblur="if(this.value=='') this.value=''" onclick="this.value=null"/></span>
                                        <a title="Click here to search this content!" style="float:right;margin-right: 20px; line-height:20px;" id="search" href="javascript:search();" class="ui-state-default ui-corner-all ui-state-focus">{$vsLang->getWords("global_search", "Search")}</a>
                                   </div>
    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">
    <li class="ui-state-default ui-corner-top" id="add-objlist-bt">
    <a href="#" title="{$vsLang->getWords('global_add_title',"Add")}">
{$vsLang->getWords('global_add',"Add")}
</a>
</li>
        <li class="ui-state-default ui-corner-top" id="hide-objlist-bt">
        <a href="#" title="{$vsLang->getWords('global_hide_title',"Hide")}">
{$vsLang->getWords('global_hide','Hide')}
</a>
</li>
        <li class="ui-state-default ui-corner-top" id="visible-objlist-bt">
        <a href="#" title="{$vsLang->getWords('global_display_title',"Display")}">
{$vsLang->getWords('global_display','Display')}
</a>
</li>
                                        
EOF;
if($vsSettings->getSystemKey($bw->input[0].'_ishome',1, $bw->input[0], 1, 1)) {
$BWHTML .= <<<EOF

                                                  <li class="ui-state-default ui-corner-top" id="home-objlist-bt"><a href="#" title="{$vsLang->getWords('ishome_obj_alt_bt',"Home selected {$bw->input[0]} ")}">{$vsLang->getWords('ishome_obj_bt','Is Home')}</a></li>
                                        
EOF;
}

$BWHTML .= <<<EOF

        <li class="ui-state-default ui-corner-top" id="delete-objlist-bt">
        <a href="#" title="{$vsLang->getWords('global_delete_title',"Delete")}">
{$vsLang->getWords('global_delete','Delete')}
</a>
</li>
    </ul>
<table cellspacing="1" cellpadding="1" id='objListHtmlTable' width="100%">
<thead>
    <tr>
        <th width="15"><input type="checkbox" onclick="checkAll()" onclicktext="checkAll()" name="all" /></th>
        <th width="20">{$vsLang->getWords('obj_list_status', 'Status')}</th>
        <th>{$vsLang->getWords('obj_list_title', 'Title')}</th>
        <th width="30">{$vsLang->getWords('obj_list_index', 'Index')}</th>
        
EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_multi_file', 0, "products", 1, 1) ) {
$BWHTML .= <<<EOF

        <th width="65">{$vsLang->getWords('obj_list_option', 'Option')}</th>
        
EOF;
}

$BWHTML .= <<<EOF

    </tr>
</thead>
<tbody>
{$this->__foreach_loop__id_4e72c0bf27a03($objItems,$option)}
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
                            <span style="padding-left: 10px;line-height:25px;"><img src="{$bw->vars['img_url']}/enable.png" />{$vsLang->getWords('global_status_enable', 'Enable')}</span>
                            <span style="padding-left: 10px;line-height:25px;"><img src="{$bw->vars['img_url']}/disabled.png" /> {$vsLang->getWords('global_status_disabled', 'Disable')}</span>
                            <span style="padding-left: 10px;line-height:25px;"><img src="{$bw->vars['img_url']}/home.png" /> {$vsLang->getWords('global_status_ishome', 'Show on home page')}</span>
                            <span style="padding-left: 10px;line-height:25px;"><img src="{$bw->vars['img_url']}/best.png" /> {$vsLang->getWords('global_status_best', 'Best-Selling')}</span>
                            <span style="padding-left: 10px;line-height:25px;"><img src="{$bw->vars['img_url']}/new.png" /> {$vsLang->getWords('global_status_new', 'New')}</span>
                           </tr>
                     </tbody>
                </table>
</div>
</form>
<div class="clear" id="file"></div>
EOF;
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e72c0bf27a03($objItems=array(),$option=array())
{
global $bw, $vsLang, $vsSettings;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $objItems as $obj )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
<tr class="$vsf_class">
<td align="center">
<input type="checkbox" onclick="checkObject({$obj->getId()});" name="obj_{$obj->getId()}" value="{$obj->getId()}" class="myCheckbox" />
</td>
<td style='text-align:center'>{$obj->getStatus('image')}</td>
<td>
<a href="javascript:vsf.get('{$bw->input[0]}/add-edit-obj-form/{$obj->getId()}/&pageIndex={$bw->input[3]}&pageCate={$bw->input[2]}','obj-panel')" title='{$vsLang->getWords('newsItem_EditObjTitle',"Click here to edit this {$bw->input[0]}")}' class="editObj" >
{$obj->getTitle()}
</a>
</td>
<td algin="center">{$obj->getIndex()}</td>

EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_multi_file',0,"products", 1, 1) ) {
$BWHTML .= <<<EOF

<td align="center">
<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" onclick="vsf.popupGet('gallerys/display-album-tab/products/{$obj->getId()}&albumCode=products','albumn1')">
{$vsLang->getWords('global_album','Album')}
</a>
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
function addEditObjForm($objItem="",$option=array()) {global $vsLang, $bw, $vsSettings,$vsStd;
$max_upload_size = min($vsStd->let_to_num(ini_get('post_max_size')), $vsStd->let_to_num(ini_get('upload_max_filesize')));
$active = $objItem->getStatus () != '' ? $objItem->getStatus () : 1;

//--starthtml--//
$BWHTML .= <<<EOF
		<div id="error-message" name="error-message"></div>
<form id='add-edit-obj-form' name="add-edit-obj-form" method="POST" enctype='multipart/form-data'>
<input type="hidden" id="obj-cat-id" name="productCatId" value="{$option['categoryId']}" />
<input type="hidden" name="productId" value="{$objItem->getId()}" /><input type="hidden" name="productImage" value="{$objItem->getImage()}" />
<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
<span class="ui-icon ui-icon-note"></span> 
<span class="ui-dialog-title">{$option['formTitle']}</span>
                        <span id="close" class="closePage" title="{$vsLang->getWords('global_undo','Trở lại')}"></span>
</div>
<table cellpadding="1" cellspacing="1" border="0" class="ui-dialog-content ui-widget-content" style="width:100%;">

EOF;
if( $vsSettings->getSystemKey("{$bw->input[0]}_title", 1, $bw->input[0], 1, 1)) {
$BWHTML .= <<<EOF

<tr class='smalltitle'>
<td width="75">{$vsLang->getWords('obj_title', 'Title')}:</td>
<td colspan="3"><input size="80" name="productTitle" value="{$objItem->getTitle()}" id="obj-title" type="text"/></td>
</tr>

EOF;
}

$BWHTML .= <<<EOF



EOF;
if( $vsSettings->getSystemKey("{$bw->input[0]}_code", 0, $bw->input[0], 1, 1)) {
$BWHTML .= <<<EOF

<tr class='smalltitle'>
<td width="75" class="label_obj">
{$vsLang->getWords('obj_code', 'Code')}:
</td>
<td colspan="3">
<input size="35" name="productCode" value="{$objItem->getCode()}" type="text"/>
</td>
</tr>

EOF;
}

$BWHTML .= <<<EOF



EOF;
if($vsSettings->getSystemKey("{$bw->input[0]}_price", 0, $bw->input[0], 1, 1)) {
$BWHTML .= <<<EOF

<tr class='smalltitle'>
<td width="75" class="label_obj">
{$vsLang->getWords('obj_price', 'Price')}:
</td>
<td colspan="3">
<input size="35" name="productPrice" value="{$objItem->getPrice(false)}" class="numeric" type="text"/>
</td>
</tr>

EOF;
}

$BWHTML .= <<<EOF



EOF;
if($vsSettings->getSystemKey("{$bw->input[0]}_index", 1, $bw->input[0], 1, 1)) {
$BWHTML .= <<<EOF

<tr class='smalltitle'>
<td width="75" class="label_obj">
{$vsLang->getWords('obj_Index', 'Index')}:
</td>
<td colspan="3">
<input size="5" name="productIndex" id="productIndex" value="{$objItem->getIndex()}" class="numeric" type="text"/>
</td>
</tr>

EOF;
}

$BWHTML .= <<<EOF



EOF;
if( $vsSettings->getSystemKey("{$bw->input[0]}_status",1,$bw->input[0], 1, 1) ) {
$BWHTML .= <<<EOF

<tr class='smalltitle'>
<td width="75" class="label_obj">{$vsLang->getWords('obj_status', 'Status')}:</td>
<td colspan="3">
            <input name="productStatus" type="radio" class='radio' value="1" />
            <label style="padding-right: 10px" for="left">{$vsLang->getWords('obj_Status_Display', 'Display')}</label>
            <input name="productStatus" type="radio" class='radio' value="0" />
            <label style="padding-right: 10px" for="left">{$vsLang->getWords('obj_Status_Hide', 'Hide')}</label>
            <input name="productStatus" type="radio" class='radio' value="3" />
<label style="padding-right: 10px" for="left">{$vsLang->getWords('obj_Status_Best_Selling', 'Best-Selling')}</label>
<input name="productStatus" type="radio" class='radio' value="4" />
<label style="padding-right: 10px" for="left">{$vsLang->getWords('obj_Status_New', 'New')}</label>
            
EOF;
if($vsSettings->getSystemKey($bw->input[0].'_ishome',1, $bw->input[0], 1, 1)) {
$BWHTML .= <<<EOF

<input type="radio" value="2" name="productStatus" id="productStatus" class="radio">
        <label style="padding-right: 10px" for="left">{$vsLang->getWords('global_ishome', "Is home")}</label>

EOF;
}

$BWHTML .= <<<EOF

</td>
</tr>

EOF;
}

$BWHTML .= <<<EOF




EOF;
if( $vsSettings->getSystemKey("{$bw->input[0]}_image", 1, $bw->input[0], 1, 1) ) {
$BWHTML .= <<<EOF

<tr class='smalltitle'>
        <td >{$vsLang->getWords('pages_pageImage',"Intro Image")}</td>
<td>
<table>
<tr>
<td>{$vsLang->getWords('obj_image_link', "Link")}:<input onclick="checkedLinkFile($('#link-text').val());" onclicktext="checkedLinkFile($('#link-text').val());" type="radio" id="link-text" name="link-file" value="link" /></td>
<td><input size="39" type="text" name="txtlink" id="txtlink"" /></td>

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
swfu = new SWFUpload(vsf.uploadSWF($max_upload_size,['add-edit-obj-form','spanButtonPlaceholder','fsUploadProgress',''],'{$bw->input['module']}','{$bw->input['module']}',["{$vsSettings->getSystemKey('global_file_image_extend',"*.jpg;*.png;*.gif","global",  0, 1)}","Images"]));

});
</script>
</td>
</tr>
</table>
</td>
<td valgin="right" >
<div id="td-obj-image">{$objItem->createImageCache($objItem->getImage(), 100, 100)}</div><br />

EOF;
if( $objItem->getImage() ) {
$BWHTML .= <<<EOF

<input name="deleteImage" type="checkbox" />
{$vsLang->getWords('obj_delete_image', 'Delete')}

EOF;
}

$BWHTML .= <<<EOF

</td>
</tr>

EOF;
}

$BWHTML .= <<<EOF




EOF;
if($vsSettings->getSystemKey("{$bw->input[0]}_intro", 1, $bw->input[0], 1, 1)) {
$BWHTML .= <<<EOF

<tr class='smalltitle'>
<td colspan="4">
{$vsLang->getWords('obj_Intro', 'Intro')}
{$objItem->getIntro()}
</td>
</tr>

EOF;
}

$BWHTML .= <<<EOF



EOF;
if($vsSettings->getSystemKey($bw->input[0]."_content", 1, $bw->input[0], 1, 1)) {
$BWHTML .= <<<EOF

<tr class='smalltitle'>
<td colspan="4">
{$vsLang->getWords('obj_content', 'Content')}
{$objItem->getContent()}</td>
</tr>

EOF;
}

$BWHTML .= <<<EOF

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
checkedLinkFile('image');
vsf.jRadio('{$active}','productStatus');
                                         vsf.jSelect('{$objItem->getCatId()}','obj-category');
                                        $("#close").click(function(){
                                            vsf.get('{$bw->input[0]}/display-obj-list/'+$("#obj-category").val(), 'obj-panel');
                                        });
});
$('#txtlink').change(function() {
var img_html = '<img src="'+$(this).val()+'" style="width:100px; max-height:115px;" />'; 
$('#td-obj-image').html(img_html);
});
$('#productIntroImage').change(function() {
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
var categoryId = "";
var count=0;
                                        if({$vsSettings->getSystemKey($bw->input[0].'_cat_list_column', 0, $bw->input[0])})
                                            if(!($("#obj-category option:selected").val()&&$("#obj-category option:selected").val()!=0)){
                                                    error = "<li>{$vsLang->getWords('not_select_category', 'Please select category!')}</li>";
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
                                        $('#obj-cat-id').val($('#obj-category').val());
$('#obj-category').removeClass('ui-state-error ui-corner-all-inner');
vsf.submitForm($('#add-edit-obj-form'), '{$bw->input[0]}/add-edit-obj-process',"obj-panel");
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
    <th id="obj-category-message" colspan="2">{$data['message']}{$vsLang->getWords('category_chosen',"Selected categories")}: <span id="chosen">{$vsLang->getWords('category_not_selected',"None")}</span></th>
    </tr>
    <tr>
        <td width="220">
        {$data['html']}
        </td>
    <td align="center">
        <a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" id="view-obj-bt" title='{$vsLang->getWords('view_list_in_cat',"Click here to edit")}'>
        {$vsLang->getWords('global_view','Xem')}
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
vsf.get('{$bw->input[0]}/add-edit-obj-form/', 'obj-panel');
});
                                
                                $('#obj-category').change(function(){
                                    $('#chosen').text($('#obj-category').val());
                                });
</script>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:displayObjTab:desc::trigger:>
//===========================================================================
function displayObjTab($option="") {global $bw, $vsSettings;

//--starthtml--//
$BWHTML .= <<<EOF
		
EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_cat_list_column', 1, "products", 1, 1) ) {
$BWHTML .= <<<EOF

<div class='left-cell'>
<div id='category-panel'>
{$option['categoryList']}
</div>
</div>

EOF;
}

$BWHTML .= <<<EOF

<div id="obj-panel" class="right-cell" 
EOF;
if( !$vsSettings->getSystemKey($bw->input[0].'_cat_list_column', 1, "products", 1, 1) ) {
$BWHTML .= <<<EOF
style="width:100%;"
EOF;
}

$BWHTML .= <<<EOF
>
{$option['objList']}
</div>
<div class="clear"></div>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:mainPage:desc::trigger:>
//===========================================================================
function mainPage() {global $bw, $vsLang, $vsSettings;


//--starthtml--//
$BWHTML .= <<<EOF
		<div id="page_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all-top">
<ul id="tabs_nav" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner">
    <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
        <a href="{$bw->base_url}{$bw->input[0]}/display-product-tab/&ajax=1">
        <span>
{$vsLang->getWords('tab_obj_objs',"{$bw->input[0]}")}
</span>
        </a>
        </li>
        
EOF;
if( $vsSettings->getSystemKey($bw->input[0].'_cat_tab', 1, "products", 1, 1)) {
$BWHTML .= <<<EOF

<li class="ui-state-default ui-corner-top">
        <a href="{$bw->base_url}menus/display-category-tab/products/&ajax=1"><span>
{$vsLang->getWords('tab_obj_categories','Categories')}</span>
</a>
        </li>
        
EOF;
}

$BWHTML .= <<<EOF

        
EOF;
if($vsSettings->getSystemKey($bw->input[0].'_setting_tab', 1, "products", 1, 1)) {
$BWHTML .= <<<EOF

        <li class="ui-state-default ui-corner-top">
        <a href="{$bw->base_url}settings/moduleObjTab/{$bw->input[0]}/&ajax=1">
{$vsLang->getWords('tab_product_Settings','Product Settings')}
</a>
        </li>
        
EOF;
}

$BWHTML .= <<<EOF

        
EOF;
if($vsSettings->getSystemKey($bw->input[0].'_banner_tab',1,$bw->input[0],1,1)) {
$BWHTML .= <<<EOF

        <li class="ui-state-default ui-corner-top">
        <a href="{$bw->base_url}partners/moduleObjTab/{$bw->input[0]}/&ajax=1">
<span>{$vsLang->getWords("tab_{$bw->input[0]}_partner","{$bw->input[0]} Banner")}</span>
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