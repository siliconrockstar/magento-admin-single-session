<?php

/**
 * Implements some hooks to make sure each admin user only has
 * a single session running at a time (prevents multiple users from using the 
 * same login, which sucks from a security/accountability standpoint).
 * 
 * Sets a cookie in the browser on login and also saves its value to the db. On
 * subsequent admin area requests, checks to make sure the two values match, and
 * kills admin session if mismatch or missing cookie.
 */
class Siliconrockstar_Adminsinglesession_Model_Observer extends Mage_Core_Model_Observer {

    /**
     * If the admin user authenticated successfully, kill any existing sessions
     * they have running and give them an access token (key) before letting Magento
     * continue to regerate session and properly log them in.
     * 
     * @param Varien_Event_Observer $observer - _data[result] = 1 if success,
     * also contains user data, plaintext password ( surprisingly :/ )
     */
    public function onUserAuthenticateAfter($observer) {

        $data = $observer->getData();

        // if login was successful
        if ($data['result'] == 1) {

            // delete any old keys for the user name
            $oldKeys = Mage::getModel('siliconrockstar_adminsinglesession/key')
                    ->getCollection()
                    ->addFieldToFilter('username', $data['username']);

            // should only be one, so minimal performance hit from loop
            foreach ($oldKeys as $oldKey) {
                $oldKey->delete();
            }

            // create a new access key
            $keyString = md5(uniqid());

            // save new access key
            $key = Mage::getModel('siliconrockstar_adminsinglesession/key');
            $key->setUsername($data['username']);
            $key->setKey($keyString);
            $key->save();

            // save key to browser cookie
            Mage::getSingleton('core/cookie')->set('siliconrockstar_adminsinglesession', $keyString, time() + 86400, '/');
        }

        return true;
    }

    /**
     * Hook in as early as possible and if admin session, then verify
     * the key in the database matches the key in the user's cookie. If no cookie
     * key or mismatch, log the user out of admin and show a meaningful message
     * why on the admin login page.
     * 
     * @param Varien_Event_Observer $observer
     * @return null
     */
    public function onControllerFrontInitBefore($observer) {

        $adminUser = Mage::getSingleton('admin/session')->getUser();

        if ($adminUser) {

            // compare cookie value against db value
            $dbKey = Mage::getModel('siliconrockstar_adminsinglesession/key')
                    ->getCollection()
                    ->addFieldToSelect('key')
                    ->addFieldToFilter('username', $adminUser->getUsername())
                    ->getFirstItem()
                    ->getData('key');

            $cookieKey = Mage::getModel('core/cookie')->get('siliconrockstar_adminsinglesession');

            // kill the user's admin session if the cookie key is missing
            // or doesn't match what is in teh db
            if (!$cookieKey || $dbKey != $cookieKey) {

                // unset cookie
                Mage::getModel('core/cookie')->delete('siliconrockstar_adminsinglesession');

                // destroy admin session (which redirects to login page)
                Mage::getSingleton('admin/session')->clear();

                // show message
                Mage::getSingleton('adminhtml/session')
                        ->addError(Mage::helper('adminhtml')->__('You have been logged out because another user has logged in with the same credentials.'));

            }
        }
    }

    /**
     * Check if in admin.
     * 
     * @return boolean
     */
    protected function isAdmin() {

        // test for admin session
        if (Mage::getSingleton('admin/session')->getUser()) {
            return true;
        } else {
            return false;
        }
    }

}
