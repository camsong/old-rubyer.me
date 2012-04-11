<?PHP

/**
 * Dropbox class
 *
 * This source file can be used to communicate with DropBox (http://dropbox.com)
 *
 * The class is documented in the file itself. If you find any bugs help me out and report them. 
 * If you report a bug, make sure you give me enough information (include your code).
 *
 *
 *
 * License
 * Copyright (c), Daniel Huesken. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
 * 3. The name of the author may not be used to endorse or promote products derived from this software without specific prior written permission.
 *
 * This software is provided by the author "as is" and any express or implied warranties, including, but not limited to, the implied warranties of merchantability and fitness for a particular purpose are disclaimed. In no event shall the author be liable for any direct, indirect, incidental, special, exemplary, or consequential damages (including, but not limited to, procurement of substitute goods or services; loss of use, data, or profits; or business interruption) however caused and on any theory of liability, whether in contract, strict liability, or tort (including negligence or otherwise) arising in any way out of the use of this software, even if advised of the possibility of such damage.
 *
 * @author		Daniel Huesken <daniel@huesken-net.de>
 * @version		2.0.0
 *
 * @copyright	Copyright (c), Daniel Huesken. All rights reserved.
 * @license		BSD License
 */

class backwpup_Dropbox {
	const API_URL = 'https://api.dropbox.com/';
	const API_CONTENT_URL = 'https://api-content.dropbox.com/';
	const API_WWW_URL = 'https://www.dropbox.com/';
	const API_VERSION_URL = '1/';
	
	private $root = 'sandbox';
	private $OAuthObject;
	private $OAuthToken;
	private $ProgressFunction = false;
	
	public function __construct($applicationKey, $applicationSecret,$dropbox=false) {
		$this->OAuthObject = new BackWPup_OAuthSimple($applicationKey, $applicationSecret);
		if ($dropbox)
			$this->root = 'dropbox';
		else
			$this->root = 'sandbox';
	}

	public function setOAuthTokens($token,$secret) {
		$this->OAuthToken = array('oauth_token'=>$token,'oauth_secret'=> $secret);
	}
	
	public function setProgressFunction($function) {
		if (function_exists($function))
			$this->ProgressFunction = $function;
		else
			$this->ProgressFunction = false;
	}
	
	public function accountInfo(){
		$url = self::API_URL.self::API_VERSION_URL.'account/info';
		return $this->request($url);
	}
	
	public function upload($file, $path = '',$overwrite=true){
		$file = str_replace("\\", "/",$file);
		if (!is_readable($file) or !is_file($file))
			throw new DropboxException("Error: File \"$file\" is not readable or doesn't exist.");
		if (filesize($file)>157286400)
			throw new DropboxException("Error: File \"$file\" is too big max. 150 MB.");
		$url = self::API_CONTENT_URL.self::API_VERSION_URL.'files_put/'.$this->root.'/'.trim($path, '/');
		return $this->request($url, array('overwrite' => ($overwrite)? 'true' : 'false'), 'PUT', $file);
	}
	
	public function download($path,$echo=false){
		$url = self::API_CONTENT_URL.self::API_VERSION_URL.'files/'.$this->root.'/'.trim($path,'/');
		if (!$echo)
			return $this->request($url);
		else
			$this->request($url,'','GET','',true);
	}
	
	public function metadata($path = '', $listContents = true, $fileLimit = 10000){
		$url = self::API_URL.self::API_VERSION_URL.'metadata/'.$this->root.'/'.trim($path,'/');
		return $this->request($url, array('list' => ($listContents)? 'true' : 'false', 'file_limit' => $fileLimit));
	}
	
	public function search($path = '', $query , $fileLimit = 1000){
		if (strlen($query)>=3)
			throw new DropboxException("Error: Query \"$query\" must three characters long.");
		$url = self::API_URL.self::API_VERSION_URL.'search/'.$this->root.'/'.trim($path,'/');
		return $this->request($url, array('query' => $query, 'file_limit' => $fileLimit));
	}
	
