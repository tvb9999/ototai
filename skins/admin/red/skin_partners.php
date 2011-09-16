<?php
class skin_partners {

	function moduleObjTab($option) {
		global $vsLang;
		$BWHTML = <<<EOF
			<div id="content_all_vsf">
				<div id="obj-panel">
					{$option['list']}
				</div>
				<div class="clear"></div>
			</div>
EOF;
		return $BWHTML;
	}

	function objListHtml($objItems = array(), $option = array()) {
		global $bw, $vsLang, $vsSettings;
		$totalObj = count ( $objItems );
		
		$BWHTML .= <<<EOF
			<div class="red">{$option['message']}</div>
<form id="obj-list-form">
  <input type="hidden" name="checkedObj" id="checked-obj" value="" />
  <input type="hidden" name="categoryId" value="{$option['categoryId']}" id="categoryId" />
  <input type="hidden" name="issearch" value="{$option['search']}" id="issearch" />
  <div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner"> <span class="ui-icon ui-icon-note"></span> <span class="ui-dialog-title">{$vsLang->getWords('obj_objListHtmlTitle',"{$bw->input[0]} Item List")}</span>
      <p style="align:right; float: right; color: #FFFFFF; cursor: pointer"><span id="search-b">{$vsLang->getWords('obj_search', 'Search')}</span></p>
    </div>
    <div id="search-f" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">
      <span style="padding-left:10px; color: #222222; line-height:20px;">{$vsLang->getWords("global_id_search", "Id")}
      <input type="text" name="searchId" class="numeric" id="searchPId" size="10"   onclick="this.value=null"/>
      </span> <span style="padding-left:10px; color: #222222; line-height:20px;">{$vsLang->getWords("global_title_search", "Title")}
      <input type="text" name="searchTitle" id="searchPTitle" size="65" value="" onblur="if(this.value=='') this.value=''" onclick="this.value=null"/>
      </span> <a title="Click here to search this content!" style="float:right;margin-right: 20px; line-height:20px;" id="search" href="javascript:search();" class="ui-state-default ui-corner-all ui-state-focus">{$vsLang->getWords("global_search", "Search")}</a>
    </div>
    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">
      <li class="ui-state-default ui-corner-top" ><a href="#" onclick="addPartner({$option ['categoryId']})" title="{$vsLang->getWords('add_obj_alt_bt',"Add {$bw->input[0]}")}">{$vsLang->getWords('add_obj_alt_bt',"Add {$bw->input[0]}")}</a></li>
      <li class="ui-state-default ui-corner-top" id="hide-objlist-bt"><a href="#" title="{$vsLang->getWords('hide_obj_alt_bt',"Hide selected {$bw->input[0]}")}">{$vsLang->getWords('hide_obj_bt','Hide')}</a></li>
      <li class="ui-state-default ui-corner-top" id="visible-objlist-bt"><a href="#" title="{$vsLang->getWords('visible_obj_alt_bt',"Visible selected {$bw->input[0]} ")}">{$vsLang->getWords('visible_obj_bt','Visible')}</a></li>
      <li class="ui-state-default ui-corner-top" id="delete-objlist-bt"><a href="#" title="{$vsLang->getWords('delete_obj_alt_bt',"Delete selected {$bw->input[0]}")}">{$vsLang->getWords('delete_obj_bt','Delete')}</a></li>
    </ul>
    <table cellspacing="1" cellpadding="1" id='objListHtmlTable' width="100%">
      <thead>
        <tr>
          <th width="15"><input type="checkbox" onclick="checkAll()" onclicktext="checkAll()" name="all" /></th>
          <th width="60">{$vsLang->getWords('obj_list_status', 'Active')}</th>
          <th>{$vsLang->getWords('obj_list_title', 'Title')}</th>
          <th width="80" style='text-align:center'>{$vsLang->getWords('obj_list_position', 'Position')}</th>
          <th width="50">{$vsLang->getWords('obj_list_index', 'Index')}</th>
        </tr>
      </thead>
      <tbody>
          <foreach="$objItems as $obj">
        
        <tr class="$class $vsf_class">
          <td align="center"><input type="checkbox" onclick="checkObject({$obj->getId()});" name="obj_{$obj->getId()}" value="{$obj->getId()}" class="myCheckbox" /></td>
          <td style='text-align:center'>{$obj->getStatus('image')}</td>
          <td><a href="javascript:vsf.get('{$bw->input[0]}/add-edit-obj-form/{$obj->getCatId()}/{$obj->getId()}/','obj-panel')" title='{$vsLang->getWords('newsItem_EditObjTitle',"Click here to edit this {$bw->input[0]}")}' style='color:#000 !important;' >
            {$obj->getTitle()} </a></td>
          
          <td style='text-align:center'>
          			<if="$obj->getPosition() == 1">
          				{$vsLang->getWords('global_top', 'Top')}
	          		</if>
	          		<if="$obj->getPosition() == 2">
	          			{$vsLang->getWords('global_left', 'Left')}	          			
	          		</if>
	          		<if="$obj->getPosition() == 3">
	          			{$vsLang->getWords('global_right', 'Right')}
	          		</if>
	          		<if="$obj->getPosition() == 4">
	          			{$vsLang->getWords('global_bottom', 'Bottom')}
	          		</if>
	          		<if="$obj->getPosition() == 5">
	          			{$vsLang->getWords('global_center', 'Center')}
	          		</if>
          </td>
        
          <td style='text-align:center'>{$obj->getIndex()}</td>
        </tr>
          </foreach>
        
      </tbody>
      <tfoot>
        <tr>
          <th colspan='5'> <div style='float:right;'>{$option['paging']}</div>
          </th>
        </tr>
      </tfoot>
    </table>
    <table cellspacing="1" cellpadding="1" id="objListInfo" width="100%">
      <tbody>
        <tr align="left"> <span style="padding-left: 10px;line-height:16px;"><img src="{$bw->vars['img_url']}/enable.png" />{$vsLang->getWords('global_status_enable', 'Enable')}</span> <span style="padding-left: 10px;line-height:16px;"><img src="{$bw->vars['img_url']}/disabled.png" /> {$vsLang->getWords('global_status_disabled', 'Disable')}</span></tr>
      </tbody>
    </table>
  </div>
</form>
<div id="albumn"></div>
<script type="text/javascript">
	var category;
	 			function addPartner(catId){
					vsf.get('{$bw->input[0]}/add-edit-obj-form/'+catId,'obj-panel');
				}
				
				$('#hide-objlist-bt').click(function() {
					if($('#checked-obj').val()=='') {
						jAlert(
							"{$vsLang->getWords('hide_obj_confirm_noitem', "You haven't choose any items to hide!")}",
							"{$bw->vars['global_websitename']} Dialog"
						);
						return false;
					}
					checkObject();

					vsf.get('{$bw->input[0]}/hide-checked-obj/'+$('#checked-obj').val()+'/'+category , 'obj-panel');
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
					vsf.get('{$bw->input[0]}/visible-checked-obj/'+$('#checked-obj').val()+'/'+ category, 'obj-panel');
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
					vsf.get('{$bw->input[0]}/home-checked-obj/'+$('#checked-obj').val()+'/'+ category, 'obj-panel');
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
								vsf.get('{$bw->input[0]}/delete-obj/'+$('#checked-obj').val()+'/','none');
								vsf.get('{$bw->input[0]}/display-obj-list/'+category,'obj-panel');
							}
						}
					);
				});

                    $(window).ready(function(){
                            category = $("#obj-category").val()?$("#obj-category").val():$("#categoryId").val()
                            $("input#searchPTitle").autocomplete({
							    source: [{$option['searchStrings']['title']}],delay: 2
							});
							 $("input#searchPId").autocomplete({
							    source: [{$option['searchStrings']['id']}],delay: 2
							});
                            if($("#issearch").val()){
                                $("#search-b").text('{$vsLang->getWords('obj_hide_search', 'Hide search')}');
                                $("#search-f").css('display', 'block');
                            }
                                else{
                                    $("#search-f").css('display', 'none');
                                }
                            $("input.numeric").numeric();
                            $("#searchTitle").focus();
                            $("#search-b").click(function(){
                                if($("#search-b").text()=='{$vsLang->getWords('obj_search', 'Search')}'){
                                    $("#search-b").text('{$vsLang->getWords('obj_hide_search', 'Hide search')}');
                                    $("#search-f").fadeIn('slow',function(){
                                        $("#search-f").css('display', 'block');
                                    });

                                    }
                                    else{
                                    $("#search-b").text('{$vsLang->getWords('obj_search', 'Search')}');
                                    $("#search-f").fadeOut('slow',function(){
                                        $("#search-f").css('display', 'none');
                                    });
                                    }
                            });

                        });
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
				
				
			</script>
