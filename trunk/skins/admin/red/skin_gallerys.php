<?php
/**
 * @author Sanh Nguyen
 * @version 1.0 RC
 */
class skin_gallerys {
	
	function loadDefault() {
		global $bw, $vsLang,$vsSettings;
		
		$BWHTML .= <<<EOF
			<div id="page_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all-top">
				<ul id="tabs_nav">
					<li>
						<a href="{$bw->base_url}gallerys/display-gallery-tab/&ajax=1">
							<span>{$vsLang->getWords('tab_gallery','Gallery')}</span>
						</a>
					</li>
					<if="$vsSettings->getSystemKey($bw->input[0].'_category_tab',1, $bw->input[0], 1, 1)">
				    <li>
					    <a href="{$bw->base_url}menus/display-category-tab/gallerys/&ajax=1">
					    	<span>{$vsLang->getWords('tab_gallery_categories','Categories')}</span>
					    </a>
				    </li>
			    	</if>
			    	<if="$vsSettings->getSystemKey($bw->input[0].'_setting_tab', 1, $bw->input[0], 1, 1)">
			        <li class="ui-state-default ui-corner-top">
			        	<a href="{$bw->base_url}settings/moduleObjTab/{$bw->input[0]}/&ajax=1">
							<span>{$vsLang->getWords("tab_{$bw->input[0]}",'Settings')}</span>
						</a>
		        	</li>
	        		</if>	
			    </ul>
			</div>
EOF;
		return $BWHTML;
	}
	
	function catagoryList($category) {
		global $vsLang, $bw;
		
		$BWHTML .= <<<EOF
			<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
				<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
					<span class="ui-icon ui-icon-triangle-1-e"></span>
					<span class="ui-dialog-title">{$vsLang->getWords('category_table_title_header','Categories')}</span>
				</div>
				<table width="100%" cellpadding="0" cellspacing="1">
					<tr>
				    	<th id="discover-category-message" colspan="2">{$vsLang->getWords('category_chosen',"Selected categories")}: {$vsLang->getWords('category_not_selected',"None")}</th>
				    </tr>
				    <tr>
				        <td width="220">
				        {$category}
				        </td>
				    	<td align="center">
				    		<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" id="view-gallery-bt" title='{$vsLang->getWords('category_view',"Click here to view this category")}'>
				        	{$vsLang->getWords('global_view','View')}
					        </a>
					    	<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" id="add-gallery-bt" title='{$vsLang->getWords('category_add',"Click here to add news")}'>
					        	{$vsLang->getWords('global_add','Add')}
					        </a>
				        </td>
					</tr>
				</table>
			</div>
			<script type="text/javascript">
				$('#view-gallery-bt').click(function(){
					var categoryId = '';
					$("#gallery-category option:selected").each(function () {
						categoryId=$(this).val();
					});
					if(categoryId==0){
						jAlert(
		        			'{$vsLang->getWords('gallery_category_empty','Vui lòng chọn danh mục!')}',
		        			'{$bw->vars['global_websitename']} Dialog'
	        			);
						$('#gallery-category').addClass('ui-state-error ui-corner-all-inner');
						return false;
					}
					$('#gallery-category').removeClass('ui-state-error ui-corner-all-inner');
					vsf.get('gallerys/display-album-list/'+categoryId, 'gallery-panel');
				});
				$('#add-gallery-bt').click(function(){
					var categoryId = '';
					$("#gallery-category option:selected").each(function () {
						categoryId=$(this).val();
					});
					if(categoryId==0){
						jAlert(
		        			'{$vsLang->getWords('gallery_category_empty','Vui lòng chọn danh mục!')}',
		        			'{$bw->vars['global_websitename']} Dialog'
	        			);
						$('#gallery-category').addClass('ui-state-error ui-corner-all-inner');
						return false;
					}
					$('#gallery-category').removeClass('ui-state-error ui-corner-all-inner');
					vsf.get('gallerys/add-album-form/'+categoryId, 'gallery-panel');
				});
				var parentId = '';
				var cateId	=	'';
				$('#gallery-category').change(function() {
					var currentId = '';
					$("#gallery-category option:selected").each(function () {
						currentId += $(this).val() + ',';
						cateId = $(this).val();
					});
										
					currentId = currentId.substr(0, currentId.length-1);
					$("#gallery-category-message").html('{$vsLang->getWords('category_chosen',"Selected categories")}:'+currentId);
					$('#cate-Id').val(cateId);
				});
			</script>
EOF;
	}

