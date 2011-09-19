<?php
class skin_settings{

//===========================================================================
// <rsf:loadRequiredJavascript:desc::trigger:>
//===========================================================================
function loadRequiredJavascript() {
global $bw, $vsLang;


//--starthtml--//
$BWHTML .= <<<EOF
		<script type='text/javascript'>

function editSetting(objId, catId, pIndex){

if(typeof(pIndex)=='undefined') pIndex = 1;

vsf.get('settings/editForm/'+catId+'/'+objId+'/&pIndex='+pIndex,'setting-table');

}


function addSetting(catId, pIndex){

if(typeof(pIndex)=='undefined') pIndex = 1;

vsf.get('settings/editForm/'+catId+'/'+'/&pIndex='+pIndex,'setting-table');

}


function deleteObj(catId, pIndex){

if(typeof(pIndex)=='undefined') pIndex = 1

jConfirm(

'{$vsLang->getWords("delete_confirm","Are you sure to delete these settings information?")}', 

 '{$bw->vars['global_websitename']} Dialog',

 function(r){

if(r){

var flag=true; var jsonStr = "";


$("input[type=checkbox]").each(function(){

if($(this).hasClass('myCheckbox'+catId)){

flag=false;

if(this.checked) jsonStr += $(this).val()+',';

}

});


  if(flag){

  jAlert(

  "{$vsLang->getWords('delete_confirm_NoItem', 'You haven\'t choose any items!')}",

  "{$vsLang->getWords('global_alert','Notice')}"

  );

  return false;

}

  jsonStr = jsonStr.substr(0,jsonStr.lastIndexOf(','));  

  

vsf.get('settings/deleteObj/'+catId+'/'+jsonStr+'/&pIndex='+pIndex,'setting-table');

return false;

}

 }

             );

}

function closeSetting(catId){

vsf.get('settings/getObjList/'+catId,'setting-table');

}




</script>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:moduleObjTab:desc::trigger:>
//===========================================================================
function moduleObjTab($option="") {
global $vsLang;


//--starthtml--//
$BWHTML .= <<<EOF
		<div id="content_all_vsf">

<div id="objForm" class="left-cell">

{$option['form']}

</div>

<div id="setting-table" class="right-cell">

{$option['list']}

</div>

<div class="clear"></div>

{$this->loadJS()}

</div>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:loadJS:desc::trigger:>
//===========================================================================
function loadJS() {
global $bw, $vsLang;


//--starthtml--//
$BWHTML .= <<<EOF
		<script type='text/javascript'>

function closeSetting(){

vsf.get('settings/moduleObjTab/{$bw->input[2]}','content_all_vsf');

}


function editSetting(objId, catId, pIndex){

if(typeof(pIndex)=='undefined') pIndex = 1;

vsf.get('settings/editForm/'+catId+'/'+objId+'/&type=moduleObj&pIndex='+pIndex,'objForm');

}


function addSetting(catId, pIndex){

if(typeof(pIndex)=='undefined') pIndex = 1;

vsf.get('settings/editForm/'+catId+'/&type=moduleObj&pIndex='+pIndex,'objForm');

}


function deleteObj(catId, pIndex){

if(typeof(pIndex)=='undefined') pIndex = 1

jConfirm(

'{$vsLang->getWords("delete_confirm","Are you sure to delete these settings information?")}', 

 '{$bw->vars['global_websitename']} Dialog',

 function(r){

if(r){

var flag=true; var jsonStr = "";


$("input[type=checkbox]").each(function(){

if($(this).hasClass('myCheckbox'+catId)){

flag=false;

if(this.checked) jsonStr += $(this).val()+',';

}

});


  if(flag){

  jAlert(

  "{$vsLang->getWords('delete_confirm_NoItem', 'You haven\'t choose any items!')}",

  "{$vsLang->getWords('global_alert','Notice')}"

  );

  return false;

}

  jsonStr = jsonStr.substr(0,jsonStr.lastIndexOf(','));  

  

vsf.get('settings/deleteObj/'+catId+'/'+jsonStr+'/&pIndex='+pIndex,'setting-table');

return false;

}

 }

             );

}


</script>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:editForm:desc::trigger:>
//===========================================================================
function editForm($obj="",$option="") {
global $bw, $vsUser, $vsLang;



//--starthtml--//
$BWHTML .= <<<EOF
		<form id="editForm" method="post" style="width:290px;">

<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>


<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">

<span class="ui-icon ui-icon-note"></span>

{$option['title']}

<span id="closePageForm{$option['type']}" title="{$vsLang->getWords('global_undo','Trở lại')}" class="closePage"></span>

</div>

<input type="hidden" name="settingId" value="{$obj->getId()}"/>

<input type="hidden" name="type" value="{$option['type']}"/>

<input type="hidden" name="pIndex" value="{$bw->input['pIndex']}"/>

<table class="ui-dialog-content ui-widget-content" cellpadding="1" cellspacing="1" >

<tr>

    <td width='30'>{$vsLang->getWords('module_label','Module')}:</td>

    <td>

    <select name="settingCatId" id="settingCatId">

    
EOF;
if( $option['category'] ) {
$BWHTML .= <<<EOF


    {$this->__foreach_loop__id_4e773d4aabfe5($obj,$option)}

    
EOF;
}

$BWHTML .= <<<EOF


    </select>

    </td>

</tr>

<tr>

    <td width='30'>{$vsLang->getWords('input_type_label','Input Type')}:</td>

    <td>

    <select name="settingInputType" id="settingInputType">

    
EOF;
if( $option['input'] ) {
$BWHTML .= <<<EOF


    {$this->__foreach_loop__id_4e773d4aac082($obj,$option)}

    
EOF;
}

$BWHTML .= <<<EOF


    </select>

    </td>

</tr>

<tr>

    <td>{$vsLang->getWords('title_label','Title')}:</td>

    <td><input name="settingTitle" value="{$obj->getTitle()}" size="27" type="text"/></td>

</tr>

<tr>

    <td>{$vsLang->getWords('description_label','Description')}:</td>

    <td><input name="settingIntro" value="{$obj->getIntro()}"  size="27" type="text" /></td>

</tr>

<tr>

    <td>{$vsLang->getWords('value_label','Value')}:</td>

    <td id="value">{$obj->buildElementForm('settingValue',$obj->getInputType(),false,false, array('size'=>27))}</td>

</tr>

<tr>

    <td>{$vsLang->getWords('key_label','Key')}:</td>

    <td><input name="settingKey" value="{$obj->getKey()}"  size="27" type="text"/></td>

</tr>


<tr>

    <td width='30'>{$vsLang->getWords('type_label','Type')}:</td>

    <td>

    <select name="settingType" id="settingType">

    <option value="0">{$vsLang->getWords('type_global','Global')}</option>

<option value="1">{$vsLang->getWords('type_admin','Admin')}</option>

<option value="2">{$vsLang->getWords('type_public','Public')}</option>

    </select>

    </td>

</tr>

<tr>

    <td>{$vsLang->getWords('index_label','Index')}:</td>

    <td><input id="settingIndex" name="settingIndex" value="{$obj->getIndex()}"  size="27" type="text"/></td>

</tr>


EOF;
if( $vsUser->checkRoot() ) {
$BWHTML .= <<<EOF


<tr>

    <td>{$vsLang->getWords('root_label','Root')}:</td>

    <td><input name="settingRoot" type="checkbox" id="settingRoot" value="1"  size="27" type="text"/></td>

</tr>


EOF;
}

$BWHTML .= <<<EOF


<tr>

    <td colspan="2" align="center" class="ui-dialog-buttonpanel">

    <input class="button" type="submit" id="submit" name="submit" style="width:50px;" value="Submit" />

    </td>

</tr>

</table>

</div>

</form>


<script>


$('#editForm').submit(function(){


EOF;
if( $option['type'] ) {
$BWHTML .= <<<EOF


vsf.submitForm($('#editForm'),'settings/editObj/','content_all_vsf');


EOF;
}

else {
$BWHTML .= <<<EOF


vsf.submitForm($('#editForm'),'settings/editObj/','setting-table');


EOF;
}
$BWHTML .= <<<EOF


return false;

});




$('#settingInputType').change(function(){

vsf.get('settings/typeValue/'+$('#settingInputType option:selected').val(),'value');

});


$('#closePageForm').click(function(){

closeSetting('{$obj->getCatId()}');

});


$('#closePageFormmoduleObj').click(function(){

addSetting(0,0);

});


$(document).ready(function(){

vsf.jSelect({$obj->getCatId()}, 'settingCatId');

vsf.jSelect({$obj->getType()}, 'settingType');

vsf.jSelect('{$obj->getInputType()}', 'settingInputType');

vsf.jSelect('{$option['catId']}', 'settingCatId');



EOF;
if( $vsUser->checkRoot() ) {
$BWHTML .= <<<EOF


vsf.jCheckbox('{$obj->getRoot()}', 'settingRoot');


EOF;
}

$BWHTML .= <<<EOF







EOF;
if( $option['type'] ) {
$BWHTML .= <<<EOF


$('#settingCatId').attr('disabled','disabled');

$("#editForm").append("<input type='hidden' name='settingCatId' id='settingCatId' value='{$obj->getCatId()}'/>");


EOF;
}

$BWHTML .= <<<EOF



$('#settingIndex').keypress(

            function(event) {

                //Allow only backspace and delete

                if (event.keyCode != 46 && event.keyCode != 8) {

                    if (!parseInt(String.fromCharCode(event.which))) {

                        event.preventDefault();

                    }

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
function __foreach_loop__id_4e773d4aabfe5($obj="",$option="")
{

global $bw, $vsUser, $vsLang;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach(  $option['category'] as $obj )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

    <option value="{$obj['id']}">

{$obj['title']}

</option>

    
EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e773d4aac082($obj="",$option="")
{

global $bw, $vsUser, $vsLang;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach(  $option['input'] as $obj  )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

    <option value="{$obj}" name="settingInputType">

{$obj}

</option>

    
EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:objListHtml:desc::trigger:>
//===========================================================================
function objListHtml($option="") {
global $vsLang,$vsUser;

$BWHTML = "";


//--starthtml--//
$BWHTML .= <<<EOF
		<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>

<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">

<span class='ui-dialog-title'>{$option['cat']['title']}</span>

<span class="ui-icon ui-icon-note"></span>

<div style="float:right;">


<span class="ui-dialog-title">

<a href="javascript:deleteObj({$option['cat']['id']}, {$option['pIndex']});" title="{$vsLang->getWords('delete_tile',"Delete settings in this category")}" class='myLink'>

{$vsLang->getWords('delete_label','Multi Delete')}

</a>

</span>

<span class="ui-dialog-title">

<a href="javascript:addSetting({$option['cat']['id']}, {$option['pIndex']});" title="{$vsLang->getWords('add_title',"Click here to add a new setting.")}" class='myLink' style='margin-left:10px;'>

{$vsLang->getWords('add_label','Add')}

</a>

</span>

</div>

</div>

<div class="message">{$option['message']}</div>

<div id="search-form" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">

                                        <span style="padding-left:10px; color: #222222; line-height:20px;">{$vsLang->getWords("global_title_search", "Title")} <input type="text" name="searchSetting" id="searchSetting" size="65" value="" onblur="if(this.value=='') this.value=''" onclick="this.value=null"/></span>

                                        <a  style="float:right;margin-right: 20px; line-height:20px;" id="search" href="javascript:searchST({$option['cat']['id']});" class="ui-state-default ui-corner-all ui-state-focus">{$vsLang->getWords("global_search", "Search")}</a>

                                   </div>

<table class="ui-widget-content" cellpadding="1" cellspacing="1" width="100%">

    <thead>

                                        

    <tr>

    <th width="20">    

    <input type="checkbox" onclick="checkAll()" onclicktext="checkAll()" name="all" />

    </th>

        <th width="400">

{$vsLang->getWords('name_lable','Name')}</th>

        <th>

{$vsLang->getWords('value_lable','Value')}

        </th>

        <th width="20">

{$vsLang->getWords('order_lable','Order')}

</th>

        </tr>

    </thead>

    <tbody>

                                    

    
EOF;
if( $option['pageList'] ) {
$BWHTML .= <<<EOF


    {$this->__foreach_loop__id_4e773d4aac8e0($option)}


EOF;
}

$BWHTML .= <<<EOF



EOF;
if( $option['paging'] ) {
$BWHTML .= <<<EOF


<tr>

        <td colspan="4" class="paging" align="right">

    {$option['paging']}

</th>

        </tr>

        
EOF;
}

$BWHTML .= <<<EOF


</tbody>

</table>

</div>


<script type='text/javascript'>

 $(window).ready(function(){

                            $("input#searchSetting").autocomplete({

    source: [{$option['searchStrings']['title']}],delay: 2

});

})

function searchST(cat){

                     var searchContent = $("#searchSetting").val();

                     if(searchContent==''){vsf.alert("{$vsLang->getWords('global_search_null', 'Please enter search infomation!')}"); return;}

                   vsf.get('settings/display-obj-list-search/'+searchContent+'/'+cat,'setting-table');

                }

function checkAll() {

var checked_status = $("input[name=all]:checked").length;

var checkedString = '';

$("input[type=checkbox]").each(function(){

if($(this).hasClass('myCheckbox{$option['cat']['id']}')){

this.checked = checked_status;

if(checked_status) checkedString += $(this).val()+',';

}

});

$("span[acaica=myCheckbox{$option['cat']['id']}]").each(function(){

if(checked_status)

this.style.backgroundPosition = "0 -50px";

else this.style.backgroundPosition = "0 0";

});

$('#checked-obj').val(checkedString);

}


    function checkObject() {

var checkedString = '';

$("input[type=checkbox]").each(function(){

if($(this).hasClass('myCheckbox{$option['cat']['id']}')){

if(this.checked) checkedString += $(this).val()+',';

}

});

checkedString = checkedString.substr(0,checkedString.lastIndexOf(','));

$('#checked-obj').val(checkedString);

}

</script>
EOF;
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e773d4aac8e0($option="")
{

global $vsLang,$vsUser;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $option['pageList'] as $obj )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

    <tr>

<td width="20px">

<input value="{$obj->getId()}" type="checkbox" onclicktext="checkObject({$option['cat']['id']});" onclick="checkObject({$option['cat']['id']});" class="myCheckbox{$option['cat']['id']}" />

</td>

    <td>

    <a href="javascript:editSetting({$obj->getId()},{$option['cat']['id']}, {$option['pIndex']})" title="Click here to edit this setting information." class='editObj'>

    {$obj->getTitle()}<br></br>

    </a>

        <span class="desctext">{$obj->getIntro()}</span>

    </td>

    <td>

    {$obj->buildElementForm('settingValue','',true,true, array('size'=>32))}

</td>

<td style="text-align: center;">{$obj->getIndex()}</td>

</tr>


EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:displayObjTab:desc::trigger:>
//===========================================================================
function displayObjTab($listObj="",$arrayCat="",$message="") {
global $vsLang;



//--starthtml--//
$BWHTML .= <<<EOF
		<div id="content_all_vsf">

<div id="setting-cate" class="left-cell">

<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">

<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">

<span class="ui-dialog-title">{$vsLang->getWords('group_title','Setting Group')}</span>

</div>

<table class="ui-dialog-content ui-widget-content" cellpadding="0" cellspacing="0" width="100%">


EOF;
if( $arrayCat ) {
$BWHTML .= <<<EOF


{$this->__foreach_loop__id_4e773d4aacc9e($listObj,$arrayCat,$message)}


EOF;
}

$BWHTML .= <<<EOF


</table>

<div id="subFormAddCate"></div>

</div>

</div>

<div id="setting-table" class="right-cell">

{$listObj}

</div>

<div class="clear"></div>

</div>

{$this->loadRequiredJavascript()}
EOF;
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e773d4aacc9e($listObj="",$arrayCat="",$message="")
{

global $vsLang;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach(  $arrayCat as $obj  )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

<tr>

<td style="border-bottom:1px solid #ccc;">

<a href="javascript:vsf.get('settings/getObjList/{$obj['id']}/', 'setting-table');" title="{$obj['title']}">

{$obj['title']}

</a>

</td>

</tr>


EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:loadDefault:desc::trigger:>
//===========================================================================
function loadDefault() {
global $bw, $vsLang;



//--starthtml--//
$BWHTML .= <<<EOF
		<div id="page_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all-top">

<ul id="tabs_nav" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner">

    <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">

        <a href="{$bw->base_url}settings/display-obj-tab/&ajax=1">

        <span>{$vsLang->getWords('tab_obj_objes',"{$bw->input[0]}")}</span>

</a>

        </li>

        

<li class="ui-state-default ui-corner-top">

        <a href="{$bw->base_url}menus/display-category-tab/settings/&ajax=1">

        <span>{$vsLang->getWords('tab_obj_categories','Categories')}</span>

</a>

        </li>

</ul>

</div>
EOF;
//--endhtml--//
return $BWHTML;
}


}?>