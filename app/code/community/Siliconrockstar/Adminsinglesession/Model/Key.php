<?php

/**
 * Admin login log model
 * 
 * @author andy@siliconrockstar.com
 * @copyright  Copyright (c) 2016 Andy Boyd
 * @license https://opensource.org/licenses/OSL-3.0 
 */
class Siliconrockstar_Adminsinglesession_Model_Key extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('siliconrockstar_adminsinglesession/key');
    }

}