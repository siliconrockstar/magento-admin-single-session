<?php

class Siliconrockstar_Adminsinglesession_Model_Resource_Key extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct() {
        $this->_init('siliconrockstar_adminsinglesession/key', 'key_id');
    }

    /**
     * truncates the table
     */
    public function truncate() {
        return $this->_getWriteAdapter()->query('TRUNCATE TABLE ' . $this->getMainTable());
    }

}