	public function shares($path = ''){
		$url = self::API_URL.self::API_VERSION_URL.'shares/'.$this->root.'/'.trim($path,'/');
		return $this->request($url);
	}
	
	public function media($path = ''){
		$url = self::API_URL.self::API_VERSION_URL.'media/'.$this->root.'/'.trim($path,'/');
		return $this->request($url);
	}
	
	public function fileopsDelete($path){
		$url = self::API_URL.self::API_VERSION_URL.'fileops/delete';
		return $this->request($url, array('path' => '/'.trim($path,'/'), 'root' => $this->root));
	}

	public function fileopsCreate_folder($path){
		$url = self::API_URL.self::API_VERSION_URL.'fileops/create_folder';
		return $this->request($url, array('path' => '/'.trim($path,'/'), 'root' => $this->root));
	}

	public function oAuthAuthorize($callback_url) {
		//request tokens
		$OAuthSign = $this->OAuthObject->sign(array(
			'path'    	=>self::API_URL.self::API_VERSION_URL.'oauth/request_token',
			'method' 	=>'HMAC-SHA1',
			'action'	=>'GET',
			'parameters'=>array('oauth_callback'=>$callback_url)));
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $OAuthSign['signed_url']);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_SSLVERSION,3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		if (is_file(dirname(__FILE__).'/aws/lib/requestcore/cacert.pem'))
			curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__).'/aws/lib/requestcore/cacert.pem');
		curl_setopt($ch, CURLOPT_AUTOREFERER , true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$content = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($status>=200 and $status<300 and 0==curl_errno($ch) ) {
			parse_str($content, $oauth_token);
		} else {
			$output = json_decode($content, true);
			if(isset($output['error']) && is_string($output['error'])) $message = $output['error'];
			elseif(isset($output['error']['hash']) && $output['error']['hash'] != '') $message = (string) $output['error']['hash'];
			elseif (0!=curl_errno($ch)) $message = '('.curl_errno($ch).') '.curl_error($ch);
			else $message = '('.$status.') Invalid response.';
			throw new DropboxException($message);		
		}
		curl_close($ch);
		$OAuthSign = $this->OAuthObject->sign(array(
			'path'      =>self::API_WWW_URL.self::API_VERSION_URL.'oauth/authorize',
			'action'	=>'GET',
			'parameters'=>array(
				'oauth_token' => $oauth_token['oauth_token'])));
		return array('authurl'=>$OAuthSign['signed_url'],'oauth_token'=>$oauth_token['oauth_token'],'oauth_token_secret'=>$oauth_token['oauth_token_secret']);
	}
	
	public function oAuthAccessToken($oauth_token, $oauth_token_secret) {
		 $OAuthSign = $this->OAuthObject->sign(array(
			'path'      => self::API_URL.self::API_VERSION_URL.'oauth/access_token',
			'action'	=>'GET',
			'method' 	=>'HMAC-SHA1',
			'parameters'=>array('oauth_token'    => $oauth_token),
			'signatures'=>array('oauth_token'=>$oauth_token,'oauth_secret'=>$oauth_token_secret)));
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $OAuthSign['signed_url']);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_SSLVERSION,3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		if (is_file(dirname(__FILE__).'/aws/lib/requestcore/cacert.pem'))
			curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__).'/aws/lib/requestcore/cacert.pem');
		curl_setopt($ch, CURLOPT_AUTOREFERER , true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$content = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($status>=200 and $status<300  and 0==curl_errno($ch)) {
			parse_str($content, $oauth_token);
			$this->setOAuthTokens($oauth_token['oauth_token'],$oauth_token['oauth_token_secret']);
			return $oauth_token;
		} else {
			$output = json_decode($content, true);
			if(isset($output['error']) && is_string($output['error'])) $message = $output['error'];
			elseif(isset($output['error']['hash']) && $output['error']['hash'] != '') $message = (string) $output['error']['hash'];
			elseif (0!=curl_errno($ch)) $message = '('.curl_errno($ch).') '.curl_error($ch);
			else $message = '('.$status.') Invalid response.';
			throw new DropboxException($message);		
		}
	}	
	
	private function request($url, $args = null, $method = 'GET', $file = null, $echo=false){
		$args = (is_array($args)) ? $args : array();
		$url = $this->url_encode($url);
		/* Sign Request*/
		$this->OAuthObject->reset();
		$OAuthSign=$this->OAuthObject->sign(array(
			'path'      => $url,
			'parameters'=> $args,
			'action'=> $method,
			'method' => 'HMAC-SHA1',
			'signatures'=> $this->OAuthToken));
		
		/* Header*/
		$headers[]='Expect:';
		
		/* Build cURL Request */
		$ch = curl_init();
		if ($method == 'POST') {
			curl_setopt($ch, CURLOPT_POST, true);
			$args = (is_array($args)) ? http_build_query($args) : $args;
			curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
			$headers[]='Content-Length: '.strlen($args);
			$headers[]='Authorization: '.$OAuthSign['header'];
			curl_setopt($ch, CURLOPT_URL, $url);
		} elseif ($method == 'PUT') {
			$datafilefd=fopen($file,'r');
			curl_setopt($ch,CURLOPT_PUT,true);
			curl_setopt($ch,CURLOPT_INFILE,$datafilefd);
			curl_setopt($ch,CURLOPT_INFILESIZE,filesize($file));
			$args = (is_array($args)) ? '?'.http_build_query($args) : $args;
			$headers[]='Authorization: '.$OAuthSign['header'];
			curl_setopt($ch, CURLOPT_URL, $url.$args);
		} else {
			$headers[]='Authorization: '.$OAuthSign['header'];
			$args = (is_array($args)) ? '?'.http_build_query($args) : $args;
			curl_setopt($ch, CURLOPT_URL, $url.$args);
		}
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSLVERSION,3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		if (is_file(dirname(__FILE__).'/aws/lib/requestcore/cacert.pem'))
			curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__).'/aws/lib/requestcore/cacert.pem');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		if (!empty($this->ProgressFunction) and function_exists($this->ProgressFunction) and defined('CURLOPT_PROGRESSFUNCTION') and $method == 'PUT') {
			curl_setopt($ch, CURLOPT_NOPROGRESS, false);
			curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, $this->ProgressFunction);
			curl_setopt($ch, CURLOPT_BUFFERSIZE, 512);
		}
		if ($echo) {
			echo curl_exec($ch);
			$output='';
		} else {
			$content = curl_exec($ch);
			$output = json_decode($content, true);
		}
		$status = curl_getinfo($ch);
		if ($method == 'PUT')
			fclose($datafilefd);
		
		if (isset($output['error']) or $status['http_code']>=300 or $status['http_code']<200 or curl_errno($ch)>0) {
			if(isset($output['error']) && is_string($output['error'])) $message = '('.$status['http_code'].') '.$output['error'];
			elseif(isset($output['error']['hash']) && $output['error']['hash'] != '') $message = (string) '('.$status['http_code'].') '.$output['error']['hash'];
			elseif (0!=curl_errno($ch)) $message = '('.curl_errno($ch).') '.curl_error($ch);
			elseif ($status['http_code']==304) $message = '(304) The folder contents have not changed (relies on hash parameter).';
			elseif ($status['http_code']==400) $message = '(400) Bad input parameter: '.strip_tags($content);
			elseif ($status['http_code']==401) $message = '(401) Bad or expired token. This can happen if the user or Dropbox revoked or expired an access token. To fix, you should re-authenticate the user.';
			elseif ($status['http_code']==403) $message = '(403) Bad OAuth request (wrong consumer key, bad nonce, expired timestamp, ...). Unfortunately, reauthenticating the user won\'t help here.';
			elseif ($status['http_code']==404) $message = '(404) The file was not found at the specified path, or was not found at the specified rev.';
			elseif ($status['http_code']==405) $message = '(405) Request method not expected (generally should be GET,PUT or POST).';
			elseif ($status['http_code']==406) $message = '(406) There are too many file entries to return.';
			elseif ($status['http_code']==411) $message = '(411) Chunked encoding was attempted for this upload, but is not supported by Dropbox.';
			elseif ($status['http_code']==415) $message = '(415) The image is invalid and cannot be thumbnailed.';
			elseif ($status['http_code']==503) $message = '(503) Your app is making too many requests and is being rate limited. 503s can trigger on a per-app or per-user basis.';
			elseif ($status['http_code']==507) $message = '(507) User is over Dropbox storage quota.';
			else $message = '('.$status['http_code'].') Invalid response.';
			throw new DropboxException($message);
		} else {
			curl_close($ch);
			if (!is_array($output))
				return $content;
			else
				return $output;
		}
	}
	
	private function url_encode($string) {
		$string = str_replace('?','%3F',$string);
		$string = str_replace('=','%3D',$string);
		$string = str_replace(' ','%20',$string);
		$string = str_replace('(','%28',$string);
		$string = str_replace(')','%29',$string);
		$string = str_replace('&','%26',$string);
		$string = str_replace('@','%40',$string);
		return $string;
	}

}

