<?php
class skin_orders{

//===========================================================================
// <rsf:mainLayout:desc::trigger:>
//===========================================================================
function mainLayout($current="") {
//--starthtml--//
$BWHTML .= <<<EOF
		<div id='page' class="ui-tabs ui-tabs-panel ui-widget-content ui-corner-bottom">
<div class='right-cell' style='width:100%' id="mainContainer">
$current
</div>
<div class="clear"></div>
<div id="orderView">
</div>
<div class="clear"></div>
</div>
EOF;
//--endhtml--//
return $BWHTML;
}
//===========================================================================
// <rsf:getListObj:desc::trigger:>
//===========================================================================
function getListObj($option="") {global $vsLang,$bw,$vsSettings;
$bw->input[2] = $bw->input[2]?$bw->input[2]:1;

//--starthtml--//
$BWHTML .= <<<EOF
		<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
        <span class="ui-icon ui-icon-triangle-1-e"></span>
        <span class="ui-dialog-title">{$vsLang->getWords('order_list','Danh sách các hóa đơn')}</span>
    </div>
    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">
    
EOF;
if( $vsSettings->getSystemKey(orders.'_deletePage_button',1, 'orders')) {
$BWHTML .= <<<EOF

    <li class="ui-state-default ui-corner-top">
        <a id="deletePage" title="{$vsLang->getWords('pages_deletePage','Delete')}" onclick="deletePage();" href="#">
        {$vsLang->getWords('pages_deletePage','Delete')}
</a>
</li>

EOF;
}

$BWHTML .= <<<EOF

    </ul>
<table cellspacing="1" cellpadding="1" id='productListTable' width="100%">
<thead>
    <tr>
       <th width="15"><input type="checkbox" onclick="checkAll()" onclicktext="checkAll()" name="all" id="checked-obj" /></th>
        <th>{$vsLang->getWords('order_name','Họ và tên')}</th>
                <th>{$vsLang->getWords('order_address','Địa chỉ')}</th>
                <th>{$vsLang->getWords('order_phone','Địa thoại')}</th>
                <th>{$vsLang->getWords('order_email','Email')}</th>
                <th>{$vsLang->getWords('order_time','Ngày đặt hàng')}</th>
                <th width="110">{$vsLang->getWords('order_option','Tùy chọn')}</th>
    </tr>
</thead>
<tbody>

EOF;
if(count($option['pageList'])) {
$BWHTML .= <<<EOF

{$this->__foreach_loop__id_4ddf6f4d941cc($option)}

EOF;
}

$BWHTML .= <<<EOF

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



function deletePage(){
jConfirm(
'{$vsLang->getWords("orders_deleteConfirm","Xóa thông tin đơn hàng?")}', 
'{$bw->vars['global_websitename']} Dialog', 
function(r){
if(r){
if($('#checked-obj').val()=='') {
vsf.alert("{$vsLang->getWords('hide_obj_confirm_noneitem', "Bạn chưa chọn!")}");
return false;
}
jsonStr = $('#checked-obj').val();
vsf.get('orders/deleteOrder/'+jsonStr,'mainContainer');
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
function __foreach_loop__id_4ddf6f4d941cc($option="")
{
global $vsLang,$bw,$vsSettings;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $option['pageList'] as $obj )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
<tr class="$vsf_class">
<td align="center" width="20">
<input type="checkbox" onclicktext="checkObject({$obj->getId()});" onclick="checkObject({$obj->getId()});" name="obj_{$obj->getId()}" value="{$obj->getId()}" class="myCheckbox" disabled/>
</td>
<td>{$obj->getName()}</td>
<td>{$obj->getAddress()}</td>
<td>{$obj->getPhone()}</td>
<td>{$obj->getEmail()}</td>
<td>{$obj->getPostDate("VN_LONG")}</td>
<td><a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" onclick="javascript:vsf.get('orders/viewCart/{$obj->getId()}/','orderView');">{$vsLang->getWords('order_bt_view','Xem đơn hàng')}</a></td>
</tr>

EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:printOrderItem:desc::trigger:>
//===========================================================================
function printOrderItem($obj="",$option="",&$amount=0) {global $vsLang,$bw;

//--starthtml--//
$BWHTML .= <<<EOF
		<table cellpadding="1" cellspacing="1" border="0">
        <tr align="left">
        <th >{$vsLang->getWords('order_name','Họ và tên')}:</th>
<td>{$obj->getName()}</td>
</tr>
<tr align="left">
<th >{$vsLang->getWords('order_email','Email')}:</th>
<td>{$obj->getEmail()}</td>
</tr>
<tr align="left">
 <th>{$vsLang->getWords('order_address','Địa chỉ')}:</th>
<td>{$obj->getAddress()}</td>
</tr>
<tr align="left">
<th>{$vsLang->getWords('order_phone','Điện thoại')}:</th>
 <td>{$obj->getPhone()}</td>
</tr>
</table>
<div style="padding:5px 0px; color:blue; font-size:18px;"> 
    <strong>{$vsLang->getWords('orderitem_list','Danh sách sản phẩm trong giỏ')}</strong>
    </div>
<table cellspacing="5" cellpadding="5" border="0">
<thead>
    <tr>
       <th>{$vsLang->getWords('order_no','STT')}</th> 
<th>{$vsLang->getWords('order_product_name','Tên sản phẩm')}</th> 
<!--<th>{$vsLang->getWords('order_product_code','Mã')}</th>--> 
<th>{$vsLang->getWords('order_product_amount','Số lượng')}</th> 
<th>{$vsLang->getWords('order_product_price','Đơn giá')}</th> 
<th>{$vsLang->getWords('order_product_money','Thành tiền')}</th>
    </tr>
</thead>

EOF;
if(count($option['cart'])) {
$BWHTML .= <<<EOF

 {$this->__foreach_loop__id_4ddf6f4d94436($obj,$option,$amount)}

EOF;
}

$BWHTML .= <<<EOF


EOF;
if(count($option['gift'])) {
$BWHTML .= <<<EOF

 {$this->__foreach_loop__id_4ddf6f4d945d5($obj,$option,$amount)}

EOF;
}

$BWHTML .= <<<EOF

<tr><th colspan="4" style="text-align:right;"><strong>Tổng cộng</strong></th><td><strong style="color:red">$amount {$vsLang->getWords('vnd',' VNĐ')}</strong></td></tr>
</table >
<div style="cursor: pointer; width:120px;" onclick="window.print();"><img src="{$bw->vars['board_url']}/styles/images/print.jpg"> In đơn hàng</div>
EOF;
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4ddf6f4d94436($obj="",$option="",&$amount=0)
{
global $vsLang,$bw;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $option['cart'] as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';$total = number_format($value->getPrice(false)*$value->getQuantity(),0,"",".");
$amount1 = $amount1 + $value->getPrice(false)*$value->getQuantity();
$amount = number_format($amount1,0,"",".");
	$BWHTML .= <<<EOF
		

<tr class="$vsf_class">
<td>{$vsf_count}</td>
<td>{$value->getTitle()}</td>
<td>{$value->getQuantity()}</td>
<td>{$value->getPrice()} {$vsLang->getWords('vnd',' VNĐ')}</td>
<td>{$total} {$vsLang->getWords('vnd',' VNĐ')}</td>
</tr>

EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4ddf6f4d9450c($obj="",$option="",&$amount=0,$value='')
{
;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $value['productId'] as $key=>$gPro )
	{
		$vsf_class = $vsf_count%2?'odd':'even';$option['gProduct'][$value['relId']][$gPro] = $option['rProduct'][$value['relId']][$gPro][$value['reProductId'][$key]];
	$BWHTML .= <<<EOF
		
                
                        
EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4ddf6f4d9458b($obj="",$option="",&$amount=0,$value='')
{
;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $option['gProduct'][$value['relId']] as $gpro )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
                <div> - {$gpro->productTitle}</div>
                
EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4ddf6f4d945d5($obj="",$option="",&$amount=0)
{
global $vsLang,$bw;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $option['gift'] as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';$value['productId'] = unserialize($value['productId']);
 $value['reProductId'] = unserialize($value['reProductId']);
 $total = number_format($value['price']*$value['quantity'],0,"",".");
$this->amount1 += $value['price']*$value['quantity'];
$amount = number_format($this->amount1,0,"",".");
$value['price'] = number_format($value['price'],0,"",".");
	$BWHTML .= <<<EOF
		
 
<tr class="$vsf_class">
<td>{$vsf_count}</td>
<td><b>{$value['giftTitle']}</b>

EOF;
if(count($option['gProduct'][$value['relId']])) {
$BWHTML .= <<<EOF

                {$this->__foreach_loop__id_4ddf6f4d9450c($obj,$option,$amount,$value)}
                {$this->__foreach_loop__id_4ddf6f4d9458b($obj,$option,$amount,$value)}
                        
EOF;
}

$BWHTML .= <<<EOF

</td>
<td>{$value['quantity']}</td>
<td>{$value['price']} {$vsLang->getWords('vnd',' VNĐ')}</td>
<td>{$total} {$vsLang->getWords('vnd',' VNĐ')}</td>
</tr>

EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:viewCart:desc::trigger:>
//===========================================================================
function viewCart(&$option="",&$amount="") {global $bw,$vsLang;

//--starthtml--//
$BWHTML .= <<<EOF
		<div class='ui-dialog ui-widget ui-widget-content ui-corner-all'>
    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
        <span class="ui-icon ui-icon-triangle-1-e"></span>
        <span class="ui-dialog-title">{$vsLang->getWords('order_listcart','Chi tiết hóa đơn')}</span>
    </div>
     <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-corner-all-inner ui-widget-header">
    <li class="ui-state-default ui-corner-top" id="success-objlist-bt"><a href="#" title="{$vsLang->getWords('add_obj_ht_bt',"Hoàn tất")}">{$vsLang->getWords('add_obj_ht_bt',"Hoàn tất")}</a></li>
    <li class="ui-state-default ui-corner-top" id="pending-objlist-bt"><a href="#" title="{$vsLang->getWords('add_obj_dc_bt',"Đang chờ")}">{$vsLang->getWords('add_obj_dc_bt',"Đang chờ")}</a></li>
  </ul>
    
EOF;
if(count($option['cart']) or count($option['gift'])) {
$BWHTML .= <<<EOF

<table cellspacing="1" cellpadding="1" id='productListTable' width="100%">
<thead>
    <tr>
       <th width="15"><input type="checkbox" onclick="checkAlls()" onclicktext="checkAlls()" name="all" id="checked-cart" /></th> 
<th>{$vsLang->getWords('order_status','Tình trạng')}</th> 
       <th>{$vsLang->getWords('order_title','Tên')}</th> 
<th>{$vsLang->getWords('order_product_type','Loại')}</th>
<th>{$vsLang->getWords('order_product_amount','Số lượng')}</th> 
<th>{$vsLang->getWords('order_product_price','Đơn giá')}</th> 
<th>{$vsLang->getWords('order_product_money','Thành tiền')}</th>
    </tr>
</thead>
<tbody>

EOF;
if(count($option['cart'])) {
$BWHTML .= <<<EOF

 {$this->__foreach_loop__id_4ddf6f4d94948($option,$amount)}

EOF;
}

$BWHTML .= <<<EOF


EOF;
if(count($option['gift'])) {
$BWHTML .= <<<EOF

 {$this->__foreach_loop__id_4ddf6f4d94b18($option,$amount)}

EOF;
}

$BWHTML .= <<<EOF

<tr>
<th  colspan="6" align="right">{$vsLang->getWords('order_product_total','Tổng cộng')}</th>
<th>$amount {$vsLang->getWords('vnd',' VNĐ')}
</th>
</tr>
</tbody>
<tfoot>
<tr>
<th colspan='9'>
<div style='float:right;'><a class="ui-state-default ui-corner-all ui-state-focus" href="javascript:;" onclick="orderPrint({$bw->input[2]});">In đơn hàng</a></div>
</th>
</tr>
</tfoot>
</table>

EOF;
}

else {
$BWHTML .= <<<EOF

<b>Không có sản phẩm nào.</b>

EOF;
}
$BWHTML .= <<<EOF


</div>

<script type="text/javascript">

function checkObjects() {
var checkedString = '',checkedStringG='';
$("input[type=checkbox]").each(function(){
if($(this).hasClass('myCheckboxCart')){
if(this.checked) checkedString += $(this).val()+',';
}
if($(this).hasClass('myCheckboxGift')){
if(this.checked) checkedStringG += $(this).val()+',';
}
});
checkedString = checkedString.substr(0,checkedString.lastIndexOf(','));
checkedStringG = checkedStringG.substr(0,checkedStringG.lastIndexOf(','));
$('#checked-cart').val(checkedString+"/"+checkedStringG);
}

function checkAlls() {
var checked_status = $("input[name=all]:checked").length;
var checkedString = '',checkedStringG = '';
$("input[type=checkbox]").each(function(){
if($(this).hasClass('myCheckboxCart')){
this.checked = checked_status;
if(checked_status) checkedString += $(this).val()+',';
}
if($(this).hasClass('myCheckboxGift')){
this.checked = checked_status;
if(checked_status) checkedStringG += $(this).val()+',';
}
});
$("span[acaica=myCheckboxCart]").each(function(){
if(checked_status)
this.style.backgroundPosition = "0 -50px";
else this.style.backgroundPosition = "0 0";
});
$("span[acaica=myCheckboxGift]").each(function(){
if(checked_status)
this.style.backgroundPosition = "0 -50px";
else this.style.backgroundPosition = "0 0";
});
checkedString = checkedString.substr(0,checkedString.lastIndexOf(','));
checkedStringG = checkedStringG.substr(0,checkedStringG.lastIndexOf(','));
$('#checked-cart').val(checkedString+"/"+checkedStringG);
}

$("#success-objlist-bt").click(function(){
checkObjects();
if($('#checked-cart').val()=='') {
jAlert(
"{$vsLang->getWords('visible_obj_confirm_noitem', "Bạn chưa chọn mục nào!")}",
"{$bw->vars['global_websitename']} Dialog"
)
return false;
}
vsf.get('{$bw->input[0]}/success-status/{$bw->input[2]}/'+$('#checked-cart').val(), 'orderView');
})
$("#pending-objlist-bt").click(function(){
checkObjects();
if($('#checked-cart').val()=='') {
jAlert(
"{$vsLang->getWords('visible_obj_confirm_noitem', "Bạn chưa chọn mục nào!")}",
"{$bw->vars['global_websitename']} Dialog"
)
return false;
}
vsf.get('{$bw->input[0]}/pending-status/{$bw->input[2]}/'+$('#checked-cart').val(), 'orderView');
})

function orderPrint(itemId)
{
 mywindow = window.open ("{$bw->base_url}orders/printOrder/"+itemId+"/&ajax=1",  "mywindow","width=500,height=500,resizable=0,location=1,status=0");
 mywindow.moveTo(50,50);
}
</script>
EOF;
//--endhtml--//
return $BWHTML;
}

//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4ddf6f4d94948(&$option="",&$amount="")
{
global $bw,$vsLang;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $option['cart'] as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';$total = number_format($value->getPrice(false)*$value->getQuantity(),0,"",".");
$this->amount1 += $value->getPrice(false)*$value->getQuantity();
$amount = number_format($this->amount1,0,"",".");
	$BWHTML .= <<<EOF
		

<tr class="$vsf_class">
<td align="center">
<input type="checkbox"  name="obj_{$value->getId()}" value="{$value->getId()}" class="myCheckboxCart" />
</td>

EOF;
if($value->getStatus()) {
$BWHTML .= <<<EOF

<td>Hoàn tất</td>

EOF;
}

else {
$BWHTML .= <<<EOF

<td>Đang chờ</td>

EOF;
}
$BWHTML .= <<<EOF

<td>{$value->getTitle()}</td>
<td>{$value->getInfo()}</td>
<td>{$value->getQuantity()}</td>
<td>{$value->getPrice()} {$vsLang->getWords('vnd',' VNĐ')}</td>
<td>{$total} {$vsLang->getWords('vnd',' VNĐ')}</td>
</tr>

EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4ddf6f4d94a57(&$option="",&$amount="",$value='')
{
;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $value['productId'] as $key=>$gPro )
	{
		$vsf_class = $vsf_count%2?'odd':'even';if($gPro)  $option['gProduct'][$value['relId']][$gPro] = $option['rProduct'][$value['relId']][$gPro][$value['reProductId'][$key]];
	$BWHTML .= <<<EOF
		
                
                        
EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4ddf6f4d94ace(&$option="",&$amount="",$value='')
{
;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $option['gProduct'][$value['relId']] as $gpro )
	{
		$vsf_class = $vsf_count%2?'odd':'even';
	$BWHTML .= <<<EOF
		
                <div> - {$gpro->productTitle} {$gpro->optionTitle}</div>
                
EOF;
$vsf_count++;
	}
	return $BWHTML;
}


//===========================================================================
// Foreach loop function
//===========================================================================
function __foreach_loop__id_4ddf6f4d94b18(&$option="",&$amount="")
{
global $bw,$vsLang;
	$BWHTML = '';
	$vsf_count = 1;
	$vsf_class = '';
	foreach( $option['gift'] as $value )
	{
		$vsf_class = $vsf_count%2?'odd':'even';$value['productId'] = unserialize($value['productId']);
 $value['reProductId'] = unserialize($value['reProductId']);
 $total = number_format($value['price']*$value['quantity'],0,"",".");
$this->amount1 += $value['price']*$value['quantity'];
$amount = number_format($this->amount1,0,"",".");
$value['price'] = number_format($value['price'],0,"",".");
	$BWHTML .= <<<EOF
		
 
<tr class="$vsf_class">
<td align="center">
<input type="checkbox"  name="obj_{$value['relId']}" value="{$value['relId']}" class="myCheckboxGift" />
</td>

EOF;
if($value['status']) {
$BWHTML .= <<<EOF

<td>Hoàn tất</td>

EOF;
}

else {
$BWHTML .= <<<EOF

<td>Đang chờ</td>

EOF;
}
$BWHTML .= <<<EOF

<td><b>{$value['giftTitle']}</b>

EOF;
if(count($option['gProduct'][$value['relId']])) {
$BWHTML .= <<<EOF

                {$this->__foreach_loop__id_4ddf6f4d94a57($option,$amount,$value)}
                {$this->__foreach_loop__id_4ddf6f4d94ace($option,$amount,$value)}
                        
EOF;
}

$BWHTML .= <<<EOF

</td>
<td></td>
<td>{$value['quantity']}</td>
<td>{$value['price']} {$vsLang->getWords('vnd',' VNĐ')}</td>
<td>{$total} {$vsLang->getWords('vnd',' VNĐ')}</td>
</tr>

EOF;
$vsf_count++;
	}
	return $BWHTML;
}
//===========================================================================
// <rsf:sendMail:desc::trigger:>
//===========================================================================
function sendMail() {global $vsLang;

//--starthtml--//
$BWHTML .= <<<EOF
		<div class="ui-dialog ui-widget ui-widget-content ui-corner-all">
<div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix ui-corner-all-inner">
    <span class="ui-dialog-title">{$vsLang->getWords('order_mailTo','Gửi Đến')}: <font color="red">{orderName}</font></span>
    <span class="ui-dialog-title">{$vsLang->getWords('order_email','Email')}: <font color="red">{orderEmail}</font></span>
    </div>
<form action="javascript:" method="post" id="formreply" name="form">
<input type="hidden" name="email" value="{orderEmail}"/>
<table cellpadding="0" cellspacing="0" class="ui-widget-content" style="border:none;">
<tr>
<td style="text-align:center; color: rgb(142, 81, 51); font-weight: bold;">
{$vsLang->getWords('order_Anwser','Nội Dung')}:
</td>
</tr>
<tr>
<td>{EDITOR}</td>
</tr>
<tr  class="ui-dialog">
<td align="center" class="ui-dialog-buttonpanel">
<input type="submit" name="submit" value="{$vsLang->getWords('order_send','Gửi')}">
</td>
</tr>
</table>
</form>
</div>
<script type="text/javascript">
$('#formreply').submit(function() {
vs.submitForm($('#formreply'),'orders/sendMail/{orderId}','orderlist');
$('#orderitem').html('');
return false;
});
</script>
EOF;
//--endhtml--//
return $BWHTML;
}


}?>