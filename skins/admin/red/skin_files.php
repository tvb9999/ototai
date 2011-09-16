<?php
class skin_files {
	
	function MainPage() {
		global $bw, $vsLang;
		
		$BWHTML = "";
		//--starthtml--//
		

		$BWHTML .= <<<EOF
		<div id="page_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all-top">
			<ul id="tabs_nav" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all-inner">
		        <li ><a href="#tab1" ><span>{$vsLang->getWords('tab_files','Files')}</span></a></li>
		        <li class="ui-state-default ui-corner-top"><a href="{$bw->base_url}files/display-type/&ajax=1"><span>{$vsLang->getWords('tab_file_types','File types')}</span></a></li>
		    </ul>
		    <div class="clear"></div>
		    <div id="tab1">
		        <iframe src="{$bw->vars['board_url']}/javascripts/tiny_mce/plugins/imagemanager/pages/im/index.html" class="iframe" frameborder="0" scrolling="auto" width="100%" height="1000" >
		        </iframe>
   			 </div>
		</div>
EOF;
		//--endhtml--//
		

		return $BWHTML;
	}
	
	function MainType($typeFormHTML = "", $typeListHTML = "") {
		$BWHTML = "";
		//--starthtml--//
		

		$BWHTML .= <<<EOF
<div id="addeditform" class="left-cell" style="width:35%">
{$typeFormHTML}
<div class="clear"></div>
</div>
<div id="listtype" class="right-cell" style="width:64%">{$typeListHTML}</div>
EOF;
		//--endhtml--//
		

		return $BWHTML;
	}
	
	function TypeList($type = array(), $message = "") {
		global $vsLang,$bw;
		$BWHTML = "";
		//--starthtml--//
		$count=0;
		$BWHTML .= <<<EOF
		<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
		    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
		    	<span class="ui-icon ui-icon-script"></span>
			    <span class="ui-dialog-title">{$vsLang->getWords('filetype_list_title',"Current file types")}</span>
		    </div>
		<div class="red">{$message}</div>
		<table cellpadding="0" cellspacing="1" width="100%">
		    <thead>
		        <tr>
		            <th>{$vsLang->getWords('filetype_form_id',"Type ID")}</th>
		            <th>{$vsLang->getWords('filetype_form_extension',"Extension")}</th>
		            <th>{$vsLang->getWords('filetype_form_action',"Actions")}</th>
		        </tr>
		    </thead>
		<tbody>
		<if="count($type)">
			<foreach="$type as $value">
				<php> 
					$classType = ($count%2)+1;
					$count++;
	           	</php> 
			<tr class="row{$classType}">
				<td>{$value->no}</td>
				<td>{$value->getExtension()}</td>
				<td>
					<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:vsf.get('files/edit-type/{$value->getId()}/','addeditform')" title='{$vsLang->getWords('newsItem_EditObjTitle',"Click here to edit this {$bw->input[0]}")}'>{$vsLang->getWords('global_edit','Sửa')}</a>
					<a class="ui-state-default ui-corner-all ui-state-focus" href="deleteType({$value->getId()})" title='{$vsLang->getWords('newsItem_EditObjTitle',"Click here to delete this {$bw->input[0]}")}'>{$vsLang->getWords('global_del','Xóa')}</a>
				</td>
			</tr>
			</foreach>
		</if>
		</tbody>
		</table>
</div>
<script type="text/javascript">
	function deleteType(id) {
					jConfirm(
						"{$vsLang->getWords('discover_delete_confirm', "Are you sure want to delete this File Type?")}",
						"{$bw->vars['global_websitename']} Dialog",
						function(r) {
							if(r) {
								javascript:vsf.get('files/delete-type/'+id,'listtype');
							}
						}
					)
				}
</script>
EOF;
		//--endhtml--//
		

		return $BWHTML;
	}
	
	function addEditTypeForm($form = array(), $type) {
		global $vsLang;
		$BWHTML = "";
		//--starthtml--//
		

		$BWHTML .= <<<EOF
<script type="text/javascript">
	$('#form-add-type').submit(function() {
		vsf.submitForm($(this),'files/add-edit-type/','listtype');
		vsf.get('files/add-type','addeditform');
		return false;
	});
</script>

<form id="form-add-type">
<input type="hidden" name="FormType" value="{$form['type']}" />
<input type="hidden" name="fileTypeID" id="file-type-id" value="{$type->getId()}" />
<div class="ui-dialog ui-widget-content ui-corner-all vs-lbox">
	<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
    	<span class="ui-icon ui-icon-newwin"></span>
    	<span class="ui-dialog-title">{$form['title']}</span>
    </div>
    <div class="error">{$form['message']}</div>
    <table cellpadding="0" cellspacing="1" width="100%">
     <tr>
        	<th>{$vsLang->getWords('file_mines','Tên kiểu')}</th>
            <td><input type="text" value="{$type->getMime()}" name="fileTypeMime" size="34" /></td>
        </tr>
    	<tr>
        	<th>{$vsLang->getWords('file_extension','Extension')}</th>
            <td><input type="text" value="{$type->getExtension()}" name="fileExtension" size="34" /></td>
        </tr>
       
        <tr>
        	<th>&nbsp;</th>
            <td>{$form ['switchform']} <button class="ui-state-default ui-corner-all" type="submit">{$form ['formSubmit']}</button></td>
        </tr>
    </table>
</div>
</form>
EOF;
		//--endhtml--//
		

		return $BWHTML;
	}
	
