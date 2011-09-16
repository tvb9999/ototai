<?php
class skin_urlalias {

	//=========================================
	// CUP ZONE
	//=========================================

	function objListHtml($option =  array()) {
		global $bw,$vsLang;
		$count=2;
		$BWHTML = "";
		//--starthtml--//

		$BWHTML .= <<<EOF
<input type="hidden" name="checkedObj" id="checked-obj" value="" />
<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
	<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
    	<span class="ui-dialog-title">{$vsLang->getWords('alias_list','Current Alias')}</span>
    </div>
	<div  class="error">{$option['message']}</div>
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">
    	<li class="ui-state-default ui-corner-top">
    		<a id="addPage" title="{$vsLang->getWords('pages_addPage','Add')}" onclick="addPage()" href="javascript:vsf.get('admins/addformadmin/','adminform');">
    		{$vsLang->getWords('pages_addPage','Add')}
			</a>
    	</li>
    	<li class="ui-state-default ui-corner-top">
        	<a id="delete-objlist-bt" title="{$vsLang->getWords('pages_deletePage','Delete')}" onclick="deleteAll()" href="#">
        	{$vsLang->getWords('pages_deletePage','Delete')}
			</a>
		</li>
	</ul>	
	<table cellspacing="0" cellpadding="0" class="ui-dialog-content ui-widget-content" style="width:100%">
    	<thead >
    		<tr>
    			<th width="15"><input type="checkbox" onclick="checkAll()" onclicktext="checkAll()" name="all" /></th>
               <th>{$vsLang->getWords('alias_usrl','Alias url')}</th>
               <th>{$vsLang->getWords('alias_read_url','Real url')}</th>
               <th>{$vsLang->getWords('alias_keywords','Keywords')}</th>
               <th>{$vsLang->getWords('alias_option','Options')}</th>
               <th style="width:20px;">{$vsLang->getWords('alias_type','Type')}</th>
            </tr>
        </thead>
        <if="$option['list']">
        <foreach="$option['list'] as $alias">
        <php>
        $count++;
        $class="even";
			if($count%2)$class="odd";
			
		</php>
		<tr class="{$class}">
			<td align="center">
				<input type="checkbox" onclicktext="checkObject({$alias->getId()});" onclick="checkObject({$alias->getId()});" name="obj_{$alias->getId()}" value="{$alias->getId()}" class="myCheckbox" />
			</td>
			<td>{$alias->getAliasUrl()}<div class="desctext">{$alias->getTitle()}</div></td>
			<td>{$alias->getRealUrl()}</td>
			<td>{$alias->getKeyword()}</td>
			<td>
				<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:vsf.get('{$bw->input[0]}/editAlias/{$alias->getId()}/{$bw->input[2]}','urlForm');" title='{$vsLang->getWords('newsItem_EditObjTitle',"Click here to edit this {$bw->input[0]}")}'>{$vsLang->getWords('global__edit','Edit')}</a> 
			</td>
			<td>
				{$alias->typeText}
			</td>
		</tr>
        </foreach>
        </if>
		<tr><td align="right" colspan="6">
			<if="$option['paging']">
			{$option['paging']}
			</if>
		</td></tr>
		<tfoot>
			<td align="center" class="footTable" colspan="6">
			
			</td>
		</tfoot>
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
	checkedString = checkedString.substr(0,checkedString.lastIndexOf(','));
	$('#checked-obj').val(checkedString);
}




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
				vsf.get('urlalias/deleteObj/'+lists+'/{$bw->input[2]}','urlCurrent');
				return false;
				}
			}
		);
	});

</script>
EOF;
			//--endhtml--//

			return $BWHTML;
	}