EOF;
	}

	function addEditObjForm($objItem, $option = array()) {
		global $vsLang, $bw, $vsSettings, $vsPrint, $vsFile, $vsStd;
		$max_upload_size = min($vsStd->let_to_num(ini_get('post_max_size')), $vsStd->let_to_num(ini_get('upload_max_filesize')));
		if ($objItem->getPosition ())
			$pos = $objItem->getPosition ();
		else
			$pos = 1;
		$BWHTML .= <<<EOF
			<div id="error-message" name="error-message"></div>
			<form id='add-edit-obj-form' name="add-edit-obj-form" method="POST"  enctype='multipart/form-data'>
				<input type="hidden" id="obj-cat-id" name="partnerCatId" value="{$objItem->getCatId()}" />
				<input type="hidden" name="partnerId" value="{$objItem->getId()}" />
				<input type="hidden" name="partnerCatId" value="{$objItem->getCatId()}" />
				<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
					<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
						<span class="ui-dialog-title">{$option['formTitle']}</span>
                        <span id="close" title="{$vsLang->getWords('global_undo','Trở lại')}" class="closePage"></span>
					</div>
					<table class="ui-dialog-content ui-widget-content" cellspacing="1" border="0" style="width:100%">
                        <tr class="smalltitle">
							<td class="label_obj">{$vsLang->getWords('obj_title', 'Title')}:</td>
							<td><input size="43" type="text" name="partnerTitle" value="{$objItem->getTitle()}" id="obj-title"/></td>
							<td align='left' rowspan="6">
                                <if="$objItem->getFileId()">
                                     {$vsFile->arrayFiles[$objItem->getFileId()]->show($vsSettings->getSystemKey($bw->input['key'].'_image_width', 250, $option ['module'], 1,1), $vsSettings->getSystemKey($bw->input['key'].'_image_height', 150, $option ['module'], 1,1))}
                                     <input name="oldImage" value="{$objItem->getFileId()}" type="hidden" />
                                     <p>{$vsLang->getWords('obj_image_dellete', 'Delete Image')}<input type="checkbox" class="checkbox" name="partnerDeleteImage" /></p>
                                <else />
                                     {$objItem->createNoImage()}
                                </if>	                                                    
							</td>
						</tr>
						<if="$vsSettings->getSystemKey($bw->input['key'].'_address_company', 0, $option ['module'], 1,1)">
                        <tr class="smalltitle">
							<td class="label_obj">{$vsLang->getWords('obj_address', 'Address company')}:</td>
							<td><input size="43" type="text" name="partnerAddress" value="{$objItem->getAddress()}" id="obj-address"/></td>
						</tr>
						<else />
						<tr></tr>
						</if>
						<tr class="smalltitle">
							<td class="label_obj">{$vsLang->getWords('obj_website', 'Website Name')}:</td>
							<td><input size="43" type="text" name="partnerWebsite" value="{$objItem->getWebsite()}" id="obj-website"/></td>
						</tr>
						<if="$vsSettings->getSystemKey($bw->input['key'].'_position', 1, $option ['module'], 1,1)">
						<tr class="smalltitle">
						    <td>{$vsLang->getWords('obj_Position', "Position")}</td>
						    <td>
						    <if="$vsSettings->getSystemKey($bw->input['key'].'_position_top', 1, $option ['module'], 1,1)">
                                                        <input type="radio" value="1" name="partnerPosition" class="radio">
						        <label style="padding-right: 10px" for="left">{$vsLang->getWords('global_top', "Top")}</label>
						        </if>
                                <if="$vsSettings->getSystemKey($bw->input['key'].'_position_left', 1, $option ['module'], 1,1)">
                                                        <input type="radio" value="2" name="partnerPosition" class="radio">
						        <label style="padding-right: 10px" for="left">{$vsLang->getWords('global_left', "Left")}</label>
						        </if>
<if="$vsSettings->getSystemKey($bw->input['key'].'_position_right', 1, $option ['module'], 1,1)">
                                                        <input type="radio" value="3" name="partnerPosition" class="radio">
						        <label style="padding-right: 10px" for="left">{$vsLang->getWords('global_right', "Right")}</label>
						        </if>

<if="$vsSettings->getSystemKey($bw->input['key'].'_position_bottom', 1, $option ['module'], 1,1)">
                                                        <input type="radio" value="4" name="partnerPosition" class="radio">
						        <label style="padding-right: 10px" for="left">{$vsLang->getWords('global_bottom', "Bottom")}</label>
</if>
<if="$vsSettings->getSystemKey($bw->input['key'].'_position_center', 1, $option ['module'], 1,1)">
                                                        <input type="radio" value="5" name="partnerPosition" class="radio">
						        <label style="padding-right: 10px" for="left">{$vsLang->getWords('global_center', "center")}</label>
						        </if>
						    </td>
						</tr>
						</if>
                                                        
                                                <tr class="smalltitle">
                                                        <td class="label_obj">{$vsLang->getWords('obj_Index', 'Index')}:</td>
							<td>
								<input size="43" type="text" name="partnerIndex" value="{$objItem->getIndex()}" id="obj-Index"/>
                                                        </td>
						</tr>

                                                <tr class="smalltitle">
							<td class="label_obj">{$vsLang->getWords('obj_Status', 'Active')}:</td>
                                                        <td>
                                 						
                                                        <input type="radio" value="0" name="partnerStatus" id="partnerStatus" class="radio">
						        <label style="padding-right: 10px" for="left">{$vsLang->getWords('global_hidden', "Ẩn")}</label>

                                                        <input type="radio" value="1" name="partnerStatus" id="partnerStatus" class="radio">
						        <label style="padding-right: 10px" for="left">{$vsLang->getWords('global_active', "Hiện")}</label>
                                                        </td>
						</tr>


                                                <if="$vsSettings->getSystemKey($bw->input['key'].'_exptime',1,$option ['module'], 1,1)">
                                                <tr class="smalltitle">
                                                        <td>
								{$vsLang->getWords('obj_begtime', 'Begin Time')}
                                                        </td>
                                                        <td colspan="2">
                                                            <input size="43" name="partnerBeginTime" value="{$objItem->getBeginTime("SHORT")}" id="partnerBeginTime"/>
                                                        </td>
                                                </tr>
                                                <tr class="smalltitle">
                                                        <td>
								{$vsLang->getWords('obj_exptime', 'Expire Time')}
                                                        </td>
                                                       <td colspan="2">
                                                            <input size="43" name="partnerExpTime" value="{$objItem->getExpTime("SHORT")}" id="partnerExpTime"/>
                                                        </td>
                                                </tr>
                                                </if>

                                                <if="$vsSettings->getSystemKey($bw->input['key'].'_price', 1, $option ['module'], 1,1)">
                                                <tr class="smalltitle">
                                                    <td>
                                                        {$vsLang->getWords('obj_price', 'Price')}
                                                    </td>
                                                    <td colspan="2">
                                                        <input  size="43" type="text" name="partnerPrice" value="{$objItem->getPrice()}" id="obj-price"/>
                                                    </td>
                                                </tr>
                                                </if>

                                                <if="$vsSettings->getSystemKey($bw->input['key'].'_image', 1, $option ['module'], 1,1)">
                                                <tr class="smalltitle">
							<td class="label_obj">{$vsLang->getWords('obj_image', "Image")}:</td>
							<td>
								
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
							<td></td>
						</tr>
                                                </if>

						<if="$vsSettings->getSystemKey($bw->input['key'].'_content', 0, $option ['module'], 1,1)">
							<tr class="smalltitle">
                                <td colspan="4" class="label_obj">{$vsLang->getWords('obj_Content', 'Content')}:</td>
                            </tr>
                            <tr class="smalltitle">
								<td colspan="4" align="center">{$objItem->getContent()}</td>
							</tr>
						</if>
                                                        
						<tr class="smalltitle">
							<td class="ui-dialog-buttonpanel" colspan="4" align="center">
								<input class="ui-state-default ui-corner-all" type="submit" name="submit" value="{$option['formSubmit']}" />
							</td>
						</tr>
					</table>
				</div>
			</form>
			<script language="javascript">
				function updateobjListHtml(categoryId){
					return vsf.get('{$bw->input[0]}/display-obj-list/'+$("#obj-cat-id").val()+'/','obj-panel');
				}
				function alertError(message){
					jAlert(
						message,
						'{$bw->vars['global_websitename']} Dialog'
					);
				}
                                
				$(window).ready(function() {
                   $("input.numeric").numeric();
					vsf.jRadio('{$objItem->getStatus()}','partnerStatus');
					vsf.jRadio('{$pos}','partnerPosition');
                     vsf.jSelect('{$objItem->getCatId()}','obj-category');

                    $('#partnerExpTime').datepicker({dateFormat: 'dd/mm/yy' , minDate:0});
                    $('#partnerBeginTime').datepicker({dateFormat: 'dd/mm/yy' , minDate:0});
                                        
                    if(!$("#obj-cat-id").val()) $("#obj-cat-id").val($("#idCategory").val());

                   
				});
 				$("#close").click(function(){
                    	vsf.get('{$bw->input[0]}/display-obj-list/'+$("#obj-cat-id").val()+'/', 'obj-panel');
                                        
                    });
                 $('#obj-category').change(function() {
					var parentId = '';
					$("#obj-category option:selected").each(function () {
						parentId = $(this).val();
					});
					$('#obj-cat-id').val(parentId);
				});
				$('#add-edit-obj-form').submit(function(){
					var flag  = true;
					var error = "";
					var categoryId = "";
					var count=0;
					var title = $("#obj-title").val();
					if(title == 0 || title == ""){
						error += "<li>{$vsLang->getWords('null_title', 'Tiêu đề không được trống !!!')}</li>";
						flag  = false;
						$('#obj-title').addClass('ui-state-error ui-corner-all-inner');
					}
                     if({$vsSettings->getSystemKey($bw->input['key'].'_category_show', 0, $option ['module'], 1,1)}){
                           if(!($("#obj-category option:selected").val()&&$("#obj-category option:selected").val()!=0)){
                                error = "<li>{$vsLang->getWords('not_select_category', 'Please select category!')}</li>";
								flag  = false;
								$('#obj-category').addClass('ui-state-error ui-corner-all-inner');
                     	}
					 }
					if({$vsSettings->getSystemKey($bw->input['key'].'_exptime',1,$option ['module'], 1,1)}){
	                    if(!$('#partnerBeginTime').val() || !$('#partnerExpTime').val()){
							error += "<li>{$vsLang->getWords('null_time', 'Ngày bắt đầu và ngày kết thúc không được để trống')}</li>";
							flag  = false;
						}
						
						var strDate1 = $('#partnerBeginTime').val().split("/");
						var strDate2 = $('#partnerExpTime').val().split("/");
						var d1 = new Date(strDate1[2], strDate1[1], strDate1[0]);
						var d2 = new Date(strDate2[2], strDate2[1], strDate2[0]);
						if(d2<d1){
							error += "<li>{$vsLang->getWords('null_time_as', 'Ngày bắt đầu phải nhỏ hơn ngày kết thúc')}</li>";
							flag  = false;
						}

					}
                     if(!flag){
						error = "<ul class='ul-popu'>" + error + "</ul>";
						vsf.alert(error);
						return false;
					}
					$('#obj-cat-id').val($('#obj-category').val());
					$('#obj-category').removeClass('ui-state-error ui-corner-all-inner');
					vsf.uploadFile("add-edit-obj-form", "{$bw->input[0]}", "add-edit-obj-process", "obj-panel", "{$bw->input[0]}");
					return false;
				});
				
			</script>
EOF;
	}

	function categoryList($categoryGroup = array()) {
		global $vsLang, $bw;
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
				       		<select size="18" style="width: 100%;" id="obj-category">
				        		<option value="0">{$vsLang->getWords('menus_option_root',"Root")}</option>
				        		<if="count($categoryGroup->getChildren())"
				        		<foreach="$categoryGroup->getChildren() as $oMenu">
				        		<option title="{$oMenu->getAlt()}" value="{$oMenu->id}">| - - {$oMenu->title} ({$oMenu->getIndex()} - $oMenu->id)</option>
				        		</foreach>
				        	</select>
				        </td>
				    	<td align="center">
				        	<a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" id="view-obj-bt" title='{$vsLang->getWords('view_list_in_cat',"Click here to edit this {$bw->input[0]}")}'>{$vsLang->getWords('global_view','Xem')}</a>
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
                                        $('#chosen').text($("#obj-category").val());
                                });
			</script>
