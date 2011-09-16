<?php

if (! defined ( 'IN_VSF' )) {
	print "<h1>Permission denied!</h1>You cannot access this area. (VS Framework is powered by <a href=\"http://www.vietsol.net\">Viet Solution webdesign company</a>)";
	exit ();
}

global $vsStd;
$vsStd->requireFile ( CORE_PATH . "orders/orders.php" );

class orders_public {
	protected $html;
	protected $module;
	protected $output;
	private $products;
	private $gifts;
	
	function __construct() {
		global $vsTemplate,$bw,$vsModule,$vsStd;
	
		$this->html = $vsTemplate->load_template('skin_orders');
		$this->module = new orders();
		$vsStd->requireFile ( CORE_PATH . "products/products.php" );
		$this->products = new products();
	}
	
	/**
	 * @return unknown
	 */
	
	public function getOutput() {
		return $this->output;
	}
	
	function auto_run() {
		global $bw,$vsSess;
		switch ($bw->input [1]) {
			case 'info' :
				$this->orderInfo ();
				break;			
			case 'addtocart' :
				$this->addtocart ();
				break;
			case 'updatecart' :
				$this->updateCart ();
				break;
			case 'deletecart' :
				$this->deleteCart ();
				break;
			case 'deleteallcart':
				unset( $_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['count']);
				unset( $_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['total']);
				unset( $_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart']['item']);
				$this->loadDefault();
				break;			
			default :
				$this->loadDefault ();
				break;
		}
	}
	
	function loadDefault($message = ""){
		global $vsPrint,$vsLang;
		$vsPrint->mainTitle = $vsPrint->pageTitle = $vsLang->getWords ( 'cart_maintitle', 'Giỏ hàng' );
		$cartHtml = $this->cartSummary();
		$this->output = $this->html->mainHtml($cartHtml,$message);
	}
	
	function orderInfo() {
		global $bw, $vsUser;
		$info = array ("fullname" => $bw->input ['fullname'], "email" => $bw->input ['email'], "address" => $bw->input ['address'], "phone" => $bw->input ['phone'], "note" => $bw->input ['note'] );
		
		$this->module->obj->setName($info['fullname']);
		$this->module->obj->setAddress($info['address']);
		$this->module->obj->setEmail($info['email']);
		$this->module->obj->setInfo($info['note']);
		$this->module->obj->setPhone($info['phone']);
		$this->module->obj->setPostDate(time());
		$this->module->insertObject();
		if ($this->module->result['status']) {
			$this->orderProccess ( $this->module->obj->getId() );
		}
		$this->output = $this->html->loadMessage();
	}
	
	function orderProccess($OrderID) {
		global $bw;
		
		$cart = $_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'];

		if(count( $cart ['item'])){
			foreach ( $cart ['item'] as $value ){ 
				$this->module->orderitem->obj->convertToObject ( $value );
				$this->module->orderitem->obj->setStatus(0);
				$this->module->orderitem->obj->setPostDate(time());
				$this->module->orderitem->obj->setOrderId ( $OrderID );
				$this->module->orderitem->insertObject ();
			}
		}
		unset ( $_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['item'] );
		unset ( $_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['count'] );
	}
	
	function successOrder() {
		global $vsPrint, $vsLang, $vsTemplate;
		$vsPrint->mainTitle = $vsPrint->pageTitle = $vsLang->getWords ( 'cart_maintitle', 'Giỏ hàng' );
		$cart = $_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['order'];
		if ($cart) {
			foreach ( $cart as $value ) {
				$money = $money + $value ['total'];
				$value ['total'] = number_format ( $value ['total'] );
				$vsTemplate->assign_block_vars ( $value, 'CART_ITEM' );
			}
			$vsTemplate->assign_var ( 'Total', number_format ( $money ) );
		}
		$mess['message']=$vsLang->getWords('successOrder','Bạn đã đặt hàng thành công!');
		$vsTemplate->assign_vars($mess);
		$vsTemplate->assign_vars_form_string ( $this->html->messageOrder () );
	}
	
	function cartSummary() {
		global $vsPrint;		
		$itemCart['cart'] = $this->getCart();	
		return $this->output = $this->html->cartSummary ($itemCart) ;
	}
	
	function getCart($message='') {
		global $vsTemplate, $vsLang;
		//Check Session Cart
		$cart = $_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['item'];
		
		if(!count($cart)) 
			return "";
		return $this->html->itemList($cart);
	}
	
	function getProductArray($productId) {
		global $bw, $vsPrint;
		//Get Information Product By productId
		$this->products->setFieldsString("productId,productCode,productTitle,productPrice,productImage");
		$this->products->getObjectById($productId);
		
		if(!$this->products->obj->getId())
			$vsPrint->boink_it($_SERVER['HTTP_REFERER']);
			
		$inforProduct = array ( 'productId' 		=> $this->products->obj->getId(), 
						'itemPrice' 		=> $this->products->obj->getPrice(false), 
						'itemNumberPrice' 	=> $this->products->obj->getPrice(), 
						'itemTitle' 		=> $this->products->obj->getTitle(),
						'itemProductImage' 	=> $this->products->obj->getCacheImagePathByFile($this->products->obj->getImage(),87,58),
						'itemQuantity' 		=> $bw->input['quantity'] ? $bw->input['quantity']  : 1, 
						'total' 			=> $this->products->obj->getPrice(false)
					);
					
		$inforProduct['total'] = $inforProduct['total']*$inforProduct['itemQuantity'];
		
		return $inforProduct;
	}
	
	function addtocart() {
		global $bw, $vsPrint, $vsLang, $vsTemplate, $vsUser;
		
		if(!$vsUser->obj->getId()){
//			$vsPrint->redirect_screen ( $vsLang->getWords ( 'global_login_error', 'Bạn hãy đăng nhập để thực hiện chức năng này.' ), 'users/login-form/' );
			$message = $vsLang->getWords ( 'global_login_error', 'Bạn hãy đăng nhập để thực hiện chức năng này.' );
			return $this->output = $this->html->orderCheckLogin($message);
		}else{
			if(is_numeric($bw->input [2])){
				//Get information to session cart and assign to $item			
				$cart = $_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['item'];
				//Get Information Product By productId
				$inforProduct = $this->getProductArray ( $bw->input [2] );
				//Init cart is empty
				$i = 0;
				
				$count = $_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['count'];
				$total = $_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['total'];
			
				if (is_array ( $cart )) {
					foreach ( $cart as $key => $value ) {
						if ($value ['productId'] == $inforProduct ['productId']) {
							$i++;
							$cart [$key]['itemQuantity'] = $value ['itemQuantity'] + 1;
							$cart [$key]['total'] += $value ['itemPrice'];
							$total +=$value ['itemPrice'];
						}
					}
				}
			
				if ($i==0) {
					$count = $count + 1;
					$total += $inforProduct['total'];
					$cart [$inforProduct ['productId']] = $inforProduct;
				}
	
				$_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['item'] = $cart;
				$_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['count'] = $count;
				$_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['total'] = $total;
				
				$message = sprintf($vsLang->getWords("order_communicate","Sản phẩm [%s] đã được thêm vào giỏ hàng."),$inforProduct['itemTitle']);
			}else{			
				$message = $vsLang->getWords('order_messages_none','Sản phẩm này không tồn tại.');
			}
			
			return $this->output = $this->html->orderLoading($message);
		}
	}
	
	function updateCart() {
		global $bw,$vsStd,$vsLang,$vsTemplate;
		
		$arrayCart = $bw->input['arrayCart'];
		
		$total = 0;
		
		$cart = $_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['item'];	
		$total = $_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['total'];
		
		foreach ( $cart as $key => $value ){
			if (isset($arrayCart[$key]['productId'])) {
				if($arrayCart[$key]['itemQuantity'] - $cart[$key]['itemQuantity'] <= 0){
					$value['itemQuantity'] = $arrayCart[$key]['itemQuantity'];//$cart[$key]['itemQuantity'];				
				}else{
					$value['itemQuantity'] = $arrayCart[$key]['itemQuantity'];
				}
				$inforProduct = array(
										"itemQuantity" 	=> $value['itemQuantity'], 
										"curTotalChange"=> $cart [$key]['itemPrice'] * ($value['itemQuantity'] - $cart[$key]['itemQuantity'])
									);
				
				$cart[$key]['itemQuantity'] = $inforProduct['itemQuantity'];
				$cart[$key]['total'] += $inforProduct ['curTotalChange'];
				
				$total += $inforProduct['curTotalChange'];
			}
		}
		
		$message = $vsLang->getWords('update_succes','<a href="#null" class="coman infor" id="kx">Giỏ hàng đã cập nhật thành công</a>');
		
		if($total<0) 
			$total=0;
			
		$_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['item'] = $cart;
		$_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['total'] = $total;
		
		$this->loadDefault($message);
	}
	
	function deleteCart() {
		global $bw, $vsLang ;
		
		$arrayCart = $bw->input['arrayCart'];
		
		$cart = $_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['item'];
		$count = $_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['count'];
		$total = $_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['total'];
		
		foreach ( $cart  as  $key => $value ){
			if (isset ($arrayCart [$key]['productId'])) {
				$total -= $cart [$key]['total'];
				$count --;
				unset ( $cart [$key] );
				if(!count($cart[$key])) 
					unset ( $cart [$key] );
			}
		}
		if($count==0){
			$total = 0;
		}else{
			$message = $vsLang->getWords('delitem_succes','<a href="#null" class="coman infor">Sản Phẩm Đã Được Xoá</a>');
		}
		
		$_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['item'] = $cart;
		$_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['total'] = $total;
		$_SESSION [$_SESSION [APPLICATION_TYPE]['language']['currentLang']['langFolder']] ['cart'] ['count'] = $count;
		
		$this->loadDefault($message);
	}
	
	function sendMail() {
		global $bw, $vsLang, $vsStd;
		
		$vsStd->requireFile ( LIBS_PATH . "Email.class.php" );
		$bw->vars ['order_mail'] = $bw->vars ['order_mail'] ? $bw->vars ['order_mail'] : $bw->vars ['global_systememail'];
		$email = new Emailer ( );
		$message = sprintf ( $vsLang->getWords ( 'order_mail_message', 'Khách hàng <strong>%s</strong> đã đặt hàng. <br />Xem chi tiết: %s' ), $bw->input ['orderName'], $bw->vars ['board_url'] . "/admin.php?vs=orders/" );
		$message = $email->clean_message ( $message );
		$email->setTo ( $bw->vars ['order_mail'] );
		$email->setSubject ( $vsLang->getWords ( 'order_subjectMail', 'Đơn đặt hàng' ) );
		$email->setMessage ( $message );
		$email->send_mail ();
	}

}
?>