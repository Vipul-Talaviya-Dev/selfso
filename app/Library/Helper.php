<?php

namespace App\Library;

// use Cloudder; 

class Helper 
{
	const ERROR_CODE = 422, SUCCESS_CODE = 200;

	public static function storeUserImagePath($userId)
	{
		return 'users/'.$userId.'/';
	}

	/**
	 * Use for common image upload
	 * */
	public static function imageUpload($path, $file)
	{
		if($file->getSize() > 2097152) {
			return [
				'status' => false,
				'message' => 'The image size must be less than 2 Mb.',
				'publicKey' => '',
			];
		}

		if(!in_array($file->getMimeType(), ["image/jpeg", "image/gif", "image/png", "image/pjpeg", "image/jpg"])) {
			return [
				'status' => false,
				'message' => 'The image extension not allowed',
				'publicKey' => '',
			];	
		}
		// 2097152 => 2MB

        // $publicKey = Cloudder::upload($file, null, ['folder' => $path])->getPublicId(); // public key
        $publicKey = '';
        return [
			'status' => true,
			'publicKey' => $publicKey,
			'message' => 'successfully uploaded',
		];

	}

	public static function getUserIpAddr()
	{
	    if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
	        #ip from share internet
	        $ip = $_SERVER['HTTP_CLIENT_IP'];
	    } elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	        #ip pass from proxy
	        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    } else {
	        $ip = $_SERVER['REMOTE_ADDR'];
	    }
	    return $ip;
	}
	
}

