<?php

class Siliconrockstar_Adminsinglesession_Model_Session extends Mage_Admin_Model_Session {
    
    /**
     * This is actually implemented in Mage_Admin_Model_Sessions's parent:
     * Mage_Core_Model_Session_Abstract
     */
    public function renewSession() {
        
        return parent::renewSession();
        
    }
    
}