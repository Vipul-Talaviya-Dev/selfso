<?php

namespace App\Library;

use Cloudder; 

class Helper 
{
	const ERROR_CODE = 422, SUCCESS_CODE = 200, UNAUTHORIZED = 401, FORBIDDEN = 403, NOTFOUND = 404, SERVERERROR = 500, CREATE_CODE = 201;
	const USERIMAGE = '';
	// const USERIMAGE = 'https://res.cloudinary.com/vipul-talaviya/image/upload/v1600100911/user.png';
	
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

        $publicKey = Cloudder::upload($file, null, ['folder' => $path])->getPublicId(); // public key
        
        return [
			'status' => true,
			'publicKey' => $publicKey,
			'message' => 'successfully uploaded',
		];

	}
	/**
	 * @type~ 1: image, 2: Video
	 * */
	public static function postUpload($path, $file, $type = 1)
	{
		try {
			if($file->getSize() > 2097152) {
				return [
					'status' => false,
					'message' => 'The image size must be less than 2 Mb.',
					'publicKey' => '',
				];
			}
			// dd($file->getClientOriginalExtension(), $file->getRealPath(), $file->getSize(), $file->getClientOriginalName());
			if($type == 2) {
				$publicKey = Cloudder::uploadVideo($file, null, ['folder' => $path])->getPublicId(); // public key
			} else {
				$publicKey = Cloudder::upload($file, null, ['folder' => $path])->getPublicId(); // public key
			}
	        $publicKey = $publicKey.'.'.$file->getClientOriginalExtension();
	        return [
				'status' => true,
				'publicKey' => $publicKey,
				'message' => 'successfully uploaded',
			];			
		} catch (\Exception $e) {
			return [
				'status' => false,
				'publicKey' => '',
				'message' => $e->getMessage(),
			];
		}
	}

	/**
	 * type 1: image, 2: video
	 * */
	public static function imageRemove($fileName, $type = 1)
	{
		$options = [];
		if($type == 2) {
			$options = ['resource_type' => 'video'];
		}
		
		Cloudder::destroyImage($fileName, $options);
        Cloudder::delete($fileName, $options);
        return true;
	}
	
	/**
	 * type 1: image, 2: video
	 * */
	public static function getImage($fileName, $type = 1)
	{
		$options = [];
		if($type == 2) {
			$options = ['resource_type' => 'video'];
		}
		return Cloudder::secureShow($fileName, $options);
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

