<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class JwtModel extends Model
{
	private $base64UrlKeyEncode = array('+' => '-', '/' => '_', '=' => '');

	private $base64UrlKeyDecode = array('-' => '+', '_' => '/', ' ' => '=');

	private $header = array([
		'typ' => 'JWT',
		'alg' => 'HS256'
	]);

	public function getEncodeJwt($payload = array()) {

		$headerJson = json_encode($this->header);
		$payloadJson = json_encode($payload);


		$base64urlHeader = $this->encodeBase64Url($headerJson);
		$base64urlPayload = $this->encodeBase64Url($payloadJson);

		// ma hoa sha256
		$signature = hash_hmac('sha256', $base64urlHeader . '.' . $base64urlPayload, 'khoa bi mat');
		$base64urlSignature = $this->encodeBase64Url($signature);

		$jwt = $base64urlHeader. '.' .$base64urlPayload. '.' .$base64urlSignature;

		return $jwt;
	}

	// ma hoa base64url
	public function encodeBase64Url($data) {
		return strtr(base64_encode($data), $this->base64UrlKeyEncode);
	}

	// giai ma base64url
	public function decodeBase64Url($data) {
		return base64_decode( strtr($data, $this->base64UrlKeyDecode));
	}

	public function getDecodeJwt($data) {

		$payload = explode('.', $data)[1];
		$payloadDecode = $this->decodeBase64Url($payload);

		return json_decode($payloadDecode);
	}
}