	//===================================================
	// FOLDER ZONE
	//===================================================
	
	function showMessage($message = "") {
		$BWHTML = "";
		//--starthtml--//
		

		$BWHTML .= <<<EOF
<div class="red">
{$message}
</div>
EOF;
		//--endhtml--//
		

		return $BWHTML;
	}
	
	function FolderForm() {
		global $vsLang;
		
		$BWHTML = "";
		//--starthtml--//
		

		$BWHTML .= <<<EOF
			<script type="text/javascript">
				$('#add-folder').submit(function() {
					vsf.submitForm($('#add-folder'),'files/addfolder/','folder-list');
					return false;
				});
			</script>
			<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
				<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
					<span class="ui-dialog-title">{$vsLang->getWords('folder_form_title',"Create new folder")}</span>
				</div>
			<form name="form" id="add-folder">
				<input type="hidden" name="folderPath" value="" id="folder-path" />
				<table class="ui-dialog-content ui-widget-content" cellspacing="0" cellpadding="0">
					<tr><td><input type="text" size="42" value="{$vsLang->getWords('folder_form_name','New Folder')}" name="folderName" onfocus="if(this.value=='{$vsLang->getWords('folder_form_name','New Folder')}') this.value='';" onblur="if(this.value=='') this.value='{$vsLang->getWords('folder_form_name','New Folder')}';" /></td></tr>
					<tr><td class="ui-dialog-buttonpanel"><input type="submit" class="ui-state-default ui-corner-all" name="submit" value="{$vsLang->getWords('folder_form_create_bt',"Create")}" /></td></tr>
				</table>
			</form>
			</div>
EOF;
		//--endhtml--//
		

		return $BWHTML;
	}
	
	function uploadDataResponse($data = array()) {
		$BWHTML = "";
		//--starthtml--//
		

		$BWHTML .= <<<EOF
{
	error: 'sa'
}
EOF;
		//--endhtml--//
		

		return $BWHTML;
	}
	
	function PreviewHTML($file) {
		global $bw;
		$BWHTML = "";
		//--starthtml--//
		if (trim ( substr ( $file->thumbnailpath, 0, 4 ) ) != 'http')
			$file->thumbnailpath = $bw->vars ['board_url'] . "/" . $file->thumbnailpath;
		$BWHTML .= <<<EOF
<div class="preview_image"><img src="{$file->thumbnailpath}" width="150px"></div><div>- Tên file: <strong>{$file->getName()}</strong><br />- Kích thước: <strong>{$file->getSize()}</strong><br />- Kiểu file: <strong>{$file->getType()}</strong></div>
EOF;
		//--endhtml--//
		

		return $BWHTML;
	}
	
	function FolderList($folerlist = "", $createform = "") {
		global $vsLang,$bw;
		
		$BWHTML = "";
		//--starthtml--//
		
		$BWHTML .= <<<EOF
<script type="text/javascript">
	function changeFolder(path) {
		$("#file-path").val(path);
		$("#folder-path").val(path);
		vsf.get('files/getfolder/'+path,'folder-list');
		vsf.get('files/getfilelist/'+path,'list-file');
	}
</script>
<div id="folder-box" class="left-cell">
	{$createform}
	<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
		<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
			<span class="ui-dialog-title">{$vsLang->getWords('folder_list_title',"Current folders")}</span>
		</div>
		<div id="folder-list">
			$folerlist
		</div>
	</div>
</div>
EOF;
		//--endhtml--//
		

		return $BWHTML;
	}
	
	function FolderLink($folder) {
		global $bw;
		$BWHTML = "";
		//--starthtml--//
		

		$BWHTML .= <<<EOF
<foreach="$folder as $thisfile">
				<a href="javascript:changeFolder('{$thisfile['path']}');">
					<img src="{$bw->vars['img_url']}/icons/{$thisfile['icon']}" />{$thisfile['name']}
				</a>
	{$thisfile['delete']}
			</foreach>
EOF;

		return $BWHTML;
	}
	
	//===================================================
	// FOLDER ZONE
	//===================================================
	
