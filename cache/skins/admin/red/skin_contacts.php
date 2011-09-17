<?php
class skin_contacts{

//===========================================================================
// <rsf:contactList:desc::trigger:>
//===========================================================================
function contactList($option="") {
global $vsLang, $bw, $vsPrint;

$vsPrint->addJavaScriptFile ( 'jquery/jquery.tablesorter' );

$BWHTML = "";


//--starthtml--//
$BWHTML .= <<<EOF
		<div id="ContactList">

<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>

<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">

        <span class="ui-icon ui-icon-note"></span>

        <span class="ui-dialog-title">{$vsLang->getWords('obj_objListHtmlTitle','Contact List')}</span>

    </div>

<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">

    <li class="ui-state-default ui-corner-top" id="add-objlist-bt">

    <a href="#" title="{$vsLang->getWords('add_obj_alt_bt','Delete')}" onclick="deleteObj({$option['contactType']}, '{$option['pIndex']}')">

{$vsLang->getWords('add_obj_delete_bt',"Delete")}

</a>

</li>

    </ul>


<div class="message">{$option['message']}</div>

<table cellpadding="1" cellspacing="1" class='ui-dialog-content ui-widget-content' id='contactItemTable' width="100%">

<thead>

    <tr>

        <th width='10'>

        <input type="checkbox" onclick="checkAll{$option['contactType']}()" name="all{$option['contactType']}" />

        </th>

        <th>{$vsLang->getWords('contactTitle','Title')}</th>

        <th width='300'>{$vsLang->getWords('from','From')}</th>

        <th width='75' >{$vsLang->getWords('time','Time')}</th>

        <th width='50' >{$vsLang->getWords('status','Status')}</th>

    </tr>

</thead>

<tbody>

    
EOF;
if(is_array($option['pageList'])) {
$BWHTML .= <<<EOF


    {$this->__foreach_loop__id_4e7406a947a0f($option)}

    
EOF;
}

$BWHTML .= <<<EOF


    </tbody>

    
EOF;
if( $option['paging'] ) {
$BWHTML .= <<<EOF


    <tfoot>

    <tr>

    <th colspan="5" align="right">

        {$option['paging']}

        </th>

    </tr>

</tfoot>


EOF;
}

$BWHTML .= <<<EOF


</table>

</div>

</div>




<script type='text/javascript'>

function checkAll{$option['contactType']}() {

var checked_status = $("input[name=all{$option['contactType']}]:checked").length;

var checkedString = '';

$("input[type=checkbox]").each(function(){

if($(this).hasClass('myCheckbox{$option['contactType']}')){

this.checked = checked_status;

if(checked_status) checkedString += $(this).val()+',';

}

});

$("span[acaica=myCheckbox{$option['contactType']}]").each(function(){

if(checked_status)

this.style.backgroundPosition = "0 -50px";

else this.style.backgroundPosition = "0 0";

});

$('#checked-obj').val(checkedString);

}

function deleteObj(contactType, pIndex){

jConfirm(

'{$vsLang->getWords("contact_deleteContactConfirm","Are you sure to delete this contact information?")}', 

'{$bw->vars['global_websitename']} Dialog', 

function(r){

if(r){

var jsonStr = "";

$("input[type=checkbox]").each(function(){

if($(this).hasClass('myCheckbox'+contactType)){

if(this.checked) jsonStr += $(this).val()+',';

}

});


if(jsonStr == ""){

jAlert(

"{$vsLang->getWords('contact_deleteAllConfirm_NoItem', "You haven't choose any items!")}",

"{$bw->vars['global_websitename']} Dialog"

);

return false;

}


jsonStr = jsonStr.substr(0,jsonStr.lastIndexOf(','));


vsf.get('contacts/deleteAllContact/'+contactType+'/'+jsonStr+'/','ContactList');

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
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4e7406a947a0f($option="")
{

global $vsLang, $bw, $vsPrint;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $option['pageList'] as $contactP )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		

    <tr class='$vsf_class'>

<td style="text-align:center">

<input type="checkbox" name="obj_{$contactP->getId()}" value="{$contactP->getId()}" class="myCheckbox{$option['contactType']}" />

</td>

<td >

<a title='{$contactP->getName()}' onclick="javascript:vsf.get('contacts/read/{$contactP->getId()}/', 'replyForm');" class="editObj">

{$contactP->getTitle()}

</a>

</td>

<td >{$contactP->getName()} ({$contactP->getEmail()})</td>

<td >{$contactP->getPostDate("SHORT")}</td>

<td>{$contactP->getIsReply()}</td>

</tr>

    
EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:readContactInfo:desc::trigger:>
//===========================================================================
function readContactInfo($contact="",$contactProfile="") {
global $vsLang;


//--starthtml--//
$BWHTML .= <<<EOF
		<div id='viewFormContainer' class='ui-dialog ui-widget ui-widget-content ui-corner-all'>

    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">

    <span class="ui-icon ui-icon-note"></span>

<span class="ui-dialog-title">{$vsLang->getWords('contactReadTitle','Read Email')}: {$contact->getTitle()}</span>

        <p style="float:right; cursor:pointer;">

<span class='ui-dialog-title' id='viewForm'>

{$vsLang->getWords('obj_back', 'Back')}

</span>

</p>

        </a>

</div>

<table cellpadding="1" cellspacing="1" border="0" class="ui-dialog-content ui-widget-content" width="100%">

<tr class="smalltitle">

<td class='left' width="100">{$vsLang->getWords('contactCompany','Company:')}</td>

<td class='right'>{$contactProfile["contactCompany"]}</td>

</tr>

<tr class="smalltitle">

        <td class='left' width="100">{$vsLang->getWords('contactName','Fullname')}:</td>

<td class='right'>{$contact->getName()}</td>

</tr>

        <tr class="smalltitle">

        <td class='left' width="100">{$vsLang->getWords('contactEmail','Email')}:</td>

            <td class='right'>{$contact->getEmail()}</td>

</tr>

        <tr class="smalltitle">

        <td class='left' width="100">{$vsLang->getWords('contactPhone','Phone')}:</td>

            <td class='right'>{$contactProfile['contactPhone']}</td>

</tr>

<tr class="smalltitle">

        <td class='left' width="100">{$vsLang->getWords('contactAddress','Address')}:</td>

             <td class='right'>{$contactProfile['contactAddress']}</td>

</tr>

        <tr class="smalltitle">

        <td class='left' width="100">{$vsLang->getWords('contactTime','Post Time')}:</td>

            <td class='right'>{$contact->getPostDate("SHORT")}</td>

</tr>

        <tr>

        <td valign="top" class="smalltitle">{$vsLang->getWords('contactMessage','Message')}:</td>

            <td class="ui-dialog-buttonpanel smalltitle">

            <input id='replyButton' value="{$vsLang->getWords('contactReply','Reply')}" type="button" />

</td>

</tr>

        <tr>

        <td colspan="2" valign="top">

            <div id='contactMessage' style="height: 150px; background-color: #EBEEF7; padding: 0px 5px;">

{$contact->getContent()}

               </div>

</td>

</tr>

</table>

</div>


<script type='text/javascript'>

$('#viewForm').click(function(){

$('#viewFormContainer').remove();

});


$('#replyButton').click(function(){

var containerDiv='<div id="container" style="top:20px!important;"></div>'

$('#vswrapper').append(containerDiv);


vsf.get('contacts/reply/{$contact->getId()}/', 'replyForm');

});

</script>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:replyContactForm:desc::trigger:>
//===========================================================================
function replyContactForm($option="") {
global $vsLang;


//--starthtml--//
$BWHTML .= <<<EOF
		<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>

<form id="formReply" method="post">

<input type="hidden" name="email" value="{$option['obj']->getEmail()}"/>

<input type="hidden" name="name"  value="{$option['obj']->getName()}"/>

<input type="hidden" name="title"  value="{$option['obj']->getTitle()}"/>

<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">

<span class="ui-icon ui-icon-note"></span>

<span class="ui-dialog-title">

{$vsLang->getWords('contactReplyFormTitle','Reply Email')}

</span>

<span id="buttonClose" class="closePage" title="{$vsLang->getWords('global_undo','Trở lại')}"></span>

</div>

{$option['replyFormEditor']}

</form>

<a class="ui-state-default ui-corner-all ui-state-focus" id="buttonSend" style="float:right; width: 80px; margin: 5px; align: right;"> 

{$vsLang->getWords('contacts_replyForm_Send','Send Reply')}

</a>

<div class="clear"></div>

</div>


<script type='text/javascript'>

function sendReply(){

$('#formReply').submit();

$('#buttonClose').click();

}


$('#buttonSend').click(function(){

$('#formReply').submit();

$('#buttonClose').click();

});


$('#buttonClose').click(function(){

$("#replyForm").html('');

});


$('#formReply').submit(function(){


vsf.submitForm($(this),'contacts/replyProcess/{$option["obj"]->getId()}/{$option["obj"]->getType()}/','maincontent');

return false;

});

</script>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:contactMainLayout:desc::trigger:>
//===========================================================================
function contactMainLayout($option="") {
global $bw, $vsLang, $vsSettings;

$BWHTML = "";


//--starthtml--//
$BWHTML .= <<<EOF
		<div id="replyForm"><div style="color:red;">{$option['message']}</div></div>

<div id="page_tabs"  class="ui-tabs ui-widget ui-widget-content ui-corner-all-top">

<ul id="tabs_nav" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner">    

        <li class="ui-state-default ui-corner-top  ui-tabs-selected ui-state-active">

        <a href="{$bw->base_url}contacts/showContact/0/&ajax=1" ><span>{$vsLang->getWords('tab_contacts_comment','Góp ý')}</span></a>

        </li>

        <li class="ui-state-default ui-corner-top">

        <a href="{$bw->base_url}pages/pageCode/contacts/&ajax=1" ><span>{$vsLang->getWords('tab_contacts_content','Nội dung')}</span></a>

        </li>

        
EOF;
if($vsSettings->getSystemKey($bw->input[0].'_setting_tab',1,$bw->input[0],1,1)) {
$BWHTML .= <<<EOF


        <li class="ui-state-default ui-corner-top">

        <a href="{$bw->base_url}settings/moduleObjTab/{$bw->input[0]}/&ajax=1">{$vsLang->getWords('tab_contact_SS','Settings')}</a>

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