<?php

/**
 * fvcn-core-validate.php
 *
 * Validate
 *
 * @package		Sigma Video
 * @subpackage	Validate
 * @author		Frank Verhoeven <info@frank-verhoeven.com>
 */

if (!defined('ABSPATH')) {
	exit;
}


/**
 * FvCommunityNews_Validate_Abstract
 *
 */
abstract class FvCommunityNews_Validate_Abstract
{
	/**
	 * Validation message
	 * @var string
	 */
	protected $_message = '';

	/**
	 * __construct()
	 *
	 * @version 20120704
	 */
	public function __construct()
	{
		$this->setMessage( __('Invallid value.', 'fvcn') );
	}

	/**
	 * setMessage()
	 *
	 * @version 20120704
	 * @param string $message
	 * @return FvCommunityNews_Validate_Abstract
	 */
	public function setMessage($message)
	{
		$this->_message = $message;
		return $this;
	}

	/**
	 * getMessage()
	 *
	 * @version 20120704
	 * @return string
	 */
	public function getMessage()
	{
		return $this->_message;
	}

	/**
	 * validate()
	 *
	 * @param mixed $value
	 * @return bool
	 */
	abstract public function isValid($value);
}


/**
 * FvCommunityNews_Validate
 *
 */
class FvCommunityNews_Validate extends FvCommunityNews_Validate_Abstract
{
	/**
	 * @var array
	 */
	protected $_validators = array();

	/**
	 * __construct()
	 *
	 * @version 20120705
	 * @param array $validators
	 */
	public function __construct(array $validators=null)
	{
		if (null !== $validators) {
			$this->setValidators($validators);
		}
	}

	/**
	 * setValidators()
	 *
	 * @version 20120705
	 * @param array $validators
	 * @return \FvCommunityNews_Validate
	 */
	public function setValidators(array $validators)
	{
		$this->clearValidators();
		
		foreach ($validators as $validator) {
			$this->addValidator($validator);
		}

		return $this;
	}
	
	/**
	 * clearValidators()
	 *
	 * @version 20120705
	 * @return \FvCommunityNews_Validate
	 */
	public function clearValidators()
	{
		$this->_validators = array();
		return $this;
	}

	/**
	 * getValidators()
	 *
	 * @version 20120705
	 * @return array
	 */
	public function getValidators()
	{
		return $this->_validators;
	}

	/**
	 * addValidator()
	 *
	 * @version 20120705
	 * @param string|FvCommunityNews_Validate_Abstract $validator
	 * @return \FvCommunityNews_Validate
	 * @throws Exception
	 */
	public function addValidator($validator)
	{
		if (is_string($validator) && class_exists($validator)) {
			$validator = new $validator();
		}

		if (is_object($validator) && $validator instanceof FvCommunityNews_Validate_Abstract) {
			$this->_validators[ get_class($validator) ] = $validator;
		} else {
			throw new Exception('Invallid validator provided');
		}

		return $this;
	}

	/**
	 * removeValidator()
	 *
	 * @version 20120705
	 * @param string|FvCommunityNews_Validate_Abstract $validator
	 * @return \FvCommunityNews_Validate
	 */
	public function removeValidator($validator)
	{
		if (is_string($validator)) {
			unset( $this->_validators[ $validator ] );
		}

		if (is_object($validator) && $validator instanceof FvCommunityNews_Validate_Abstract) {
			unset( $this->_validators[ get_class($validator) ] );
		}

		return $this;
	}

	/**
	 * validate()
	 *
	 * @version 20120705
	 * @param mixed $value
	 * @return boolean
	 */
	public function isValid($value)
	{
		if (null == $this->getValidators()) {
			return true;
		}

		foreach ($this->getValidators() as $validator) {
			if (!$validator->isValid($value)) {
				$this->setMessage( $validator->getMessage() );
				return false;
			}
		}

		return true;
	}
}


class FvCommunityNews_Validate_NotEmpty extends FvCommunityNews_Validate_Abstract
{
	protected $_trim;
	
	public function __construct($trim=true)
	{
		$this->_trim = (bool) $trim;
		$this->setMessage( __('Value cannot be empty.', 'fvcn') );
	}

	public function isValid($value)
	{
		if ($this->_trim) {
			$value = trim($value);
		}
		
		if (empty($value) || '' == $value || null == $value) {
			return false;
		}
		
		return true;
	}
}

class FvCommunityNews_Validate_Email extends FvCommunityNews_Validate_Abstract
{
	public function __construct()
	{
		$this->setMessage( __('Value must be a valid email address.', 'fvcn') );
	}

	public function isValid($value)
	{
		return (bool) is_email($value);
	}
}

