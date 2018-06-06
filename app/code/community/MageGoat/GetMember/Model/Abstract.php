<?php
/**
 * Magento
 *
 */

/**
 * GetMember abstract model
 * Provide date processing functionality
 */
abstract class MageGoat_GetMember_Model_Abstract extends Mage_Core_Model_Abstract
{

    /**
     * Load order by custom attribute value. Attribute value should be unique
     *
     * @param string $attribute
     * @param string $value
     * @return MageGoat_GetMember_Model_Member
     */
    public function loadByAttribute($attribute, $value)
    {
        $this->load($value, $attribute);
        return $this;
    }
}