class DropboxException extends Exception {
}


/**
 * OAuthSimple - A simpler version of OAuth
 *
 * https://github.com/jrconlin/oauthsimple
 *
 * @author		 jr conlin <src@jrconlin.com>
 * @copyright	  unitedHeroes.net 2011
 * @version		1.3
 * @license		See license.txt
 *
 */

class BackWPup_OAuthSimple {
	private $_secrets;
	private $_default_signature_method;
	private $_action;
	private $_nonce_chars;

	/**
	 * Constructor
	 *
	 * @access public
	 *
	 * @param api_key (String)	   The API Key (sometimes referred to as the consumer key) This value is usually supplied by the site you wish to use.
	 * @param shared_secret (String) The shared secret. This value is also usually provided by the site you wish to use.
	 *
	 * @return OAuthSimple (Object)
	 */
	function __construct( $APIKey = "", $sharedSecret = "" ) {

		if ( ! empty($APIKey) ) {
			$this->_secrets['consumer_key'] = $APIKey;
		}

		if ( ! empty($sharedSecret) ) {
			$this->_secrets['shared_secret'] = $sharedSecret;
		}

		$this->_default_signature_method = "HMAC-SHA1";
		$this->_action                   = "GET";
		$this->_nonce_chars              = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

		return $this;
	}