	function displayMain($option){
		global $vsSettings,$bw;
		$class = " ";
		if($vsSettings->getSystemKey($bw->input[0].'_category_tab',1, $bw->input[0], 1, 1))
			$class = "right-cell";
		$BWHTML .= <<<EOF
			<if="$vsSettings->getSystemKey($bw->input[0].'_category_tab',1, $bw->input[0], 1, 1)">
			<div class='left-cell'><div id='category-panel'>{$option['categoryList']}</div></div>
				
			</if>
			<div id="gallery-panel" class="$class">{$option['galleryAlbum']}</div>
EOF;
		return $BWHTML;
	}
	
	function addEditAlbumFrom($albumItem,$option){
		global $vsLang,$bw,$vsSettings;
		$BWHTML .= <<<EOF
			<div id="error-message" name="error-message"></div>
			<form id='addEditForm' name="addEditForm" method="POST" enctype='multipart/form-data'>
				<input type="hidden" name="galleryCatId" value="{$albumItem->getCatId()}" id="cate-Id" />
				<input type="hidden" name="galleryId" value="{$albumItem->getId()}" id="galleryId" />
				<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
					<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
						<span class="ui-dialog-title">{$option['formTitle']}</span>
					</div>
					
					<table class="ui-dialog-content ui-widget-content">
						<if="$vsSettings->getSystemKey($bw->input[0].'_title',1, $bw->input[0], 1, 1)">
							<tr>
								<td class="label_news" style="width:78px;">{$vsLang->getWords('gallery_title', 'Tên album')}:</td>
								<td><input size="35" type="text" name="galleryAlbum" value="{$albumItem->getTitle()}" id="galleryAlbum"/></td>
							</tr>
						</if>
						<if="$vsSettings->getSystemKey($bw->input[0].'_Index',1, $bw->input[0], 1, 1)">
							<tr>
								<td class="label_news">{$vsLang->getWords('gallery_Index', 'Index')}:</td>
								<td><input size="15" type="text" name="galleryIndex" value="{$albumItem->getIndex()}" class="numeric"/></td>
							</tr>
							</if>
							
						<if="$vsSettings->getSystemKey($bw->input[0].'_password',1, $bw->input[0], 1, 1)">
							<tr>
								<td class="label_news">{$vsLang->getWords('gallery_password',"Mật khẩu")}</td>
								<td><input size="15" type="password" name="galleryPassWord" value="" /></td>
							</tr>
						</if>
						<if="$vsSettings->getSystemKey($bw->input[0].'_code',1, $bw->input[0], 1, 1)">
							<tr>
								<td class="label_news">{$vsLang->getWords('gallery_code',"Mã trang")}</td>
								<td><input size="15" type="text" name="galleryCode" value="{$albumItem->getCode()}" /></td>
							</tr>
						</if>
						<if="$vsSettings->getSystemKey($bw->input[0].'_status',1, $bw->input[0], 1, 1)">
							<tr>
								<td class="label_news">{$vsLang->getWords('gallery_status',"Status")}</td>
								<td colspan="3">
									<input type="radio" name="galleryStatus" value="0" id="galleryStatus_last" class="checkbox">
					            	<label for = "galleryStatus_last">{$vsLang->getWords('gallery_hides','ẩn')}</label>&nbsp;&nbsp;
									<input type="radio" name="galleryStatus" value="1" id="galleryStatus_fist" class="checkbox">
									<label for = "galleryStatus_fist">{$vsLang->getWords('gallery_active','Hiển thị')}</label>&nbsp;&nbsp;
								</td>
							</tr>
						</if>
						<if="$vsSettings->getSystemKey($bw->input[0].'_Intro',1, $bw->input[0], 1, 1)">
							<tr >
								<td class="label_area">{$vsLang->getWords('area_Intro', 'Intro')}:</td>
								<td  colspan='2'>{$albumItem->getIntro()}</td>
							</tr>
						</if>
						<if="$vsSettings->getSystemKey($bw->input[0].'_Images',1, $bw->input[0], 1, 1)">
							<tr >
								<td class="label_area" style="vertical-align:top">{$vsLang->getWords('gallery_image', 'Hình ảnh ')}:</td>
								<td style="vertical-align:top"><input  type="file" name="fileType" id="fileType" /></td>
								<div style="float:right; border: 1px solid;" id="td-obj-image">{$albumItem->createImageCache($albumItem->getImage(),125,125)}</div>
							</tr>
						</if>
						<tr>
							<td class="ui-dialog-buttonpanel" colspan="4" align="center">
								<input class="ui-state-default ui-corner-all" type="submit" name="submit" value="{$option['formSubmit']}" />
								<input class="ui-state-default ui-corner-all" type="button" onclick="vsf.get('gallerys/display-album-list/{$albumItem->getCatId()}','gallery-panel')" value="Trở về" />
							</td>
						</tr>
					</table>
				</div>
			</form>
			<script type="text/javascript">
				var count=0;
				
				function selectOption(select_id, option_val) {
				    $('#'+select_id+' option:selected').removeAttr('selected');
				    $('#'+select_id+' option[value='+option_val+']').attr('selected','selected');       
				}
				
				var the_formed = window.document.addEditForm;
				$(document).ready(function(){
					$("input.numeric").numeric();
						vsf.radio('{$albumItem->getStatus()}',the_formed.galleryStatus);
					selectOption('gallery-category','{$albumItem->getCatId()}');
					
					$('#gallery-category option').each(function(){
						count++;
					});
				});
				
				$('#addEditForm').submit(function() {
					if(($('#cate-Id').val()=="" || $('#cate-Id').val()==0) &&count>1){
						jAlert('{$vsLang->getWords('not_select_category', 'Vui lòng chọn category!!!')}','{$bw->vars['global_websitename']} Dialog');
						$('#gallery-category').addClass('ui-state-error ui-corner-all-inner');
						return false;
					}
					
					if($('#galleryAlbum').val()==""){
						jAlert('{$vsLang->getWords('gallery_title_album_error','Vui lòng cho biết tên album')}','{$bw->vars['global_websitename']} Dialog');
						$('#galleryAlbum').focus(); 
						$('#galleryAlbum').addClass('ui-state-error ui-corner-all-inner');
						return false;
					}
					$('#galleryAlbum').removeClass('ui-state-error ui-corner-all-inner');
					vsf.uploadFile("addEditForm", "{$bw->input[0]}", "add-edit-album", "gallery-panel","gallery/deputy");
					return false;
				});
			</script>
EOF;
		return $BWHTML;
	}
	
