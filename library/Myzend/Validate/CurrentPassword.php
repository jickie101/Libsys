<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: RecordExists.php 23775 2011-03-01 17:25:24Z ralph $
 */

/**
 * @see Zend_Validate_Db_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * Confirms a record exists in a table.
 *
 * @category   Zend
 * @package    Zend_Validate
 */
class Myzend_Validate_CurrentPassword extends Zend_Validate_Abstract
{

	private $currentpassword = null;
	
	const MSG_ERROR = '';
	
    protected $_messageTemplates = array(
        self::MSG_ERROR => "Current password is invalid.",
    );
	
	function __construct($currentpassword = null) 
	{
		$this->currentpassword = $currentpassword;
	}
	
    public function isValid($value)
    {
        $this->_setValue($value);

        $isValid = true;
 
        if (md5($value) != $this->currentpassword) 
		{
			$this->_error(self::MSG_ERROR);
            $isValid = false;
        }
		
		return $isValid;
    }
}