EOF;
	}

	function displayObjTab($option) {
		global $bw, $vsSettings;
		$BWHTML .= <<<EOF
		<if="$vsSettings->getSystemKey($bw->input['key'].'_category_show', 0, $option ['module'], 1,1)">
	        <div class='left-cell'><div id='category-panel'>{$option['categoryList']}</div></div>
			<input type="hidden" id="idCategory" name="idCategory" />
			<div id="obj-panel" class="right-cell"{$option['objList']}</div>
			<div class="clear"></div>
			<else />
			<input type="hidden" id="idCategory" name="idCategory" />
			<div id="obj-panel" style="width:100%" class="right-cell">{$option['objList']}</div>
			<div class="clear"></div>
        </if>
			
EOF;
		return $BWHTML;
	}

	function managerObjHtml() {
		global $bw, $vsLang, $vsSettings;
		$BWHTML .= <<<EOF
			<div id="page_tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all-top">
				<ul id="tabs_nav" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner">
			    	<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
			        	<a href="{$bw->base_url}{$bw->input[0]}/display-obj-tab/&ajax=1"><span>{$vsLang->getWords('tab_obj_objes',"{$bw->input[0]}")}</span></a>
			        </li>
					 <if="$vsSettings->getSystemKey($bw->input[0].'_category_tab', 0, $bw->input[0],1,1)">
						<li class="ui-state-default ui-corner-top">
				        	<a href="{$bw->base_url}menus/display-category-tab/{$bw->input[0]}/&ajax=1"><span>{$vsLang->getWords('global_categories','Categories')}</span></a>
                                                </li>
                                         </if>
                                        
			        </if>
			        <if="$vsSettings->getSystemKey($bw->input[0].'_setting_tab',1,$bw->input[0],1,1)">
				        <li class="ui-state-default ui-corner-top">
				        	<a href="{$bw->base_url}settings/moduleObjTab/{$bw->input[0]}/&ajax=1">
								<span>{$vsLang->getWords("tab_{$bw->input[0]}_SS",'System Settings')}</span>
							</a>
			        	</li>
		        	</if>	
				</ul>
			</div>
EOF;
		return $BWHTML;
	}
}
?>