	function displayGalleryAlbumList($albumList,$option){
		global $bw,$vsLang,$vsSettings;
		$count = 0;
		$message = $vsLang->getWords('gallery_deleteConfirm_NoItem', "You haven't choose any items!");
		$BWHTML .= <<<EOF
			<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
			    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
			        <span class="ui-icon ui-icon-triangle-1-e"></span>
			        <span class="ui-dialog-title">{$vsLang->getWords('gallery_listAlbum','Danh sách các album')}</span>
			    </div>
			    <if="$vsSettings->getSystemKey($bw->input[0].'_header',1, $bw->input[0], 1, 1)">
			    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">
			    	<li class="ui-state-default ui-corner-top">
			    		<a onclick="addPage()" title="{$vsLang->getWords('gallery_addAlum','Add')}" id="addAlum" href="#">
							{$vsLang->getWords('gallery_addAlum','Add')}
						</a>
		    		</li>
		    		<li class="ui-state-default ui-corner-top">
			        	<a id="deleteAlbum" title="{$vsLang->getWords('gallery_deleteAlbum','Delete')}" href="#">
							{$vsLang->getWords('gallery_deleteAlbum','Delete')}
						</a>
					</li>
			        <li class="ui-state-default ui-corner-top">
			        	<a id="hideAlbum" title="{$vsLang->getWords('gallery_hideAlbum','Hide')}" href="#">
							{$vsLang->getWords('gallery_hideAlbum','Hide')}
						</a>
					</li>
			        <li class="ui-state-default ui-corner-top">
			        	<a id="displayAlbum" title="{$vsLang->getWords('gallery_unhideAlbum','Display')}" href="#">
							{$vsLang->getWords('gallery_unhideAlbum','Display')}
						</a>
					</li>
			    </ul>
				</if> 
				<table cellspacing="1" cellpadding="1" id='productListTable' width="100%">
					<thead>
					    <tr>
					        <th style='text-align:center;' width="15"><input type="checkbox" onclick="checkAll()" onclicktext="checkAll()" name="all" /></th>
					        <th style='text-align:center;' width="20">{$vsLang->getWords('gallery_labelStatus', 'Hiện')}</th>
					        <th style='text-align:center;' width="200">{$vsLang->getWords('gallery_labelTitle', 'Tên Album')}</td>
					        <th style='text-align:center;' width="">{$vsLang->getWords('gallery_labelIntro', 'Giới Thiệu')}</th>
					        <th style='text-align:center;' width="110">{$vsLang->getWords('gallery_option', 'Tùy chọn')}</th>
					    </tr>
					</thead>
					<tbody>
						<if="count($albumList)">
							<foreach="$albumList as $Album">
								<php> 
									$classType = ($count%2)+1;
									$count++;
			           			</php> 
								<tr class="row{$classType}">
									<td align="center" width="20">
										<input type="checkbox" onclicktext="checkObject({$Album->getId()});" onclick="checkObject({$Album->getId()});" name="obj_{$Album->getId()}" value="{$Album->getId()}" class="myCheckbox" />
									</td>
									<td style='text-align:center' width="20">{$Album->getStatus('image')}</td>
									
									<td>
									<a href="javascript:vsf.get('gallerys/edit-album-form/{$Album->getId()}/','gallery-panel')" title='{$vsLang->getWords('gallery_edit_album','Click here to edit this album')}' class="editObj">
											{$Album->getTitle()}
										</a>
									</td>
									
									<td>{$Album->getIntro(200)}</td>
									
									<td class="ui-dialog-buttonpanel" colspan="4" align="center">
										<a onclick="vsf.popupGet('gallerys/display-file/{$Album->getId()}','auto{$Album->getId()}')" class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" >
											{$vsLang->getWords('images','Images')}
										</a>
									</td>
								</tr>
							</foreach>
						</if>
					</tbody>
					
					<tfoot>
						<tr>
							<th colspan='7'>
								<div style='float:right;'>{$option['paging']}</div>
							</th>
						</tr>
					</tfoot>
				</table>
			</div>
			<script type="text/javascript">
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
					$('#checked-obj').val(checkedString);
				}
				function addPage(){
					vsf.get('gallerys/add-album-form','gallery-panel');
				}
				$('#deleteAlbum').click(function(){
					jConfirm(
						'{$vsLang->getWords("gallery_deleteConfirm","Are you sure to delete these Album information?")}', 
						'{$bw->vars['global_websitename']} Dialog', 
						function(r){
							if(r){
								var flag=true; var jsonStr = "";
						
								$("input[type=checkbox]").each(function(){
									if($(this).hasClass('myCheckbox')){
										flag=false;
										if(this.checked) jsonStr += $(this).val()+',';
									}
								});
								if(flag){
									jAlert(
										"{$message}",
										"{$bw->vars['global_websitename']} Dialog"
									);
									return false;
								}
								jsonStr = jsonStr.substr(0,jsonStr.lastIndexOf(','));
								
								vsf.get('gallerys/delete-album/{$option['cateId']}/'+jsonStr+'/','gallery-panel');
							}
						}
					);
				});
				$('#hideAlbum').click(function(){
						var flag=true; var jsonStr = "";
						$("input[type=checkbox]").each(function(){
								if($(this).hasClass('myCheckbox')){
									flag=false;
									if(this.checked) jsonStr += $(this).val()+',';
								}
							});
						if(flag){
							jAlert(
								"{$message}",
								"{$bw->vars['global_websitename']} Dialog"
							);
							return false;
						}
						jsonStr = jsonStr.substr(0,jsonStr.lastIndexOf(','));
						
						vsf.get('gallerys/update-album-status/{$option['cateId']}/'+jsonStr+'/0/','gallery-panel');
				});
				
