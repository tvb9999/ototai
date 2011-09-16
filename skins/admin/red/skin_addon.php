<?php

class skin_addon {

	function acpHelpHTML($acp_help){
		global $bw;
		//--starthtml--//
		$BWHTML .= <<<EOF
<script type="text/javascript">
$(document).ready(function(){
	$("#acp-helper").click(function(){
		$('#acp-helper-expandable').toggle('fast')});
})
</script>
<div class="vsf-help-box">
	<h3><a id="acp-helper" href="#">{$acp_help['help_title']}</a></h3>
	<div id="acp-helper-expandable" style="display:none">{$acp_help['help_body']}</div>
</div>
EOF;
		//--endhtml--//
		return $BWHTML;
	}

	function userLanguages($arrayObj=array(), $title=''){
		global $bw,$vsLang;
		//--starthtml--//
		$BWHTML .= <<<EOF
<div id="user-language">
	<form id="form-user-language" name='form-user-language' method="post">
		<input type="hidden" name="currentModule" value="{$bw->input['module']}" />
		<input type="hidden" name="currentAction" value="{$bw->input['action']}" />
		{$title}
		<select name='languageid' id="language-list">
		<foreach="$arrayObj as $obj">
			<option value="{$obj->getId()}" >{$obj->getName()}</option>
		</foreach>
		</select>
	</form>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#language-list').change(function() {
		$('#form-user-language').submit();
	});
	vsf.jSelect('{$vsLang->currentLang->getId()}','language-list')
	$('#form-user-language').submit(function() {
		var action='{$bw->base_url}languages/switch/'+$('#language-list').val()+'/';
		$('#form-user-language').attr('action',action);
		return true;
	});
});
</script>
EOF;
		//--endhtml--//
		return $BWHTML;
	}

}
?>

