<?php
/** 
 *  @package ##PACKAGE##
 *  @version ##VERSION##
 */

/**
 * Description of Memcache
 * @package ##PACKAGE##
 * @author prskavecl
 */
class ZFCore_Session_SaveHandler_Memcache implements Zend_Session_SaveHandler_Interface
{
    private $maxlifetime = 3600;
    public $cache = '';

    public function __construct($cacheHandler)
    {
        $this->cache = $cacheHandler;
    }
    
    public function open($save_path, $name)
    {
        return true;
    }
    
    public function close()
    {
        return true;
    }
    
    public function read($id)
    {
        if(!($data = $this->cache->load($id))) {
            return '';
        }
        else {
            return $data;
        }
    }
    
    public function write($id, $sessionData)
    {
        $this->cache->save($sessionData, $id, array(), $this->maxlifetime);
        return true;
    }
    
    public function destroy($id)
    {
        $this->cache->remove($id);
        return true;
    }
    
    public function gc($notusedformemcache)
    {
        return true;
    }
}