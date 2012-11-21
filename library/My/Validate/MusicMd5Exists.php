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
 * @category  Zend
 * @package   Zend_Validate
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 * @version   $Id: Size.php 23775 2011-03-01 17:25:24Z ralph $
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * 验证数据库中是否已经存在MD5值和该文件相同的伴奏
 *
 * @category  My
 * @package   My_Validate
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd     New BSD License
 */
class My_Validate_MusicMd5Exists extends Zend_Validate_Abstract
{
    /**#@+
     * @const string Error constants
     */
    const EXISTS   = 'fileExists';
    const NOT_FOUND = 'fileSizeNotFound';
    /**#@-*/

    /**
     * @var array Error message templates
     */
    protected $_messageTemplates = array(
        self::EXISTS   => "你要上传的伴奏本站已经收录（伴奏id为%id%），请不要重复上传。",
        self::NOT_FOUND => "File '%value%' is not readable or does not exist",
    );

    
    
    protected $table = NULL;
    
    protected $_id = NULL;

    protected $_messageVariables = array(
        'id'  => '_id',
    );
    
    /**
     * Sets validator options
     *
     * If $options is a integer, it will be used as maximum filesize
     * As Array is accepts the following keys:
     * 'min': Minimum filesize
     * 'max': Maximum filesize
     * 'bytestring': Use bytestring or real size for messages
     *
     * @param  integer|array $options Options for the adapter
     */
    public function __construct()
    {
        $this->table = new Model_DbTable_Accompaniments();
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if the filesize of $value is at least min and
     * not bigger than max (when max is not null).
     *
     * @param  string $value Real file to check for size
     * @param  array  $file  File data from Zend_File_Transfer
     * @return boolean
     */
    public function isValid($value, $file = null)
    {
        // Is file readable ?
        require_once 'Zend/Loader.php';
        if (!Zend_Loader::isReadable($value)) {
            return $this->_throw($file, self::NOT_FOUND);
        }

       $music = new My_Music($value);
       $md5Value = $music->md5OriginalFileWithAlltagsTripped();
       $record = $this->table->fetchRow("`md5_code` = '$md5Value'");
//       $logger = Zend_Registry::get("logger");
//       $logger->log($music, Zend_Log::DEBUG);
//       $logger->log($md5Value, Zend_Log::DEBUG);
//       $logger->log($record, Zend_Log::DEBUG);
       if ($record){
       		$this->_id = $record->id;
       		$this->_throw($file, self::EXISTS);
       }

        if (count($this->_messages) > 0) {
            return false;
        }

        return true;
    }

    
    /**
     * Throws an error of the given type
     *
     * @param  string $file
     * @param  string $errorType
     * @return false
     */
    protected function _throw($file, $errorType)
    {
        if ($file !== null) {
            $this->_value = $file['name'];
        }

        $this->_error($errorType);
        return false;
    }
}