	/**
	 * Reset the parameters and URL
	 *
	 * @access public
	 * @return OAuthSimple (Object)
	 */
	public function reset() {
		$this->_parameters = Array();
		$this->path        = NULL;
		$this->sbs         = NULL;

		return $this;
	}

	/**
	 * Set the parameters either from a hash or a string
	 *
	 * @access public
	 *
	 * @param  (string, object) List of parameters for the call, this can either be a URI string (e.g. "foo=bar&gorp=banana" or an object/hash)
	 *
	 * @return OAuthSimple (Object)
	 */
	public function setParameters( $parameters = Array() ) {

		if ( is_string( $parameters ) ) {
			$parameters = $this->_parseParameterString( $parameters );
		}
		if ( empty($this->_parameters) ) {
			$this->_parameters = $parameters;
		}
		else if ( ! empty($parameters) ) {
			$this->_parameters = array_merge( $this->_parameters, $parameters );
		}
		if ( empty($this->_parameters['oauth_nonce']) ) {
			$this->_getNonce();
		}
		if ( empty($this->_parameters['oauth_timestamp']) ) {
			$this->_getTimeStamp();
		}
		if ( empty($this->_parameters['oauth_consumer_key']) ) {
			$this->_getApiKey();
		}
		if ( empty($this->_parameters['oauth_token']) ) {
			$this->_getAccessToken();
		}
		if ( empty($this->_parameters['oauth_signature_method']) ) {
			$this->setSignatureMethod();
		}
		if ( empty($this->_parameters['oauth_version']) ) {
			$this->_parameters['oauth_version'] = "1.0";
		}

		return $this;
	}

