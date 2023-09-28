<?php
	
class KT_iPro {
	
	// @TODO store this in the db
	public $base_url = "https://booking.kateandtoms.com";
	public $token_url = "/oauth/2.0/token";
	
	public $auth = "authorization: Basic MTAwMTo2NDNmNjc5NTA1NTA0ZDE3OTY1NDQ2NDdkMTFjNWIwMA==";

	public $poll = 6;
	
	// TODO write comment
	public function get_access_token(){
		
		//$request_url = $this->base_url . $this->token_url;
		
		$curl = curl_init();
		
		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->base_url . $this->token_url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 60,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "grant_type=client_credentials",
			CURLOPT_HTTPHEADER => array(
				$this->auth,
				"cache-control: no-cache",
				"content-type: application/x-www-form-urlencoded",
				"postman-token: 2eddc391-bb32-5149-16de-9dc195389976"
			),
		));
		
		$response = curl_exec($curl);
		$err = curl_error($curl);
		
		curl_close($curl);
		
		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			// Will dump a beauty json :3
		  	return json_decode($response, true);
		}

	}
	

	// TODO write comment
	public function get_all_properties($token) {
		
        $curl = curl_init();
        
        error_log('Sending request to iPro API for properties');
		WP_CLI::log( 'iPro Cron Log: class.iprokit.php line 59 - Sending request to iPro API for properties' );
		
		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->base_url . '/apis/properties?access_token='.$token,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 60,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"postman-token: b550f3fc-f029-aabd-4d48-13e95961f328"
			),
        ));
        
        error_log('Request to iPro API for properties complete');
		WP_CLI::log( 'iPro Cron Log: class.iprokit.php line 73 - Request to iPro API for properties complete' );

		
		$response = curl_exec($curl);
		WP_CLI::log( 'iPro Cron Log: class.iprokit.php line 77 - Results ' . $response );

		$err = curl_error($curl);
		
		curl_close($curl);
		
		if ($err) {
			error_log('cURL Error #:' . $err);
		} else {
			return json_decode($response, true);
		}

	}

	/**
	 * Get updated properties
	 *
	 * @param [type] $token
	 * @return void
	 */
	public function get_updated_properties($token) {

		$curl = curl_init();
		
		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->base_url . '/apis/properties/lastupdated?lastUpdated='.$this->poll.'&access_token='.$token,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 60,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"postman-token: b550f3fc-f029-aabd-4d48-13e95961f328"
			),
        ));
		
		$response = curl_exec($curl);

		$err = curl_error($curl);
		
		curl_close($curl);
		
		if ($err) {
			error_log('cURL Error #:' . $err);
		} else {
			return json_decode($response, true);
		}

		
	}

	/**
	 * Get properties where availability has changed
	 *
	 * @param [type] $token
	 * @return void
	 */
	public function get_updated_availability_properties($token) {
		$curl = curl_init();
		
		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->base_url . '/apis/property/dayavailabilitycheck?lastUpdated='.$this->poll.'&access_token='.$token,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 60,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"postman-token: b550f3fc-f029-aabd-4d48-13e95961f328"
			),
        ));
		
		$response = curl_exec($curl);

		$err = curl_error($curl);
		
		curl_close($curl);
		
		if ($err) {
			error_log('cURL Error #:' . $err);
		} else {
			return json_decode($response, true);
		}

	}

	/**
	 * Get iPro property vs Website property ID
	 *
	 * @param [type] $token
	 * @return void
	 */
	public function get_ipro_property_reference_lookup($token) {
		$curl = curl_init();
		
		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->base_url . '/apis/properties/reflookup?access_token='.$token,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 60,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"postman-token: b550f3fc-f029-aabd-4d48-13e95961f328"
			),
        ));
		
		$response = curl_exec($curl);

		$err = curl_error($curl);
		
		curl_close($curl);
		
		if ($err) {
			error_log('cURL Error #:' . $err);
		} else {
			return json_decode($response, true);
		}

	}

	// TODO write comment
	public function get_property_availability($token, $id){

		$curl = curl_init();
        
        error_log('Sending request to iPro API for property availability');

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->base_url . '/apis/property/'.$id.'/dayavailability?months=36&access_token='.$token.'',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 60,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"postman-token: e49d6e15-49eb-2343-8d20-6d73a03da652"
			),
        ));
        
        error_log('Request to iPro API for property availability complete');
		
		$response = curl_exec($curl);
		$err = curl_error($curl);
		
		curl_close($curl);
		
		if ($err) {
			error_log('cURL Error #:' . $err);
		} else {
			return json_decode($response, true);
		}

	}

	// TODO write comment
	public function get_property_rates($token, $id){

		$curl = curl_init();
        
        error_log('Sending request to iPro API for property rates');

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->base_url . '/apis/property/'.$id.'/customrates?PropertyReference=HouseId&access_token='.$token.'',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 60,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"postman-token: e49d6e15-49eb-2343-8d20-6d73a03da652"
			),
        ));
        
        error_log('Request to iPro API for property rates complete');
		
		$response = curl_exec($curl);
		$err = curl_error($curl);
		
		curl_close($curl);
		
		if ($err) {
			error_log('cURL Error #:' . $err);
		} else {
			return json_decode($response, true);
		}

	}

}