class FvCommunityNews_Validate_Url extends FvCommunityNews_Validate_Abstract
{
	public function __construct()
	{
		$this->setMessage( __('Value must be a valid url.', 'fvcn') );
	}

	public function isValid($value)
	{
		return (bool) filter_var($value, FILTER_VALIDATE_URL);
	}
}

class FvCommunityNews_Validate_Name extends FvCommunityNews_Validate_Abstract
{
	public function __construct()
	{
		$this->setMessage( __('Value can only contain letters.', 'fvcn') );
	}

	public function isValid($value)
	{
		return (bool) preg_match('/^[\p{L}\p{M}]+$/u', str_replace(' ', '', $value) );
	}
}

class FvCommunityNews_Validate_Alpha extends FvCommunityNews_Validate_Abstract
{
	public function __construct()
	{
		$this->setMessage( __('Value can only contain alphabetic characters.', 'fvcn') );
	}

	public function isValid($value)
	{
		return (bool) ctype_alpha( str_replace(' ', '', $value) );
	}
}

class FvCommunityNews_Validate_Alnum extends FvCommunityNews_Validate_Abstract
{
	public function __construct()
	{
		$this->setMessage( __('Value can only contain alphabetic and numeric characters.', 'fvcn') );
	}

	public function isValid($value)
	{
		return (bool) ctype_alnum( str_replace(' ', '', $value) );
	}
}

class FvCommunityNews_Validate_MinLength extends FvCommunityNews_Validate_Abstract
{
	protected $_minLength;
	
	public function __construct($minLength=10)
	{
		$this->_minLength = abs( (int) $minLength );
		$this->setMessage( sprintf(__('Value requires at least %d characters.' , 'fvcn'), $this->_minLength) );
	}

	public function isValid($value)
	{
		return (bool) (mb_strlen($value) >= $this->_minLength);
	}
}

class FvCommunityNews_Validate_MaxLength extends FvCommunityNews_Validate_Abstract
{
	protected $_maxLength;
	
	public function __construct($maxLength=250)
	{
		$this->_maxLength = abs( (int) $maxLength );
		$this->setMessage( sprintf(__('Value cannot have more then %d characters.' , 'fvcn'), $this->_maxLength) );
	}

	public function isValid($value)
	{
		return (bool) (mb_strlen($value) <= $this->_maxLength);
	}
}

class FvCommunityNews_Validate_Tags extends FvCommunityNews_Validate_Abstract
{
	public function __construct()
	{
		$this->setMessage( __('Value can only contain alphabetic characters.', 'fvcn') );
	}

	public function isValid($value)
	{
		return (bool) preg_match('/^[a-zA-Z, ]+$/', $value);
	}
}

class FvCommunityNews_Validate_ImageUpload extends FvCommunityNews_Validate_Abstract
{
	public function __construct()
	{
		$this->setMessage( __('The image provided is not a valid image.', 'fvcn') );
	}

	public function isValid($value)
	{
		if (empty($value['tmp_name'])) {
			$this->setMessage( __('Value cannot be empty.', 'fvcn') );
			return false;
		}
		
		if (UPLOAD_ERR_OK != $value['error']) {
			return false;
		}
		
		if (filesize($value['tmp_name']) < 12) {
			return false;
		}
		
		$valid	= array(
			'gif'	=> IMAGETYPE_GIF,
			'png'	=> IMAGETYPE_PNG,
			'jpg'	=> IMAGETYPE_JPEG,
			'jpe'	=> IMAGETYPE_JPEG,
			'jpeg'	=> IMAGETYPE_JPEG
		);
		
		if (!array_key_exists(strtolower(pathinfo($value['name'], PATHINFO_EXTENSION)), $valid)) {
			return false;
		}
		
		$mime = exif_imagetype($value['tmp_name']);
		
		if (!$mime || !in_array($mime, $valid)) {
			return false;
		}
		
		return true;
	}
}

class FvCommunityNews_Validate_Timeout extends FvCommunityNews_Validate_Abstract
{
	public function __construct()
	{
		$this->setMessage( __('Timout occured, please resubmit.', 'fvcn') );
	}

	public function isValid($value)
	{
		$crypt = new FvCommunityNews_Crypt( hash('sha256', wp_create_nonce('fvcn-post-form-time-key')) );
		if ($crypt->canEncrypt()) {
			$value = explode(':', $value);
			if (2 != count($value)) {
				return false;
			}
			
			try {
				$time = (int) $crypt->setIv( base64_decode($value[0]) )->decrypt($value[1]);
			} catch (Exception $e) {
				return false;
			}
		} else {
			$time = (int) base64_decode($value);
		}
		
		// min 15 sec, max 1 hour
		if ($time+15 > time() || time()-3600 > $time) {
			return false;
		}
		
		return true;
	}
}

