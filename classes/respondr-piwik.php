<?php

class respondrPiwik {
	
	function __construct() {
	
	}
	
	public function viewProd() {
		global $post, $woocommerce;
		$wooProd = new WC_Product( $post->ID );
		
		// SKU
		$prod['sku'] = $wooProd->get_sku();
		if( empty( $prodSku ) ){
			$prod['sku'] = $post->ID;
		}
		
		// TITLE
		$prod['title'] = $post->post_title;
		
		// CATS
		$prod['cats'] = wp_get_post_terms( $post->ID, 'product_cat', array( 'fields' => 'names' ) );
		
		// PRICE
		$prod['price'] = $wooProd->get_price();
		
		// IMG
		$img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
		$prod['img'] = $img[0];
		
		// DESC
		$prod['desc'] = $post->post_content;
		
		wp_enqueue_script( 'rsp_viewProd', PLUGIN_URL.'/includes/js/viewProd.js', array( 'rsp_tracker' ), null, false );
		wp_localize_script( 'rsp_viewProd', 'respProd', $prod );
	}
	
	public function catView( $id ) {
		var_dump( $id );
	}
	
	public function addToCart() {
		global $woocommerce;
		$qtys = $woocommerce->cart->get_cart_item_quantities();
		
		foreach($woocommerce->cart->get_cart() as $cart_item_key => $values ) {
			$_product = $values['data'];
			$wooProd = new WC_Product( $_product->id );
			
			// TITLE
			$prod['title'] = $_product->post->post_title;
			
			// CATS
			$prod['cats'] = wp_get_post_terms( $_product->id, 'product_cat', array( 'fields' => 'names' ) );
			
			
			// PRICE
			$prod['price'] = $wooProd->get_price();
			
			// SKU
			$prod['sku'] = $wooProd->get_sku();
			if( empty( $prodSku ) ){
				$prod['sku'] = $_product->id;
			}
			
			// QTY
			$prod['qty'] = $qtys[$_product->id];
			
			$cartProds[] = $prod;
			
		}
		
		
		$wooTotal = $woocommerce->cart->get_cart_total();
		$wooTotal = str_replace( '<span class="amount">', '', $wooTotal );
		$wooTotal = str_replace( '</span>', '', $wooTotal );
		$wooTotal = preg_replace( '/&#36;/', '', $wooTotal );
		
		wp_enqueue_script( 'rsp_addCart', PLUGIN_URL.'/includes/js/addCart.js', array( 'rsp_tracker' ), null, false );
		wp_localize_script( 'rsp_addCart', 'respCart', $cartProds );
		wp_localize_script( 'rsp_addCart', 'respCartTotal', $wooTotal );
	}
	
	public function orderComplete() {
		
	}
	
	public function saveUser( $id ) {
		$user = get_userdata( $id );
		wp_enqueue_script( 'rsp_userSave', PLUGIN_URL.'/includes/js/saveUser.js', array( 'rsp_tracker' ), null, false );
		wp_localize_script( 'rsp_userSave', 'respUser', array( 
			'email' 	 => $user->user_email, 
			'first_name' => $user->user_firstname,
			'last_name'  => $user->user_lastname
		) );
	}
	
	public function userLogin( $user_login, $user ) {
		/*
wp_enqueue_script( 'rsp_userSave', PLUGIN_URL.'/includes/js/saveUser.js', array( 'rsp_tracker' ), null, false );
		wp_localize_script( 'rsp_userSave', 'respUser', array( 
			'email' 	 => $user->user_email, 
			'first_name' => $user->user_firstname,
			'last_name'  => $user->user_lastname
		) );
*/
	}
}

?>