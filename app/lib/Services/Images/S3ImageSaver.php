<?php namespace Lib\Services\Images;

use Config, File, Imagine, Request, Log;
use Lib\Services\Scraping\Curl;
use Aws\S3\S3Client;

class S3ImageSaver
{
	/**
	 * Curl instance.
	 * 
	 * @var Lib\Services\Scraping\Curl
	 */
	protected $scraper;

	/**
	 * AWS S3 Client Instance
	 * @var Aws\S3\S3Client
	 */
	protected $s3;

	/**
	 * S3 config data
	 * @var array
	 */
	protected $config;

	public function __construct(Curl $scraper)
	{
		$this->scraper = $scraper;

		$this->config = Config::get('s3');
        
        $this->s3 = S3Client::factory(array(
            'key' => $this->config['access_key'],
            'secret' => $this->config['secret_key']
        ));
	}

	/**
	 * Downloads and saves single image locally.
	 * 
	 * @param  string $url 
	 * @param  string $id
	 * @param  string/null $path
	 * @param  string/int $num
	 * @return void
	 */
	public function saveSingle($url, $id, $path = null, $num = '')
	{
		if ( ! $url) return;

		if ( ! $path)
		{
			$path = 'imdb/posters/';
		}
		
    	$image = $this->scraper->curl($url);
    	
    	//catch error if image we get passed is corrupt and return
    	//false so we wont save a reference of image that doesnt 
    	//exist in database.
    	try
    	{
    		$file = public_path($path) . $id . $num .'.jpg';
    		$image = Imagine::raw($image)->save($file, 100);
    		if($image)
    		{
    			$this->moveFileToS3($file, null, true);
    		}
    	}
    	catch(\Intervention\Image\Exception\InvalidImageDataStringException $e)
    	{
    		return false;
    	}

    	return true;
	}

	/**
	 * Downloads and saves multiple images locally.
	 * 
	 * @param  string $url 
	 * @param  string $id
	 * @param  string/null $path
	 * @param  string/int $num
	 * @return void
	 */
	public function saveMultiple(array $urls, $id = null, $path)
	{
		if ( empty($urls)) return;

    	$images = $this->scraper->multiCurl($urls);
    	
    	foreach ($images as $k => $v)
    	{ 	
    		//we're saving cast images
    		if (strpos($k, 'nm') || ! $id)
    		{
    			try
    			{
    				$file = public_path($path) . $k . '.jpg';
    				$image = Imagine::raw($v)->save($file, 100);
    				if($image)
		    		{
		    			$this->moveFileToS3($file, null, true);
		    		}
    			}
    			catch(\Exception $e){}
    			
    		}

    		//we're saving movie stills
    		else
    		{
    			try
    			{
    				$image = Imagine::raw($v);
    				
    				$file = public_path($path) . $id . $k.'.jpg';
    				$image->save($file, 60);
    				if($image)
		    		{
		    			$this->moveFileToS3($file, null, true);
		    		}
    				
    				$file = public_path($path) . $id . $k.'.thumb'.'.jpg';
    				$thumb = $image->save($file, 100);
    				if($thumb)
		    		{
		    			$this->moveFileToS3($file, null, true);
		    		}
    			}
    			catch(\Exception $e){}
    		}   		
    	}
	}

	/**
	 * Saves avatar in filesystem.
	 * 
	 * @param  UploadedImage $image
	 * @param  string $path
	 * @return void
	 */
	public function saveAvatar($image, array $paths)
	{
		foreach ($paths as $k => $v)
		{
			//delete any previous user avatars
			File::delete(public_path($v));

			if ($k == 'big')
			{
				$encoded = Imagine::make($image['avatar']->getRealPath())
					->resize(100, 100)
					->encode('jpg');

				$file = public_path($v);
				$image = Imagine::make($encoded)->save($file);	
			
				if($image)
				{
					$this->moveFileToS3($file, null, true);
				}
			}
			else
			{
				$encoded = Imagine::make($image['avatar']->getRealPath())
					->resize(35, 35)
					->encode('jpg');

				$file = public_path($v);
				$image = Imagine::make($encoded)->save($file);
				if($image)
				{
					$this->moveFileToS3($file, null, true);
				}
			}
		}			
	}

	/**
	 * Saves user background in filesystem.
	 * 
	 * @param  UploadedImage $image
	 * @param  string $path
	 * @return void
	 */
	public function saveBg($image, $path)
	{
		$encoded = Imagine::make($image['bg']->getRealPath())
			->resize(1140, 400, true)
			->encode('jpg');

		$file = public_path($path);
		$image = Imagine::make($encoded)->save($file);			
		if($image)
		{
			$this->moveFileToS3($file, null, true);	
		}
	}

	/**
	 * Saves title image locally.
	 * 
	 * @param  UploadedImage $image
	 * @param  string $path
	 * @return void
	 */
	public function saveTitleImage($input, $name)
	{
		$encoded = Imagine::make($input['image']->getRealPath())
			->encode('jpg');

		$file = public_path('assets/images/'.$name.'.jpg');
		$image = Imagine::make($encoded)->save($file);	
		
		if($image)
		{
			$this->moveFileToS3($file, null, true);
		}
	}


	public function s3KeyExists($key)
	{
		return $this->s3->doesObjectExist($this->getBucket(), $key);
	}

	/**
	 * Upload a file to S3
	 * @param  string  $filePath    Absolute path to local file
	 * @param  string  $key         
	 * @param  bool $deleteLocal 
	 * @return bool               
	 */
	public function moveFileToS3($filePath, $key = null, $deleteLocal = false)
	{
		if(!$key)
		{
			$key = str_replace(public_path(), '', $filePath);
		}

		if (substr($key, 0, 1) != '/') {
            $key = '/'.$key;
        }

        try {

	        if( ! $this->s3KeyExists($key))
	        {
		        $result = $this->s3->putObject(array(
			        'Bucket'     => $this->getBucket(),
			        'Key'        => $key,
			        'SourceFile' => $filePath,
			        'ACL'        => 'public-read',
		        ));

		        // We can poll the object until it is accessible
		        $this->s3->waitUntilObjectExists(array(
			        'Bucket' => $this->getBucket(),
			        'Key'    => $key
		        ));
	        }

            if($deleteLocal && file_exists($filePath))
            {
            	unlink($filePath);
            }

            return true;

        } catch (\Exception $ex) {
        	Log::error($ex);
            return false;
        }
	}

	/**
	 * Get the S3 client instance
	 * @return Aws\S3\S3Client
	 */
	public function s3()
	{
		return $this->s3;
	}

	public function linkTo($path, $secure = true)
	{
		if($secure || Request::secure())
		{
			$domain = array_get($this->config, 'secure_url_base');
		}
		else
		{
			$domain = array_get($this->config, 'url_base');
		}
		
		$path = str_replace('//', '/', '/'.$this->getBucket().'/'.$path);
		
		$url = $domain.$path;
		
		return $url;
	}

	/**
	 * Get the default bucket to use.
	 * @return [type] [description]
	 */
	protected function getBucket()
	{
		return array_get($this->config, 'bucket');
	}
}