function showAddEditForm($form = array(), $alias) {
		global $vsLang;
		$BWHTML = "";
		//--starthtml--//

		$BWHTML .= <<<EOF
<form  method="post" id="add_url_form">
	<input type="hidden" name="formType" value="{$form['formType']}" />
	<input type="hidden" name="seoId" value="{$alias->getId()}" />
	<div class="ui-dialog ui-widget ui-widget-content ui-corner-all vs-lbox">
		<div class="vs-lbox-content form-content">
    	<div style="width:10%;float:left;margin-top:10px;">{$vsLang->getWords('alias_url','Alias url')}</div>
        <div style="width:90%;float:left;margin-top:10px;"><input style=";width:100%;float:left" id="input" type="text" value="{$alias->getAliasUrl()}" name="seoAliasUrl" size="28" /></div><div class="clear"></div>
        <div style="width:10%;float:left;margin-top:10px;">{$vsLang->getWords('alias_read_url','Read url')}</div>
        <div style="width:90%;float:left;margin-top:10px;"><input style="width:100%;float:left" class="input" type="text" value="{$alias->getRealUrl()}" name="seoRealUrl" size="28" /></div><div class="clear"></div>
        <div style="width:10%;float:left;margin-top:10px;">{$vsLang->getWords('alias_title','Title')}</div>
        <div style="width:90%;float:left;margin-top:10px;"><input style="width:100%;float:left" class="input" type="text" value="{$alias->getTitle()}" name="seoTitle" size="28" /></div><div class="clear"></div>
         <div style="width:10%;float:left;margin-top:10px;">{$vsLang->getWords('alias_keyword','Keyword')}</div>
        <div style="width:90%;float:left;margin-top:10px;"><textarea style="width:100%;float:left" cols="22" rows="5" name="seoKeyword">{$alias->getKeyword()}</textarea></div><div class="clear"></div>
        <div style="width:10%;float:left;margin-top:10px;">{$vsLang->getWords('alias_intro','Intro')}</div>
        <div style="width:90%;float:left;margin-top:10px;"><textarea style="width:100%;float:left" cols="22" rows="5" name="seoIntro">{$alias->getIntro()}</textarea></div>
        	<input  type="hidden" value="1" name="seoType">
        <div class="clear"></div>
      		<div class="ui-dialog-buttonpanel">{$form['switchform']} 
      		<input  style="width:100px;margin-left:40%;margin-top:10px;" type="submit" name="submit" value="{$form['submit']}" /></div>    
	    <div class="clear"></div>
	    <div class="result"></div>
	    <div class="clear"></div>
	    </div>
	</div>
</form>
<script>
	var f = {
			aliasUrl: $("input[name='seoAliasUrl']"),
			realUrl: $("input[name='seoRealUrl']"),
			title: $("input[name='seoTitle']"),
			keyWord: $("input[name='seoKeyword']"),
			intro: $("input[name='seoIntro']"),
			btnSm:$("input[type='submit']"),
			result:$(".vs-lbox .result")
		}
		
	$(document).ready(function(){
		var space="-";
		if(!f.realUrl.val())f.realUrl.val(realUrl);
		if(!f.aliasUrl.val())f.aliasUrl.val(aliasUrl);
		f.aliasUrl.keydown(function(e){
			if(e.keyCode==32){
				var startPos = this.selectionStart;
				var endPos = this.selectionEnd;
				
				var text = $(this).val().substring(0,this.selectionStart)+space+$(this).val().substring(this.selectionStart,$(this).val().length);
				$(this).val(text);
				this.selectionStart=startPos+space.length;
				this.selectionEnd=startPos+space.length;		
				return false;
			}
		});
	});
	
	
	
	

	$("#add_url_form").submit(function(){
		var flag=false;
		if(!f.aliasUrl.val()||!f.realUrl.val()){
			flag=true;
		}
		if(flag){
			 f.result.html("Alias url and real url is not a null value!");
		}else{
			f.btnSm.attr("disabled",true);
			f.result.html('<img src="'+imgurl+'loader.gif"/>');
			vsf.submitForm($('#add_url_form'),'urlalias/addEditObjPublic&json=1','urlCurrent',{
				sucess:function(data){
					if(data.status){
						f.result.html(data.message);
						$('#add_url_form').append('<input type="hidden" name="seoId" value="'+data.id+'"/>');
						currentSeoIdInput.val(data.id);
						f.btnSm.val("Edit");
						
					}else{
						f.result.html("Error:"+data.Error);
					}
					f.btnSm.attr("disabled",false);
				},
				json:true
			});
			return false;
		}
		return false;
	});
</script>
EOF;
		//--endhtml--//

		return $BWHTML;
	}
	function addEditObjForm($form = array(), $alias) {
		global $vsLang;
		if(!$alias->getType()) $alias->setType(0);
		 
		$check[$alias->getType()]="checked='checked'";
		$BWHTML = "";
		//--starthtml--//

		$BWHTML .= <<<EOF
<form action="javascript:vsf.submitForm($('#addurl'),'urlalias/addEditObj','urlCurrent'); javascript:vsf.get('urlalias/addAlias/','urlForm');" method="post" id="addurl">
	<input type="hidden" name="formType" value="{$form['formType']}" />
	<input type="hidden" name="seoId" value="{$alias->getId()}" />
	<div class="ui-dialog ui-widget ui-widget-content ui-corner-all vs-lbox">
		<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
	    	<span class="ui-dialog-title">{$form['title']}</span>
	    </div>
		<div class="vs-lbox-content form-content">
    	<div class="text-cell">{$vsLang->getWords('alias_url','Alias url')}</div>
        <div class="input-cell"><input id="input" type="text" value="{$alias->getAliasUrl()}" name="seoAliasUrl" size="28" /></div><div class="clear"></div>
        <div class="text-cell">{$vsLang->getWords('alias_read_url','Read url')}</div>
        <div class="input-cell"><input class="input" type="text" value="{$alias->getRealUrl()}" name="seoRealUrl" size="28" /></div><div class="clear"></div>
        <div class="text-cell">{$vsLang->getWords('alias_title','Title')}</div>
        <div class="input-cell"><input class="input" type="text" value="{$alias->getTitle()}" name="seoTitle" size="28" /></div><div class="clear"></div>
         <div class="text-cell">{$vsLang->getWords('alias_keyword','Keyword')}</div>
        <div class="input-cell"><textarea cols="22" rows="5" name="seoKeyword">{$alias->getKeyword()}</textarea></div><div class="clear"></div>
        <div class="text-cell">{$vsLang->getWords('alias_intro','Intro')}</div>
        <div class="input-cell"><textarea cols="22" rows="5" name="seoIntro">{$alias->getIntro()}</textarea></div>
        <div class="clear"></div>
        
        <div class="text-cell">{$vsLang->getWords('alias_type','type')}</div>
        <div class="ui-dialog-buttonpanel"><input type="Radio" {$check[0]} name="seoType" value="0" />global  <input type="Radio" {$check[1]} name="seoType" value="1" />Details</div>
        <div class="clear"></div>
      		<div class="ui-dialog-buttonpanel">{$form['switchform']} <input type="submit" name="submit" value="{$form['submit']}" /></div>    
	    <div class="clear"></div>
	    </div>
	</div>
</form>
	<script>
		var f={
				aliasUrl: $("input[name='seoAliasUrl']"),
				realUrl: $("input[name='seoRealUrl']"),
				title: $("input[name='seoTitle']"),
				keyWord: $("input[name='seoKeyword']"),
				intro: $("input[name='seoIntro']"),
				btnSm:$("input[type='submit']"),
			}
			$(document).ready(function(){
				f.aliasUrl.keyup(function(event){
					if(event.keyCode=='32')
						$(this).val($(this).val().replace(' ','-'));
				});
			});
			$("#addurl").submit(function(){
			 	if(!f.aliasUrl.val()){
			 		f.aliasUrl.css( "background","#FF6600");
			 		return false;
			 	}
			 	if(!f.realUrl.val()){
			 		f.realUrl.css("background","#FF6600");
			 		return false;
			 	}
			});
			
	</script>
EOF;
		//--endhtml--//

		return $BWHTML;
	}

	function managerObjHtml($addeditform = "", $listable = "") {
		$BWHTML = <<<EOF
			<div class="ui-tabs ui-widget ui-widget-content ui-corner-all-top">
			    <div id="urlCurrent" class="right-cell">$listable</div>
			    <div id="urlForm" class="left-cell">$addeditform</div>
			    <div class="clear"></div>
			</div>
EOF;
		//--endhtml--//

		return $BWHTML;
	}
}
?>