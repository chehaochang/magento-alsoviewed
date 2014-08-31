<?php

class Yavva_AlsoViewed_Block_Adminhtml_Relations extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'alsoviewed';
        $this->_controller = 'adminhtml_relations';
        $this->_headerText = Mage::helper('alsoviewed')->__('Relations');

        parent::__construct();

        $this->_removeButton('add');

        if ($this->_isAllowedAction('save')) {
            $this->_addButton('save', array(
                'label'   => Mage::helper('alsoviewed')->__('Save'),
                'onclick' => 'setLocation(\'' . $this->getUrl('*/*/saveMultiple') .'\')'
            ));
        }
    }

    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('yavva/alsoviewed/alsoviewed_relations/' . $action);
    }
}