	/**
	 * Convenience method for setParameters
	 *
	 * @access public
	 * @see	setParameters
	 */
	public function setQueryString( $parameters ) {
		return $this->setParameters( $parameters );
	}

	/**
	 * Set the target URL (does not include the parameters)
	 *
	 * @param path (String) the fully qualified URI (excluding query arguments) (e.g "http://example.org/foo")
	 *
	 * @return OAuthSimple (Object)
	 */
	public function setURL( $path ) {
		if ( empty($path) ) {
			throw new BackWPup_OAuthSimpleException('No path specified for OAuthSimple.setURL');
		}
		$this->_path = $path;

		return $this;
	}

	/**
	 * Convenience method for setURL
	 *
	 * @param path (String)
	 *
	 * @see setURL
	 */
	public function setPath( $path ) {
		return $this->_path = $path;
	}

	/**
	 * Set the "action" for the url, (e.g. GET,POST, DELETE, etc.)
	 *
	 * @param action (String) HTTP Action word.
	 *
	 * @return OAuthSimple (Object)
	 */
	public function setAction( $action ) {
		if ( empty($action) ) {
			$action = 'GET';
		}
		$action = strtoupper( $action );
		if ( preg_match( '/[^A-Z]/', $action ) ) {
			throw new BackWPup_OAuthSimpleException('Invalid action specified for OAuthSimple.setAction');
		}
		$this->_action = $action;

		return $this;
	}

	/**
	 * Set the signatures (as well as validate the ones you have)
	 *
	 * @param signatures (object) object/hash of the token/signature pairs {api_key:, shared_secret:, oauth_token: oauth_secret:}
	 *
	 * @return OAuthSimple (Object)
	 */
	public function signatures( $signatures ) {
		if ( ! empty($signatures) && ! is_array( $signatures ) ) {
			throw new BackWPup_OAuthSimpleException('Must pass dictionary array to OAuthSimple.signatures');
		}
		if ( ! empty($signatures) ) {
			if ( empty($this->_secrets) ) {
				$this->_secrets = Array();
			}
			$this->_secrets = array_merge( $this->_secrets, $signatures );
		}
		if ( isset($this->_secrets['api_key']) ) {
			$this->_secrets['consumer_key'] = $this->_secrets['api_key'];
		}
		if ( isset($this->_secrets['access_token']) ) {
			$this->_secrets['oauth_token'] = $this->_secrets['access_token'];
		}
		if ( isset($this->_secrets['access_secret']) ) {
			$this->_secrets['oauth_secret'] = $this->_secrets['access_secret'];
		}
		if ( isset($this->_secrets['access_token_secret']) ) {
			$this->_secrets['oauth_secret'] = $this->_secrets['access_token_secret'];
		}
		if ( empty($this->_secrets['consumer_key']) ) {
			throw new BackWPup_OAuthSimpleException('Missing required consumer_key in OAuthSimple.signatures');
		}
		if ( empty($this->_secrets['shared_secret']) ) {
			throw new BackWPup_OAuthSimpleException('Missing requires shared_secret in OAuthSimple.signatures');
		}
		if ( ! empty($this->_secrets['oauth_token']) && empty($this->_secrets['oauth_secret']) ) {
			throw new BackWPup_OAuthSimpleException('Missing oauth_secret for supplied oauth_token in OAuthSimple.signatures');
		}

		return $this;
	}

	public function setTokensAndSecrets( $signatures ) {
		return $this->signatures( $signatures );
	}