	function FileList($fileList = array(), $message = "") {
		global $vsLang;
		$BWHTML = "";
		//--starthtml--//
		$BWHTML .= <<<EOF
			<div class="error">{$message}</div>
			<table cellpadding="3" cellspacing="1" border="0" class="tableborder" width="100%">
				<tr><td class="titlemedium" colspan="6">{$vsLang->getWords('file_list_title',"Current files")}</td></tr>
				<tr>
				<td class="smalltitle" width="50">{$vsLang->getWords('file_list_id',"File ID")}</td>
				<td class="smalltitle" width="100">{$vsLang->getWords('file_list_image',"Image")}</td>
				<td class="smalltitle">{$vsLang->getWords('file_list_information',"Information")}</td>
				<td class="smalltitle">{$vsLang->getWords('file_list_action',"Action")}</td>
				</tr>
				<if="count($fileList)">
					<foreach="$fileList as $file">
					<php> 
						$classType = ($file->stt%2)+1;
           			</php> 
						<tr class="row{$classType}">
							<td>{$file->getId()}</td>
							<td >{$file->show()}</td>
							<td>
							- {$vsLang->getWords('file_list_name',"File name")}: <strong>{$file->getTitle()}</strong><br />
							- {$vsLang->getWords('file_list_size',"File size")}: <strong>{$file->getSize()}</strong><br />
							- {$vsLang->getWords('file_list_type',"File type")}: <strong>{$file->getType()}</strong><br />
							- {$vsLang->getWords('file_list_time',"Uploaded time")}: <strong>{$file->getUploadTime()}</strong><br />
							- {$vsLang->getWords('file_list_desc',"File Intro")}: {$file->getIntro()}
							</td>
							<td>
								<a href="javascript:vsf.get('files/editfile/{$file->getId()}/','add-edit-file-form');">{$vsLang->getWords("file_list_edit_link","Edit")}</a> | 
								<a href="javascript:vsf.get('files/deletefile/{$file->getId()}/','list-file');">{$vsLang->getWords('file_list_delete_link',"Delete")}</a>
							</td>
						</tr>
					</foreach>
				<else /> <tr><td>{$vsLang->getWords('file_list_error',"No file")}</td></tr>
				</if>	
			</table>
EOF;
		//--endhtml--//
		

		return $BWHTML;
	}
	
	function addEditFileForm($form = array(), $file) {
		global $bw, $vsLang;
		$BWHTML = "";
		//--starthtml--//
		$time = time();
		$BWHTML .= <<<EOF
<script type="text/javascript" src="{$bw->vars['board_url']}/javascripts/ajaxupload/ajaxfileupload.js"></script>
<script type="text/javascript">
	$('#switch-add-file-bt').click( function() {
		var currentpath = $("#file-path").val();
		vsf.get('files/addfile/','add-edit-file-form');
	});
	
	$('#form-add-edit-file').submit(function() {
		vsf.uploadFile("form-add-edit-file", "files", "addeditfile", "list-file",$("#file-path").val());
		return false;
	});
	</script>
	<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
		<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
		<span class="ui-dialog-title">{$form['title']}</span></div>
		<form name="form" method="post" id="form-add-edit-file" enctype="multipart/form-data">
			<input type="hidden" name="oldFileId" id="file-id" value="{$file->getId()}" />
			<input type="hidden" name="filePath" id="file-path" value="{$form['filepath']}" />
			<input type="hidden" name="hiddenTime" value="{$time}" />
		<div class="red">{$form['message']}</div>
		<table cellpadding="0" cellspacing="0" border="0"
			class="ui-dialog-content ui-widget-content">
			<tr>
				<td class="normalcell" width="100">{$vsLang->getWords('file_upload_form_name',"File
				name")}:</td>
				<td class="normalcell" width="300"><input type="text"
					value="{$file->getAliasTitle()}" name="fileAliasTitle" size="45" id="fileAliasTitle" /></td>
			</tr>
			<tr>
				<td class="normalcell" valign="top">{$vsLang->getWords('file_upload_form_desc',"Intro")}:</td>
				<td class="normalcell"><textarea name="fileIntro" cols="34"
					rows="5">{$file->getIntro()}</textarea></td>
			</tr>
			<tr>
				<td class="normalcell">{$vsLang->getWords('file_upload_form_source',"Source")}:</td>
				<td class="ui-dialog-buttonpanel">
				<input type="file"	name="fileUpload" id="fileUpload" /><br />
				<input type="file"	name="fileUpload2" id="fileUpload2" />
				</td>
			</tr>
			<tr>
				<td class="ui-dialog-buttonpanel" align="right" colspan="4">
					<input class="ui-state-default ui-corner-all" type="submit" name="submit" value="{$form ['formSubmit']}" />{$form ['$switchform']}
				</td>
			</tr>
		</table>
		</form>
	</div>
EOF;
		//--endhtml--//
		

		return $BWHTML;
	}
	
	function MainFiles($addeditform = "", $listable = "") {
		$BWHTML = "";
		//--starthtml--//
		

		$BWHTML .= <<<EOF
<div id="file-box" class="right-cell">
	<div id="add-edit-file-form">{$addeditform}</div>
	<br />
	<div id="list-file">{$listable}</div>
</div>
EOF;
		//--endhtml--//
		

		return $BWHTML;
	}
}
?>