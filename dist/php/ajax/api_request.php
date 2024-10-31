<?php
	/**
	 * API Request
	 *
	 * @version		1.1
	 * @author    	mdonada
	 * @package		ajax
	 * @since 		icd-api-playground 1.0
	 */

    // namespace sistema\Nucleo;
    // use sistema\Nucleo\Response;
    // use sistema\Nucleo\ICD_API_Client;

	session_start();
	
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') { // is it an AJAX request?
		
		require_once '../../Sistema/Nucleo/Response.php';	
		require_once '../../Sistema/Nucleo/ICD_API_Client.php';
			
		$response = new Response();
		
		$uri = '';
		if(isset($_GET['uri'])) {			
			$uri = trim($_GET['uri']);	
		}
		elseif(isset($_GET['search'])) {
			$search = trim($_GET['search']);
			$uri = 'https://id.who.int/icd/entity/search?q='.$search;
		}
		
		if($uri != '') {					
			$icd_api_client = new ICD_API_Client($uri);
			$response->set(1, $icd_api_client->get());					
		}
			
		header('Content-Type: application/json');		
		echo $response->encode();
	}
		
?>