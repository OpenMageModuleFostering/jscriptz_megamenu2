<?php 
class JScriptz_MegaMenu2_Model_SystemSettings
{
    public function toOptionArray()
    {
        return array(
            array('value'=>1, 'label'=>Mage::helper('megamenu2')->__('Enabled')),
            array('value'=>0, 'label'=>Mage::helper('megamenu2')->__('Disabled')),
                     
        );
    }

}
?>
