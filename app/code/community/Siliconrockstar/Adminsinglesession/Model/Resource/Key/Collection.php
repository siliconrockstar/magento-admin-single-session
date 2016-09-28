<?php


class Siliconrockstar_Adminsinglesession_Model_Resource_Key_Collection extends
Mage_Core_Model_Resource_Db_Collection_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('siliconrockstar_adminsinglesession/key');
    }

}