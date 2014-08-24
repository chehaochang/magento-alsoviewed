<?php

class Yavva_AlsoViewed_Model_Observer
{
    protected function _getSession()
    {
        return Mage::getSingleton('alsoviewed/session');
    }

    /**
     * Add product relations to alsoviewed_log table
     *
     * @param Varien_Event_Observer $observer
     */
    public function catalogProductView(Varien_Event_Observer $observer)
    {
        $session   = $this->_getSession();
        $productId = $observer->getEvent()->getProduct()->getId();
        $viewedIds = $session->getViewedProductIds();

        if (!$viewedIds) {
            $viewedIds = array();
        }

        if ($productId && !in_array($productId, $viewedIds)) {
            if (count($viewedIds)) {
                Mage::getResourceModel('alsoviewed/log')->insertRelations(
                    $productId, $viewedIds
                );
            }
            $session->addViewedProductId($productId);
        }
    }

    /**
     * Move generated log records to alsoviewed_relation table
     */
    public function processLog()
    {
        $log  = Mage::getResourceModel('alsoviewed/log');
        $data = $log->getGroupedRelations();
        if ($data) {
            try {
                Mage::getResourceModel('alsoviewed/relation')->updateRelations($data);
                $log->clean();
            } catch (Zend_Db_Exception $e) {
                Mage::logException($e);
            }
        }
    }
}
