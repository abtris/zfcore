<?php
/**
 * @package ##PACKAGE##
 * @version ##VERSION##
 */
/**
 * @package ##PACKAGE##
 */
class ZFCore_Acl extends Zend_Acl
{
        /**
         * Constructor
         */
	public function __construct()
	{
		// resources
		$this->add(new Zend_Acl_Resource(ZFCore_Resources::ACCOUNT));
		$this->add(new Zend_Acl_Resource(ZFCore_Resources::ADMIN_SECTION));
		$this->add(new Zend_Acl_Resource(ZFCore_Resources::PUBLICPAGE));
                // roles
		$this->addRole(new Zend_Acl_Role(ZFCore_Roles::GUEST));
		$this->addRole(new Zend_Acl_Role(ZFCore_Roles::USER),ZFCore_Roles::GUEST);
		$this->addRole(new Zend_Acl_Role(ZFCore_Roles::ADMIN), ZFCore_Roles::USER);
                // allow to resource
		$this->allow(ZFCore_Roles::GUEST , ZFCore_Resources::PUBLICPAGE);
		$this->allow(ZFCore_Roles::ADMIN , ZFCore_Resources::ADMIN_SECTION);
		$this->allow(ZFCore_Roles::USER , ZFCore_Resources::ACCOUNT);		
		
	}
}