	/**
	 * Set the signature method (currently only Plaintext or SHA-MAC1)
	 *
	 * @param method (String) Method of signing the transaction (only PLAINTEXT and SHA-MAC1 allowed for now)
	 *
	 * @return OAuthSimple (Object)
	 */
	public function setSignatureMethod( $method = "" ) {
		if ( empty($method) ) {
			$method = $this->_default_signature_method;
		}
		$method = strtoupper( $method );
		switch ( $method )
		{
			case 'PLAINTEXT':
			case 'HMAC-SHA1':
				$this->_parameters['oauth_signature_method'] = $method;
				break;
			default:
				throw new BackWPup_OAuthSimpleException ("Unknown signing method $method specified for OAuthSimple.setSignatureMethod");
				break;
		}

		return $this;
	}

	/** sign the request
	 *
	 * note: all arguments are optional, provided you've set them using the
	 * other helper functions.
	 *
	 * @param args (Array) hash of arguments for the call {action, path, parameters (array), method, signatures (array)} all arguments are optional.
	 *
	 * @return (Array) signed values
	 */
	public function sign( $args = array() ) {
		if ( ! empty($args['action']) ) {
			$this->setAction( $args['action'] );
		}
		if ( ! empty($args['path']) ) {
			$this->setPath( $args['path'] );
		}
		if ( ! empty($args['method']) ) {
			$this->setSignatureMethod( $args['method'] );
		}
		if ( ! empty($args['signatures']) ) {
			$this->signatures( $args['signatures'] );
		}
		if ( empty($args['parameters']) ) {
			$args['parameters'] = array();
		}
		$this->setParameters( $args['parameters'] );
		$normParams                           = $this->_normalizedParameters();
		$this->_parameters['oauth_signature'] = $this->_generateSignature( $normParams );

		return Array(
			'parameters' => $this->_parameters,
			'signature'  => self::_oauthEscape( $this->_parameters['oauth_signature'] ),
			'signed_url' => $this->_path . '?' . $this->_normalizedParameters(),
			'header'	 => $this->getHeaderString(),
			'sbs'		=> $this->sbs
		);
	}

	/**
	 * Return a formatted "header" string
	 *
	 * NOTE: This doesn't set the "Authorization: " prefix, which is required.
	 * It's not set because various set header functions prefer different
	 * ways to do that.
	 *
	 * @param args (Array)
	 *
	 * @return $result (String)
	 */
	public function getHeaderString( $args = array() ) {
		if ( empty($this->_parameters['oauth_signature']) ) {
			$this->sign( $args );
		}
		$result = 'OAuth ';

		foreach ( $this->_parameters as $pName => $pValue )
		{
			if ( strpos( $pName, 'oauth_' ) !== 0 ) {
				continue;
			}
			if ( is_array( $pValue ) ) {
				foreach ( $pValue as $val )
				{
					$result .= $pName . '="' . self::_oauthEscape( $val ) . '", ';
				}
			}
			else
			{
				$result .= $pName . '="' . self::_oauthEscape( $pValue ) . '", ';
			}
		}

		return preg_replace( '/, $/', '', $result );
	}

	private function _parseParameterString( $paramString ) {
		$elements = explode( '&', $paramString );
		$result   = array();
		foreach ( $elements as $element )
		{
			list ($key, $token) = explode( '=', $element );
			if ( $token ) {
				$token = urldecode( $token );
			}
			if ( ! empty($result[$key]) ) {
				if ( ! is_array( $result[$key] ) ) {
					$result[$key] = array( $result[$key], $token );
				}
				else
				{
					array_push( $result[$key], $token );
				}
			}
			else
				$result[$key] = $token;
		}
		return $result;
	}


	private static function _oauthEscape( $string ) {
		if ( $string === 0 ) {
			return 0;
		}
		if ( $string == '0' ) {
			return '0';
		}
		if ( strlen( $string ) == 0 ) {
			return '';
		}
		if ( is_array( $string ) ) {
			throw new BackWPup_OAuthSimpleException('Array passed to _oauthEscape');
		}
		$string = rawurlencode( $string );

		$string = str_replace( '+', '%20', $string );
		$string = str_replace( '!', '%21', $string );
		$string = str_replace( '*', '%2A', $string );
		$string = str_replace( '\'', '%27', $string );
		$string = str_replace( '(', '%28', $string );
		$string = str_replace( ')', '%29', $string );

		return $string;
	}