				$('#displayAlbum').click(function(){
						var flag=true; var jsonStr = "";
				
						$("input[type=checkbox]").each(function(){
								if($(this).hasClass('myCheckbox')){
									flag=false;
									if(this.checked) jsonStr += $(this).val()+',';
								}
							});
						if(flag){
							jAlert(
								"{$message}",
								"{$bw->vars['global_websitename']} Dialog"
							);
							return false;
						}
						jsonStr = jsonStr.substr(0,jsonStr.lastIndexOf(','));
						vsf.get('gallerys/update-album-status/{$option['cateId']}/'+jsonStr+'/1/','gallery-panel');
				});
				
			</script>
EOF;
		return $BWHTML;
	}
	
	function displayFile($option){
		return $BWHTML .= <<<EOF
			<div id="dialog" title="Dialog Title">
				<div class='left-cell'><div id='file-form' >{$option['file-form']}</div></div>
				<div id="file-panel" >{$option['file-list']}</div>
			</div>	
EOF;
	}
	
	function addEditFileForm($form = array(), $file,$album) {
		global $bw, $vsLang, $vsSettings,$vsStd;
		$max_upload_size = min($vsStd->let_to_num(ini_get('post_max_size')), $vsStd->let_to_num(ini_get('upload_max_filesize')));
		if(!$album->getCode())
			$album->setCode('common');
			
		$albumName = $album->getCode()."/Album-{$album->getId()}";
		if(!$file->getId())
			$file->setStatus(1);

		$BWHTML .= <<<EOF
			<div class="ui-widget ui-widget-content ui-corner-all">
				<form name="form" method="post" id="form-add-edit-file" enctype="multipart/form-data">
					<input type="hidden" name="oldFileId" id="file-id" value="{$file->getId()}" />
					<input type="hidden" name="albumId" id="albumId" value="{$form['albumId']}" />
					<input type="hidden" name="albumPath" id="albumPath" value="gallery/{$albumName}" />
				<div class="red">{$form['message']}</div>
				<table cellpadding="0" cellspacing="0" border="0" 	class="ui-dialog-content ui-widget-content" width="100%">
					<if="$vsSettings->getSystemKey($album->getCode().'_file_title',1,$album->getCode(),  1, 1)">
					<tr>
						<td class="normalcell" width="100">{$vsLang->getWords('file_upload_form_name',"File	name")}:</td>
						<td class="normalcell" width="300"><input type="text" value="{$file->getTitle()}" name="fileTitle" size="45" id="fileTitle" /></td>
					</tr>
					</if>
					<if="$vsSettings->getSystemKey($album->getCode().'_file_url',0,$album->getCode(),  1, 1)">
						<tr>
							<td class="normalcell" width="100">{$vsLang->getWords('file_url',"File Link")} </td>
							<td class="normalcell" width="300"><input type="text" value="{$file->getUrl()}" name="fileUrl" size="45" id="fileUrl" /></td>
						</tr>
					
					</if>
					<tr>
						<td class="normalcell" width="100"></td>
						<td class="normalcell" width="300">
							<if="$vsSettings->getSystemKey($album->getCode().'_file_video',0,$album->getCode(),  0, 1) and  $vsSettings->getSystemKey($album->getCode().'_file_document',0,$album->getCode(),  0, 1) and $vsSettings->getSystemKey($album->getCode().'_file_image',1,$album->getCode(),  0, 1)">
								<input type="radio"  class="checkbox" name="option"  onclick="show('video');hiddens('image');hiddens('document')" /> Video
								<input type="radio"  class="checkbox" name="option"  onclick="hiddens('image');hiddens('video');show('document')" /> {$vsLang->getWords('file_document_title',"Tài liệu")}
								<input type="radio" id="img"  class="checkbox" name="option" checked='checked' onclick="show('image');hiddens('video');hiddens('document')"/> {$vsLang->getWords('file_image_title',"Hình ảnh")}
								<style>
									#video_source_rs,#video_link_rs,#document_source_rs,#document_link_rs{
										display:none;
									}
								</style>
							<else />
								<if="$vsSettings->getSystemKey($album->getCode().'_file_video',0,$album->getCode(),  0, 1)">
									<style>
										#video_source_rs,#video_link_rs{
											display: inline-block;
										}
										#image_source_rs,#image_link_rs,#document_source_rs,#document_link_rs{
											display:none;
										}
									</style>
									<script>
										$("#video").css('display','table-row');
										$("#image").css('display','none');
										$("#document").css('display','none');
									</script>
								</if>
								<if="$vsSettings->getSystemKey($album->getCode().'_file_document',0,$album->getCode(),  0, 1)">
									<style>
										#document_source_rs,#document_link_rs{
											display: inline-block;
										}
										#video_source_rs,#video_link_rs,#image_source_rs,#image_link_rs{
											display:none;
										}
									</style>
									<script>
										$("#video").css('display','none');
										$("#image").css('display','none');
										$("#document").css('display','table-row');
									</script>
								</if>
								<if="$vsSettings->getSystemKey($album->getCode().'_file_images',1,$album->getCode(),  0, 1)">
									<style>
										#video_source_rs,#video_link_rs,#document_source_rs,#document_link_rs{
											display: none;
										}
										#image_source_rs,#image_link_rs{
											display: inline-block;
										}
									</style>
								</if>
							</if>
							</td>
					</tr>
					<tr>
						<td class="normalcell" width="100"></td>
						<td class="normalcell" width="300">
							<input type="radio"  class="checkbox" name="video" value="youtube"  id="video_link" onclick="$('#source2').css('display','none');$('#youtube').css('display','table-row');$('#images2').css('display','none');" title="Youtube"/>
							<input type="radio"  class="checkbox" name="video" id="video_source" checked='checked' onclick="$('#source2').css('display','table-row');$('#youtube').css('display','none');$('#images2').css('display','table-row');" title="{$vsLang->getWords('file_upload_title',"Dữ liệu từ máy tính")}" />
							<input type="radio"  class="checkbox" name="image" id="image_source" checked='checked' onclick="$('#source3').css('display','table-row');$('#link').css('display','none');" title="{$vsLang->getWords('file_upload_title',"Dữ liệu từ máy tính")}" />
							<input type="radio"  class="checkbox" name="image" id="image_link" onclick="$('#link').css('display','table-row');$('#source3').css('display','none');" title="{$vsLang->getWords('file_link_title',"Link từ website khác")}" />
							<input type="radio"  class="checkbox" name="document" id="document_source" checked='checked' style="display:none" onclick="$('#source4').css('display','table-row');$('#link1').css('display','none');" title="{$vsLang->getWords('file_upload_title',"Dữ liệu từ máy tính")}" />
							<input type="radio"  class="checkbox" name="document" id="document_link" onclick="$('#link1').css('display','table-row');$('#source4').css('display','none');" title="{$vsLang->getWords('file_link_title',"Link từ website khác")}" />
						</td>
					</tr>
					
					<tr id="video" style="display:none"> 
						<td colspan="3">
						<table>
							<tr id="source2">
								<td class="normalcell" style="width: 100px;">{$vsLang->getWords('file_upload_form_source',"Source")}:</td>
								<td class="ui-dialog-buttonpanel">
									<div style="width: 180px; height: 18px; border: solid 1px #7FAAFF; background-color: #C5D9FF; padding: 2px;">
										<span id="spanButtonPlaceholder"></span>
									</div>
									<div id="fsUploadProgress"></div>
									<script>
										$(window).ready(function() {
											var swfu;
											swfu = new SWFUpload(vsf.uploadSWF($max_upload_size,['form-add-edit-file','spanButtonPlaceholder','fsUploadProgress','1'],'gallerys','gallery/{$albumName}',["{$vsSettings->getSystemKey('global_file_video_extend',"*.mp4;*.flv;*.3gp;*.m4a;*.mp3","global",  0, 1)}","Video"]));
											
										});
									</script>
								</td>
							 <tr id="images2">
								<td class="normalcell" style="width: 100px;">{$vsLang->getWords('file_imge',"Hình ảnh đại diện")}:</td>
								<td class="normalcell" width="300">
									<div style="width: 180px; height: 18px; border: solid 1px #7FAAFF; background-color: #C5D9FF; padding: 2px;">
										<span id="spanButtonPlaceholder1"></span>
									</div>
									<div id="fsUploadProgress1"></div>
									<script>
										$(window).ready(function() {
											var swfu1;
											swfu1 = new SWFUpload(vsf.uploadSWF($max_upload_size,['form-add-edit-file','spanButtonPlaceholder1','fsUploadProgress1','2'],'gallerys','tmp',["{$vsSettings->getSystemKey('global_file_image_extend',"*.jpg;*.png;*.gif","global",  0, 1)}","Images"]));
											
										});
									</script>
								</td>
							</tr>
							</tr>
							<tr id="youtube" style="display:none">
								<td class="normalcell" style="width: 100px;">{$vsLang->getWords('file_youtube',"Url youtube")}:</td>
								<td class="normalcell" width="300"><input type="text" name="fileYoutube" id="fileYoutube" size="45" value="{$file->getYoutube()}"/></td>
							</tr>
						</table>
						</td>
					</tr>
					<tr id="image" > 
						<td colspan="3">
							<table>
								<tr id="source3" >
									<td class="normalcell" style="width: 100px;">{$vsLang->getWords('file_upload_form_source',"Source")}:</td>
									<td class="ui-dialog-buttonpanel">
										<div style="width: 180px; height: 18px; border: solid 1px #7FAAFF; background-color: #C5D9FF; padding: 2px;">
										<span id="spanButtonPlaceholder2"></span>
										</div>
										<div id="fsUploadProgress2"></div>
										<script>
											$(window).ready(function() {
												var swfu2;
												swfu2 = new SWFUpload(vsf.uploadSWF($max_upload_size,['form-add-edit-file','spanButtonPlaceholder2','fsUploadProgress2',1],'gallerys','gallery/{$albumName}',["{$vsSettings->getSystemKey('global_file_image_extend',"*.jpg;*.png;*.gif","global",  0, 1)}","Images"]));
											});
										</script>
									</td>
								</tr>
								
								<tr id="link" style="display:none">
									<td class="normalcell" width="100">{$vsLang->getWords('file_url_link',"Link từ website khác")} </td>
									<td class="normalcell" width="300"><input type="text"  name="fileLink1" size="45" id="fileLink" /></td>
								</tr>
							</table>	
						</td>
					</tr>
					<tr id="document" style="display:none"> 
						<td colspan="3">
							<table>
								<tr id="source4" >
									<td class="normalcell" style="width: 100px;">{$vsLang->getWords('file_upload_form_source',"Source")}:</td>
									<td class="ui-dialog-buttonpanel">
										<div style="width: 180px; height: 18px; border: solid 1px #7FAAFF; background-color: #C5D9FF; padding: 2px;">
										<span id="spanButtonPlaceholder3"></span>
										</div>
										<div id="fsUploadProgress3"></div>
										<script>
											$(window).ready(function() {
												var swfu2;
												swfu2 = new SWFUpload(vsf.uploadSWF($max_upload_size,['form-add-edit-file','spanButtonPlaceholder3','fsUploadProgress3',1],'gallerys','gallery/{$albumName}',["{$vsSettings->getSystemKey('global_file_document_extend',"*.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.csv;*.xps;*.pdf;*.zip;*.rar","global",  0, 1)}","Document"]));
											});
										</script>
									</td>
								</tr>
								
								<tr id="link1" style="display:none">
									<td class="normalcell" width="100">{$vsLang->getWords('file_url_link',"Link từ website khác")} </td>
									<td class="normalcell" width="300"><input type="text"  name="fileLink2" size="45" id="fileLink" /></td>
								</tr>
							</table>	
						</td>
					</tr>
					<tr>
						<td class="normalcell" width="100"></td>
						<td class="normalcell" width="300">
							<if="$vsSettings->getSystemKey($album->getCode().'_file_document_extend',0,$album->getCode(),  0, 1)">
							<strong style="color:red">Tài liệu:</strong><span> doc, docx, xls, xlsx, pdf</span> 
							</if>
						</td>
					</tr>
					<if="$vsSettings->getSystemKey($album->getCode().'_file_intro',0,$album->getCode(),  1, 1)">
					<tr>
						<td class="normalcell" width="100">{$vsLang->getWords('file_url',"File	Intro")}:</td>
						<td class="normalcell" width="300"><textarea name="fileIntro" style="width:100%;height:100px">{$file->getIntro()}</textarea></td>
					</tr>
					</if>
					<if="$vsSettings->getSystemKey($album->getCode().'_file_index',0,$album->getCode(),  1, 1)">
					<tr>
						<td class="normalcell" width="100">{$vsLang->getWords('file_index',"File Index")}:</td>
						<td class="normalcell" width="300"><input type="text" class="numeric" value="{$file->getIndex()}" name="fileIndex" /></td>
					</tr>
					</if>
					<if="$vsSettings->getSystemKey($album->getCode().'_file_status',0,$album->getCode(),  1, 1)">
					<tr>
						<td class="normalcell" width="100">{$vsLang->getWords('file_status',"File	Status")}:</td>
						<td class="normalcell" width="300"><input type="checkbox" value="1" name="fileStatus" /></td>
					</tr>
					</if>
					<tr>
						<td class="ui-dialog-buttonpanel" align="right" colspan="2">
							<input class="ui-state-default ui-corner-all" type="submit" name="submit" value="{$form ['formSubmit']}" /> {$form ['switchform']}
						</td>
					</tr>
				</table>
				</form>
			</div>
			<script type="text/javascript">
				$('#switch-add-file-bt').click( function() {
					vsf.get('gallerys/add-form-file/{$form['albumId']}','file-form');
				});
				
				$('#form-add-edit-file').submit(function() {
					vsf.submitForm($('#form-add-edit-file'), 'gallerys/add-edit-gallery-file',"file-panel");
					return false;
				});
				$(window).ready(function() {
					$("input.numeric").numeric();
					vsf.jCheckbox('{$file->getStatus()}','fileStatus');
					vsf.jRadio('{$file->getType()}','source');
				});
				function show(id){
					$('#'+id+'_source_rs').css('display','inline-block');
					$('#'+id+'_link_rs').css('display','inline-block');
					$('#'+id).css('display','table-row');
				}
				function hiddens(id){
					$('#'+id+'_source_rs').css('display','none');
					$('#'+id+'_link_rs').css('display','none');
					$('#'+id).css('display','none');
				}					
			</script>
