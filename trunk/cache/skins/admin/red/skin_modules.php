<?php
class skin_modules{

//===========================================================================
// <rsf:addEditModuleForm:desc::trigger:>
//===========================================================================
function addEditModuleForm($formtitle="",$formtype=0,$module="") {
global $bw,$vsLang;

$BWHTML = "";

//--starthtml--//

$submit = "Add";

if($formtype) {

$submit = "Edit";

$switchform = <<<EOF

<button class="ui-state-default ui-corner-all" type="button" onclick="vsf.get('Modules/addForm/','addeditform');">Switch to Add Form</button>

EOF;

}

$moduleadmin = "";

if($module->getAdmin()) $moduleadmin = "checked";

$moduleuser = "";

if($module->getUser()) $moduleuser = "checked";


//--starthtml--//
$BWHTML .= <<<EOF
		<script></script>

<form action="javascript:vsf.submitForm($('#addmodule'),'Modules/addEdit','modulelist'); javascript:vsf.get('Modules/addForm/','addeditform');" method="post" name="form" id="addmodule">

<input type="hidden" name="FormType" value="{$formtype}" />

<input type="hidden" name="moduleId" value="{$module->getId()}" />


<div class="ui-dialog ui-widget-content ui-corner-all vs-lbox">

<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">

    <span class="ui-icon ui-icon-newwin"></span>

    <span class="ui-dialog-title">{$formtitle}</span>

    </div>

    <table cellpadding="0" cellspacing="1" width="100%">

    <tr>

        <th>{$vsLang->getWords('module_list_name','Tên chức năng')}</th>

            <td><input id="input" type="text" value="{$module->getTitle()}" name="moduleTitle" /></td>

        </tr>

    <tr>

        <th>{$vsLang->getWords('module_list_module','Chức năng')}</th>

            <td><input class="input" type="text" value="{$module->getClass()}" name="moduleClass" /></td>

        </tr>

        <tr>

        <th>{$vsLang->getWords('module_list_desc','Mô tả')}</th>

            <td><textarea cols="25" rows="5" name="moduleIntro">{$module->getIntro()}</textarea></td>

        </tr>

        <tr>

        <th>{$vsLang->getWords('module_list_admin','Quản trị')}</th>

            <td><input class="checkbox" type="checkbox" id="moduleIsAdmin" name="moduleIsAdmin" {$moduleadmin} />

            <label for="moduleIsAdmin"><span class="desctext">(Enable for admin)</span></label></td>

        </tr>

        <tr>

        <th>{$vsLang->getWords('module_list_user','Người dùng')}</th>

            <td><input class="checkbox" type="checkbox" id="moduleIsUser" name="moduleIsUser" {$moduleuser} /><label for="moduleIsUser"><span class="desctext">(Enable for user)</span></label></td>

        </tr>

        <tr>

        <th>&nbsp;</th>

            <td>{$switchform} <button class="ui-state-default ui-corner-all" type="submit">{$submit}</button></td>

        </tr>

    </table>

</div>

</form>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:editLink:desc::trigger:>
//===========================================================================
function editLink($module="") {
global $bw, $vsLang;

$BWHTML = "";


//--starthtml--//
$BWHTML .= <<<EOF
		<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:vsf.get('Modules/edit/{$module->getId()}/','addeditform');" title='{$vsLang->getWords('newsItem_EditObjTitle',"Click here to edit this {$bw->input[0]}")}'>{$vsLang->getWords('global_edit','Sửa')}</a>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:uninstallLink:desc::trigger:>
//===========================================================================
function uninstallLink($module="") {
global $vsLang;

$BWHTML = "";

//--starthtml--//



//--starthtml--//
$BWHTML .= <<<EOF
		<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:Uninstall({$module->getId()},'{$module->getClass()}')" title='{$vsLang->getWords('newsItem_UinstallObjTitle',"Click here to uninstall database for this module")}'>{$vsLang->getWords('global_uinstall','Uninstall')}</a>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:installLink:desc::trigger:>
//===========================================================================
function installLink($module="") {

$BWHTML = "";

//--starthtml--//



//--starthtml--//
$BWHTML .= <<<EOF
		<a href="javascript:vsf.get('Modules/install/{$module->getClass()}/','modulelist');" Title="Install database for this module">Install</a>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:addModuleList:desc::trigger:>
//===========================================================================
function addModuleList($module="",$editLink="",$installLink="",$rowStyle="") {

$BWHTML = "";

//--starthtml--//

$moduleadmin = "No";

if($module->getAdmin()) $moduleadmin = "Yes";

$moduleuser = "No";

if($module->getUser()) $moduleuser = "Yes";



//--starthtml--//
$BWHTML .= <<<EOF
		<tr class="{$rowStyle}">

<td><strong>{$module->getTitle()} ({$module->getClass()})</strong><br />

<div class="desctext">{$module->getIntro()}</div>

</td>

    <td>{$moduleadmin}</td>

    <td>{$moduleuser}</td>

<td>

    {$editLink}

    {$installLink}

</tr>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:moduleList:desc::trigger:>
//===========================================================================
function moduleList($message="",$moduleListHTML="") {
global $bw, $vsLang;

$BWHTML = "";

//--starthtml--//


//--starthtml--//
$BWHTML .= <<<EOF
		<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">

    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">

    <span class="ui-icon ui-icon-script"></span>

    <span class="ui-dialog-title">{$vsLang->getWords('module_list_title','Module list')}</span>

    </div>

<div class="red">{$message}</div>

<table cellpadding="0" cellspacing="1" width="100%">

    <thead>

        <tr>

            <th>{$vsLang->getWords('module_list_name',"Module name")}</th>

            <th>{$vsLang->getWords('module_list_admin',"Run in admin")}</th>

            <th>{$vsLang->getWords('module_list_user',"Run in public")}</th>

            <th>{$vsLang->getWords('module_list_option',"Option")}</th>

        </tr>

    </thead>

<tbody>

{$moduleListHTML}

</tbody>

</table>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:modulesMain:desc::trigger:>
//===========================================================================
function modulesMain($addFormHTML="",$moduleListHTML="") {
global $bw, $vsLang;

$BWHTML = "";

//--starthtml--//


//--starthtml--//
$BWHTML .= <<<EOF
		<script type="text/javascript">

function xDef() {

for(var i=0; i<arguments.length; ++i){if(typeof(arguments[i])=="" || typeof(arguments[i])=="undefined") return false;}

return true;

}

function xScrollTop() {

var offset=0;

if(xDef(window.pageYOffset)) offset=window.pageYOffset;

else if(document.documentElement && document.documentElement.scrollTop) offset=document.documentElement.scrollTop;

else if(document.body) offset=document.body.scrollTop;

return offset;

}

function Uninstall(id,name){

jConfirm('{$vsLang->getWords('module_ask','Are you sure uninstall this module')} ['+name+']?','{$bw->vars['global_websitename']} Dialog',function(r){

if(r)

  vsf.get('modules/uninstall/'+id+'/','modulelist');

})

}


</script>

<div id="addeditform" class="left-cell" style="width:35%">

{$addFormHTML}

<div class="clear"></div>

</div>

<div id="modulelist" class="right-cell" style="width:64%">{$moduleListHTML}</div>
EOF;
//--endhtml--//
return $BWHTML;
}


}?>