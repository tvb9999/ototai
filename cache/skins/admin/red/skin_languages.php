<?php
class skin_languages{

//===========================================================================
// <rsf:MainPage:desc::trigger:>
//===========================================================================
function MainPage($list="") {
global $bw, $vsLang, $vsUser;



//--starthtml--//
$BWHTML .= <<<EOF
		<script type="text/javascript">

function deleted(act,langid,value,id){

jConfirm(

'{$vsLang->getWords('lang_confirm','Confirm delete')} "'+value+'"?', 'Confirmation Dialog',

function(r) {

if(r)

vsf.get('languages/'+act+'/'+langid+'/'+value,id);

});

}

</script>

<div id="page_tabs">

<ul id="tabs_nav">

        {$this->__foreach_loop__id_4e13cec2c18f6($list)}

        
EOF;
if( $vsUser->checkRoot() ) {
$BWHTML .= <<<EOF


        <li>

        <a href="{$bw->base_url}languages/displayLangForm/&ajax=1">

        <span>{$vsLang->getWords('tab_news_lang','Languages')}</span>

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

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e13cec2c18f6($list="")
{

global $bw, $vsLang, $vsUser;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach(  $list as $obj  )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

         <li>

         <a href="{$bw->base_url}languages/viewLangWith/{$obj->getId()}/&ajax=1">

         <span>{$vsLang->getWords($obj->getName(), $obj->getName())}</span>

         </a>

         </li>

        
EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:LanguagesMain:desc::trigger:>
//===========================================================================
function LanguagesMain($show="") {
global $bw, $vsLang;

$BWHTML = "";


$storein = $bw->vars ['storelangtype'] ? "File" : "Database";


//--starthtml--//
$BWHTML .= <<<EOF
		<script type="text/javascript">

$(document).ready(function(){

function deleted(act,langid,value,id)

{

jConfirm('{$vsLang->getWords('lang_confirm','Confirm delete')} "'+value+'"?', 'Confirmation Dialog', function(r) {

    if(r)

vsf.get('languages/'+act+'/'+langid+'/'+value,id);

});

}

});

</script>

<div class="ui-tabs ui-widget ui-widget-content ui-corner-all-top">

    <div id="langmodule" class="left-cell">

    {$show['form']}

    

        <div class="clear"></div>

    </div>

    <div id="langlist" class="right-cell">

    {$show['list']}

        <div class="clear"></div>

</div>

    <div class="clear"></div>

    <div class="ui-state-error ui-corner-all" style="padding:10px 5px;">

        <p align="left">

            <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span>

            <strong>Alert:</strong> The system language is currently stored in <strong>{$storein}</strong>. 

    So if you make any change it will be saved to <strong>{$storein}</strong>.

        </p>

    </div>

</div>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:addEditLangForm:desc::trigger:>
//===========================================================================
function addEditLangForm($form="") {
global $vsLang, $bw;

$BWHTML = "";

$langua = $form ['language'];


//--starthtml--//
$BWHTML .= <<<EOF
		<form id="LANGFORM" nam='LANGFORM'>

<input type="hidden" name="FormType" value="{$form ['type']}" />

<input type="hidden" name="langId" value="{$langua->getId()}" />

<input type="hidden" name="oldFolder" value="{$langua->getFolderName()}" />

<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">

<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">

    <span class="ui-dialog-title">{$form ['title']}</span>

    </div>

<div class="error">{$form ['message']}</div>

<table class="ui-dialog-content ui-widget-content" cellpadding="0" cellspacing="0">

<tr>

        <td>{$vsLang->getWords('lang_name','Language')}</td>

        <td><input type="text" id="langName" name="langName" value="{$langua->getName()}" size="32"></td>

</tr>

<tr>

        <td>{$vsLang->getWords('lang_folder','Folder')}</td>

        <td><input type="text" id="langFolder" name="langFolder" value="{$langua->getFolderName()}" size="32"></td>

</tr>

<tr>

        <td>{$vsLang->getWords('lang_Udefault','User Def')}</td>

        <td>

            <input class="checkbox" type="checkbox" name="userDefault" value="1" >

            <label for="langDefault_first">Yes</label>

           

        </td>

</tr>

<tr>

        <td>{$vsLang->getWords('lang_Adefault','Admin Def')}</td>

        <td>

            <input class="checkbox" type="checkbox"  name="adminDefault" value="1" />

            <label for="langType_first">Yes</label>

        </td>

</tr>

<tr>

        <td>{$vsLang->getWords('lang_active','Active')}</td>

        <td>

            <input class="checkbox" type="radio" name="langStatus" value="1" >

            <label for="langIsActive_first">Yes </label>

            <input class="checkbox" type="radio" name="langStatus" value="0" >

            <label for="langIsActive_last">No </label>

        </td>

</tr>

    <tr>

    <td>{$vsLang->getWords('lang_symbol',Symbol)}</td>

        <td class="ui-dialog-selectpanel">

       <select size="5" multiple="multiple" id="langSymbol" name="langSymbol">

       {$this->__foreach_loop__id_4e13cec2c1ce0($form)}

</select>

        </td>

    </tr>

<tr>

        <td align="left" style="padding-left:70px;" colspan="2" class="ui-dialog-buttonpanel">

        {$form['switchform']}

        <input type="submit" value="{$form['submit']}" /></td>

        </td>

</tr>

</table>

</div>

</form>

<script language="javascript">

$(document).ready(function(){


var the_LANGFORM= window.document.getElementById('LANGFORM');

vsf.checkbox('{$langua->getAdminDefault()}',the_LANGFORM.adminDefault);

vsf.radio('{$langua->getStatus()}',the_LANGFORM.langStatus);

vsf.checkbox('{$langua->getUserDefault()}',the_LANGFORM.userDefault);

vsf.select('{$langua->getSymbol()}',the_LANGFORM.langSymbol);


});

$('#LANGFORM').submit(function() {

if(!$('#langName').val()) {

jAlert('{$vsLang->getWords('err_lang_name_blank','Please enter the language name!')}','{$bw->vars['global_websitename']} Dialog');

$('#langName').addClass('ui-state-error ui-corner-all-inner');

$('#langName').focus();

return false;

}

if(!$('#langFolder').val()) {

jAlert('{$vsLang->getWords('err_lang_folder_blank','Please enter the folder name!')}','{$bw->vars['global_websitename']} Dialog');

$('#langFolder').addClass('ui-state-error ui-corner-all-inner');

$('#langFolder').focus();

return false;

}

vsf.submitForm($(this),'Languages/addEditLang/','langlist');

vsf.get('Languages/addLangForm/','langmodule');

});

</script>
EOF;
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e13cec2c1ce0($form="")
{

global $vsLang, $bw;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $form['flags'] as $flag )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

<option value="{$flag['value']}">{$flag['name']}</option>


EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:getLangList:desc::trigger:>
//===========================================================================
function getLangList($showval="") {
global $vsLang, $bw;


$show = "";

$BWHTML = "";

//--starthtml--//



//--starthtml--//
$BWHTML .= <<<EOF
		<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">

<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">

    <span class="ui-dialog-title">{$vsLang->getWords('lang_list','Current Language Packs In Use')}</span>

    </div>

<div class="error">{$showval['mess']}</div>

<table cellspacing="0" cellpadding="0" class="ui-dialog-content ui-widget-content" style="width:100%;">

    <thead>

        <tr>

                <th>{$vsLang->getWords('lang_symbol','Symbol')}</th>

                <th>{$vsLang->getWords('lang_name','Language')}</th>

                <th>{$vsLang->getWords('lang_active','Status')}</th>

                <th>{$vsLang->getWords('lang_folder','Folder name')}</th>

                <th>{$vsLang->getWords('lang_userdefault','Default User')}</th>

                <th>{$vsLang->getWords('lang_admindefault','Default Admin')}</th>

                <th>{$vsLang->getWords('lang_option','Option')}</th>

            </tr>

        </thead>

{$this->__foreach_loop__id_4e13cec2c20c6($showval)}

</table>

</div>
EOF;
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e13cec2c20c6($showval="")
{

global $vsLang, $bw;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $showval['values'] as $show )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

<tr class="{$show['format_class']}">

    <td align="center"><img src="{$bw->vars['img_url']}/flags/{$show['langSymbol']}"></td>

    <td align="center">

    <a href="javascript:vsf.get('languages/editLang/{$show['langId']}/','langmodule');" title='{$vsLang->getWords('newsItem_EditObjTitle',"Click here to edit this {$bw->input[0]}")}' class="editObj">

{$show['langName']}

</a>

</td>

    <td align="center"><img src="{$bw->vars['img_url']}/{$show['imageactive']}.png" width="14" alt="{$show['imageactive']}" /></td>

    <td align="center">{$show['langFolder']}</td>

    <td align="center">{$show['userDefault']}</td>

    <td align="center">{$show['adminDefault']}</td>

    <td align="center">

<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:deleted('deleteLang',{$show['langId']},'{$show['langName']}','langlist')" title='{$vsLang->getWords('newsItem_EditObjTitle',"Click here to delete this {$bw->input[0]}")}'>{$vsLang->getWords('global_del','Xóa')}</a>

        {$show['defimg']}

    </td>

    </tr>

    


EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:wordList:desc::trigger:>
//===========================================================================
function wordList($showall="") {
global $bw, $vsLang;

$BWHTML = "";

//--starthtml--//


//--starthtml--//
$BWHTML .= <<<EOF
		<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">

<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">

    <span class="ui-dialog-title">{$vsLang->getWords('lang_module_add_text',' Add module′s language text')}</span>

    </div>

    <div class="red">{$showall['message']}</div>

    <form action="javascript:vsf.submitForm($('#add_item_{$bw->input[2]}'),'languages/addMore','langlist_{$bw->input[2]}')" method="Post" id="add_item_{$bw->input[2]}">

    <input type="hidden" name="2" value="{$bw->input[2]}">

    <input type="hidden" name="langtype" value="{$bw->input[3]}">

    <table class="ui-dialog-content ui-widget-content" cellpadding="0" cellspacing="0" style="width:100%;">

    <thead>

        <tr>

            <th>{$vsLang->getWords('lang_module_text_key','Language key')}</th>

                <th>{$vsLang->getWords('lang_module_text_value','Language value')}</th>

                <th></th>

            </tr>

        </thead>


<tr>

        <td ><input type="text" name="wkey" value=""></td>

<td><textarea name="wdisplay" cols="55" rows="1"></textarea></td>

<td class="ui-dialog-buttonpanel"><input class="button" type="submit" value="Add" name="submit"></td>

</tr>

</table>

<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">

    <span class="ui-dialog-title">{$vsLang->getWords('lang_module_text','Current module′s language text')} : [{$showall['moduleName']}]</span>

    </div>

    </form>

    <form action="javascript:vsf.submitForm($('#frm_save_{$bw->input[2]}'),'languages/saveLangItem','langlist_{$bw->input[2]}')" id='frm_save_{$bw->input[2]}' method="Post">

    <input type="hidden" name="2" value="{$bw->input[2]}">

    <input type="hidden" name="langtype" value="{$bw->input[3]}">

<table class="ui-dialog-content ui-widget-content" cellpadding="0" cellspacing="0" style="width:100%;">

    <thead>

        <tr>

            <th>{$vsLang->getWords('lang_module_text_key')}</th>

                <th>{$vsLang->getWords('lang_module_text_value')}</th>

                <th>{$vsLang->getWords('lang_option')}</th>

            </tr>

        </thead>

        

        {$this->__foreach_loop__id_4e13cec2c24d6($showall)}


        <tr>

        <td colspan="2" class="ui-dialog-buttonpanel">

                <input class="button" type="Submit" name="Submit" value="Save" />

            </td>

        </tr>

</form>

</table>

</div>
EOF;
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e13cec2c24d6($showall="")
{

global $bw, $vsLang;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $showall['WORD_ITEM'] as $word )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

<tr>

<td ><p style="width:150px;overflow:auto">{$word['key']}</p></td>

<td><textarea name="key_{$word['key']}" cols="50" rows="1">{$word['value']}</textarea></td>

<td>

<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:deleted('deleteWord',{$bw->input[2]},'{$word['key']}','langlist_{$bw->input[2]}')" title='{$vsLang->getWords('newsItem_EditObjTitle',"Click here to delete this {$bw->input[0]}")}'>{$vsLang->getWords('global_del','Xóa')}</a>

</td>

</tr>


EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:addLangItemForm:desc::trigger:>
//===========================================================================
function addLangItemForm() {
global $bw, $vsLang, $langtype;


$BWHTML = "";

//--starthtml--//





//--starthtml--//
$BWHTML .= <<<EOF
		<script type="text/javascript"> 

$('#addlangitem_{$bw->input[2]}').submit(function() {


if(!$('#moduleName_{$bw->input[2]}').val()) {

alert('{$vsLang->getWords('err_lang_name_blank','Please enter the module name!')}');

$('#moduleName').addClass('ui-state-error ui-corner-all-inner');

$('#moduleName').focus();

return false;

}

vsf.submitForm($(this),'languages/addLangItem','lang_item_list_{$bw->input[2]}');

vsf.get('languages/additemform/{$bw->input[2]}/','langitemform_{$bw->input[2]}');

});

</script>

<div id="langitemform_{$bw->input[2]}">

<form id="addlangitem_{$bw->input[2]}">

<input type="hidden" name="langId" value="{$bw->input[2]}">

<input type="hidden" name="langtype" value="{$langtype}">

<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">

<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">

    <span class="ui-dialog-title">{$vsLang->getWords('lang_module_add',' Add module′s language file  ')}</span>

    </div>

    <table class="ui-dialog-content ui-widget-content" cellpadding="0" cellspacing="0">

    <tr>

        <td>{$vsLang->getWords('lang_module','Module')}</td>

            <td><input type="text" id="moduleName_{$bw->input[2]}" name="moduleName" value=" "></td>

        </tr>

        <tr>

        <td class="ui-dialog-buttonpanel" colspan="2"><input class="button" type="submit" name="submit" value="Add"></td>

        </tr>

    </table>

   </div>

</form>

</div>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:langItem:desc::trigger:>
//===========================================================================
function langItem($showall="") {
global $vsLang, $bw;


$BWHTML = "";



//--starthtml--//
$BWHTML .= <<<EOF
		<div class="ui-dialog ui-widget ui-widget-content ui-corner-all" id="lang_item_list_{$bw->input[2]}">

<div class="red">{$showall['message']}</div>

<table class="ui-dialog-content ui-widget-content" cellpadding="0" cellspacing="0" style="width:100%;">

<thead>

    <tr>

        <th>{$vsLang->getWords('lang_itemm_list','Language Item List')}</th>

            <th>{$vsLang->getWords('lang_option','Option')}</th>

        </tr>

    </thead>

    
EOF;
if(count($showall['VAR_LANG_ITEM'])) {
$BWHTML .= <<<EOF


    {$this->__foreach_loop__id_4e13cec2c289d($showall)}


EOF;
}

$BWHTML .= <<<EOF


</table>

</div>
EOF;
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e13cec2c289d($showall="")
{

global $vsLang, $bw;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach(  $showall['VAR_LANG_ITEM'] as $var )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

<tr class="{$var['format_class']}">

<td>

<a href="javascript:;" onclick="javascript:vsf.get('languages/viewItem/{$bw->input[2]}/{$var['langType']}/{$var['langModule']}/','langlist_{$bw->input[2]}')" title="{$vsLang->getWords('global_edit_title','Click here to edit')}" class="editObj">

{$var['langModule']}

</a>

</td>

<td>

<a href="javascript:deleted('deleteItem','{$bw->input[2]}/{$var['langType']}','{$var['langModule']}','MODULE_FILE_LIST_{$bw->input[2]}')" title='{$vsLang->getWords('global_delete_title',"Click here to delete")}' class="ui-state-default ui-corner-all ui-state-focus">

{$vsLang->getWords('global_delele','Delete')}

</a>

</td>

</tr>


EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:FileLangMainAjax:desc::trigger:>
//===========================================================================
function FileLangMainAjax($show="") {
global $bw, $vsLang, $vsSettings;


//--starthtml--//
$BWHTML .= <<<EOF
		<div id="sa_content_{$bw->input[2]}">

    <div id="langmodule_{$bw->input[2]}" class="left-cell">

    <div id="MODULE_FILE_FORM_{$bw->input[2]}">{$show['CURRENT_LANG_ADD_ITEM']}</div>

    <div id="MODULE_FILE_LIST_{$bw->input[2]}">{$show['CURRENT_LANG_ITEM']}</div>

    <div class="clear"></div>

    </div>

    <div id="langlist_{$bw->input[2]}" class="right-cell" style="margin-right:3px;">

    {$show['LANGUAGE_LIST']}

        <div class="clear"></div>

</div>

    <div class="clear"></div>

    
EOF;
if( $vsSettings->getSystemKey('display_alert', 0 , "languages", 1, 1) ) {
$BWHTML .= <<<EOF


    <div class="ui-state-error ui-corner-all" style="padding:10px 5px;">

        <p align="left">

            <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span>

            <strong>Alert:</strong> The system language is currently stored in <strong>{$storein}</strong>. 

    So if you make any change it will be saved to <strong>{$storein}</strong>.

        </p>

    </div>

    
EOF;
}

$BWHTML .= <<<EOF


</div>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:FileLangMain:desc::trigger:>
//===========================================================================
function FileLangMain($show="") {
global $bw, $vsLang, $vsSettings, $vsUser;

$BWHTML = "";


$storein = $bw->vars ['storelangtype'] ? "File" : "Database";


//--starthtml--//
$BWHTML .= <<<EOF
		<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>

    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">

        <span class="ui-icon ui-icon-note"></span>

        <span class="ui-dialog-title">{$vsLang->getWords('language_file_title',"Language Files")}</span>

    </div>

    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">

    
EOF;
if( $vsUser->checkRoot() ) {
$BWHTML .= <<<EOF


    <li class="ui-state-default ui-corner-top" id="add-objlist-bt">

    <a href="#" title="{$vsLang->getWords('user',"User")}" onclick="javascript: vsf.get('languages/viewLangModule/{$bw->input[2]}/user/','sa_content_{$bw->input[2]}'); return false">

{$vsLang->getWords('user',"User")}

</a>

</li>


EOF;
}

$BWHTML .= <<<EOF


        <li class="ui-state-default ui-corner-top" id="hide-objlist-bt">

        <a href="#" title="{$vsLang->getWords('admin',"Admin")}" onclick="javascript: vsf.get('languages/viewLangModule/{$bw->input[2]}/admin/','sa_content_{$bw->input[2]}'); return false">

{$vsLang->getWords('admin','Admin')}

</a>

</li>

    </ul>

<div id="sa_content_{$bw->input[2]}">

    <div id="langmodule_{$bw->input[2]}" class="left-cell">

    <div id="MODULE_FILE_FORM_{$bw->input[2]}">{$show['CURRENT_LANG_ADD_ITEM']}</div>

    <div id="MODULE_FILE_LIST_{$bw->input[2]}">{$show['CURRENT_LANG_ITEM']}</div>

    <div class="clear"></div>

    </div>

    <div id="langlist_{$bw->input[2]}" class="right-cell">

    {$show['LANGUAGE_LIST']}

        <div class="clear"></div>

</div>

    <div class="clear"></div>

    

    
EOF;
if( $vsSettings->getSystemKey('display_alert', 0 , "languages", 1, 1) ) {
$BWHTML .= <<<EOF


    <div class="ui-state-error ui-corner-all" style="padding:10px 5px;">

        <p align="left">

            <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span>

            <strong>Alert:</strong> The system language is currently stored in <strong>{$storein}</strong>. 

    So if you make any change it will be saved to <strong>{$storein}</strong>.

        </p>

    </div>

    
EOF;
}

$BWHTML .= <<<EOF


    

</div>

</div>
EOF;
//--endhtml--//
return $BWHTML;
}


}?>