	private function _getNonce( $length = 5 ) {
		$result  = '';
		$cLength = strlen( $this->_nonce_chars );
		for ( $i = 0; $i < $length; $i ++ )
		{
			$rnum = rand( 0, $cLength );
			$result .= substr( $this->_nonce_chars, $rnum, 1 );
		}
		$this->_parameters['oauth_nonce'] = $result;

		return $result;
	}

	private function _getApiKey() {
		if ( empty($this->_secrets['consumer_key']) ) {
			throw new BackWPup_OAuthSimpleException('No consumer_key set for OAuthSimple');
		}
		$this->_parameters['oauth_consumer_key'] = $this->_secrets['consumer_key'];

		return $this->_parameters['oauth_consumer_key'];
	}

	private function _getAccessToken() {
		if ( ! isset($this->_secrets['oauth_secret']) ) {
			return '';
		}
		if ( ! isset($this->_secrets['oauth_token']) ) {
			throw new BackWPup_OAuthSimpleException('No access token (oauth_token) set for OAuthSimple.');
		}
		$this->_parameters['oauth_token'] = $this->_secrets['oauth_token'];

		return $this->_parameters['oauth_token'];
	}

	private function _getTimeStamp() {
		return $this->_parameters['oauth_timestamp'] = time();
	}

	private function _normalizedParameters() {
		$normalized_keys = array();
		$return_array    = array();

		foreach ( $this->_parameters as $paramName=> $paramValue ) {
			if ( ! preg_match( '/\w+_secret/', $paramName ) || (strpos( $paramValue, '@' ) !== 0 && ! file_exists( substr( $paramValue, 1 ) )) ) {
				if ( is_array( $paramValue ) ) {
					$normalized_keys[self::_oauthEscape( $paramName )] = array();
					foreach ( $paramValue as $item )
					{
						array_push( $normalized_keys[self::_oauthEscape( $paramName )], self::_oauthEscape( $item ) );
					}
				}
				else
				{
					$normalized_keys[self::_oauthEscape( $paramName )] = self::_oauthEscape( $paramValue );
				}
			}
		}

		ksort( $normalized_keys );

		foreach ( $normalized_keys as $key=> $val )
		{
			if ( is_array( $val ) ) {
				sort( $val );
				foreach ( $val as $element )
				{
					array_push( $return_array, $key . "=" . $element );
				}
			}
			else
			{
				array_push( $return_array, $key . '=' . $val );
			}

		}

		return join( "&", $return_array );
	}


	private function _generateSignature() {
		$secretKey = '';
		if ( isset($this->_secrets['shared_secret']) ) {
			$secretKey = self::_oauthEscape( $this->_secrets['shared_secret'] );
		}

		$secretKey .= '&';
		if ( isset($this->_secrets['oauth_secret']) ) {
			$secretKey .= self::_oauthEscape( $this->_secrets['oauth_secret'] );
		}
		switch ( $this->_parameters['oauth_signature_method'] )
		{
			case 'PLAINTEXT':
				return urlencode( $secretKey );
				;
			case 'HMAC-SHA1':
				$this->sbs = self::_oauthEscape( $this->_action ) . '&' . self::_oauthEscape( $this->_path ) . '&' . self::_oauthEscape( $this->_normalizedParameters() );

				return base64_encode( hash_hmac( 'sha1', $this->sbs, $secretKey, TRUE ) );
			default:
				throw new BackWPup_OAuthSimpleException('Unknown signature method for OAuthSimple');
				break;
		}
	}
}

class BackWPup_OAuthSimpleException extends Exception {

}

?>