EOF;
		return $BWHTML;
	}
	
	function displayGalleryFileList($file,$albumId){
		global $vsLang, $bw;
		$BWHTML .= <<<EOF
			<div class="ui-widget ui-widget-content ui-corner-all" style="background:url('images/bg_dialog_cd.jpg') repeat scroll 0 0; border:1px solid #A8211D !important">
			<if="count($file)">
				<foreach="$file as $value">
				<div class="ui-dialog-content ui-widget-content" style="float: left; width: 100px; margin: 5px;" title="{$value->getTitle()}">
					<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript: displayEditFile({$value->getId()},$albumId)" title='{$vsLang->getWords('newsItem_EditObjTitle',"Click here to edit this {$bw->input[0]}")}'>{$vsLang->getWords('global_edit','Sửa')}</a>
					<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript: removeFile({$value->getId()},$albumId,'{$value->getTitle()}')" title='{$vsLang->getWords('newsItem_EditObjTitle',"Click here to delete this {$bw->input[0]}")}'>{$vsLang->getWords('global_del','Xóa')}</a>
					{$value->viewFile()}
				</div>
				</foreach>
			<else />
				<div class="error">{$vsLang->getWords('gallery_file_empty',"Không có hình ảnh nào cả.")}</div>	
			</if>
			<div class="clear"></div>
        </div>
		<script>
			function displayEditFile(fileId, cateId){
				vsf.get('gallerys/edit-form-file/'+cateId + '/'+fileId+'/','file-form');	
			}
	
			function removeFile(fileId, cateId, fileName){
				vsf.get("gallerys/delete-file/" + fileId +'/'+ cateId +'/', 'file-panel');
			}		
		</script>
EOF;
		return $BWHTML;
	}	
	
}
?>