<?php
class skin_skins {

function skinList($option=array()) {
global $vsLang, $bw;
$BWHTML = <<<EOF
<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
<span class="ui-icon ui-icon-triangle-1-e"></span>
<span class="ui-dialog-title">{$vsLang->getWords('skin_list','Current skin')}</span>
</div>
<div class="error">{$message}</div>
<table cellspacing="1" cellpadding="0" width="100%">
<thead>
<tr>
<th>{$vsLang->getWords('skin_list_id','Id')}</th>
<th>{$vsLang->getWords('skin_list_name','Skin name')}</th>
<th>{$vsLang->getWords('skin_list_author','Author')}</th>
<th>{$vsLang->getWords('skin_list_for','Use for')}</th>
<th>{$vsLang->getWords('skin_list_folder','Folder')}</th>
<th>{$vsLang->getWords('skin_list_action','Action')}</th>
</tr>
</thead>
<if="count($option['pageList'])&&is_array($option['pageList'])">
	<foreach="$option['pageList'] as $obj">
	<tr class="{$class}">
		<td>{$obj->getId()}</td>
		<td>{$obj->getTitle()}</td>
		<td>{$obj->getAuthorName()}</td>
		<td>{$obj->getIsAdmin('text')}</td>
		<td>{$obj->getFolder()}</td>
		<td>
			<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:vsf.get('skins/edit-obj/{$obj->getId()}/','skin-form')" title='{$vsLang->getWords('newsItem_EditObjTitle',"Click here to edit this {$bw->input[0]}")}'>{$vsLang->getWords('global_edit','Sửa')}</a>
		</td>
	</tr>
	</foreach>
</if>
</table>
{$option['paging']}
</div>
EOF;
return $BWHTML;		
}

function addEditObjForm($obj=null, $form=array()) {
global $vsLang,$vsSettings;

$BWHTML .= <<<EOF
<form method="post" id="add-obj-form">
<input type="hidden" name="skinId" value="{$obj->getId()}" />
<div class="ui-dialog ui-widget ui-widget-content ui-corner-all vs-lbox">
    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
        <span class="ui-icon ui-icon-triangle-1-e"></span><span class="ui-dialog-title">{$form['formTitle']}</span>
    </div>
    <div class="red">{$form['message']}</div>
    <table cellpadding="0" cellspacing="1" width="100%">
    	<thead>
    		<if="$vsSettings->getSystemKey($bw->input[0].'_title',1)">
        	<tr>
            	<th>{$vsLang->getWords('skin_form_name','Skin name')}</th>
                <td><input id="input" type="text" value="{$obj->getTitle()}" name="skinTitle" size="26" /></td>
            </tr>
            </if>
            <if="$vsSettings->getSystemKey($bw->input[0].'_author_name',1)">
            <tr>
            	<th>{$vsLang->getWords('skin_form_author','Author')}</th>
            	<td><input class="input" type="text" value="{$obj->getAuthorName()}" name="skinAuthorName" size="26" /></td>
            </tr>
            </if>
            <if="$vsSettings->getSystemKey($bw->input[0].'_author_email',1)">
            <tr>
            	<th>{$vsLang->getWords('skin_form_author_email','Author email')}</th>
            	<td><input class="input" type="text" value="{$obj->getAuthorEmail()}" name="skinAuthorEmail" size="26" /></td>
            </tr>
            </if>
            <if="$vsSettings->getSystemKey($bw->input[0].'_author_website',1)">
            <tr>
            	<th>{$vsLang->getWords('skin_form_author_url','Author url')}</th>
            	<td><input class="input" type="text" value="{$obj->getAuthorWebsite()}" name="skinAuthorWebsite" size="26" /></td>
            </tr>
            </if>
            <if="$vsSettings->getSystemKey($bw->input[0].'_folder',1)">
            <tr>
            	<th>{$vsLang->getWords('skin_form_folder','Folder')}</th>
                <td><input class="input" type="text" value="{$obj->getFolder()}" name="skinFolder" size="26" /></td>
            </tr>
            </if>
            <if="$vsSettings->getSystemKey($bw->input[0].'_admin',1)">
            <tr>
            	<th>{$vsLang->getWords('skin_form_use_for','Use For')}</th>
                <td>
                	<input class="input" type="radio" value="1" name="skinIsAdmin" /> Admin 
                	<input class="input" type="radio" value="0" name="skinIsAdmin" /> User
                </td>
            </tr>
            </if>
            <if="$vsSettings->getSystemKey($bw->input[0].'_default',1)">
             <tr>
            	<th>{$vsLang->getWords('skin_form_default','Default')}</th>
                <td><input class="input" type="checkbox" value="1" name="skinDefault" id="skinDefault" /></td>
            </tr>
            </if>
            <tr>
            	<th>&nbsp;</th>
            	<td><if="$obj->getId()">
            			<button id="switch-bt" class="ui-state-default ui-corner-all">{$vsLang->getWords('skin_form_switch',"Switch to add form")}</button>
            		</if>
            		<button id="add-edit-bt" class="ui-state-default ui-corner-all">{$form['formSubmit']}</button>
            	</td>
            </tr>
        </thead>
    </table>
</div>
</form>
<script type="text/javascript">
$(document).ready(function() {
	vsf.jCheckbox('{$obj->getDefault()}','skinDefault');
	vsf.jRadio('{$obj->getIsAdmin()}','skinIsAdmin');
	$('#add-edit-bt').click(function() {
		vsf.submitForm($('#add-obj-form'),'skins/add-edit-obj/','skin-list');
		vsf.get('skins/add-obj/','skin-form');
	});
});
</script>
EOF;

return $BWHTML;
}

function MainPage($objform="",$objlist="") {
$BWHTML .= <<<EOF
<div class="ui-tabs ui-widget ui-widget-content ui-corner-all-top">
	<div id="skin-form" class="left-cell">{$objform}</div>
	<div id="skin-list" class="right-cell">{$objlist}</div>
	<div class="clear"></div>
</div>
EOF;
return $BWHTML;
}
}
?>