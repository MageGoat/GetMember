<?php
/**
 * Magento
 *
 *
 */
class  MageGoat_GetMember_Model_Resource_Point extends Mage_Core_Model_Resource_Db_Abstract
{
	/**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('getmember/point', 'point_id');
    }
    
    /**
     * Prepare data for save
     *
     * @param Mage_Core_Model_Abstract $object
     * @return array
     */
    protected function _prepareDataForSave(Mage_Core_Model_Abstract $object)
    {
        $currentTime = Varien_Date::now();
        if ((!$object->getId() || $object->isObjectNew()) && !$object->getCreatedAt()) {
            $object->setCreatedAt($currentTime);
        }
        $object->setUpdatedAt($currentTime);
        $data = parent::_prepareDataForSave($object);
        return $data;
    }
}