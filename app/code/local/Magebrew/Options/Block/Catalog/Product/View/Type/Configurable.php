<?php

/**
 * Show Out of stock options on product page
 *
 * @category   Magebrew
 * @author     Magebrew <magebrew.com>
 */
class Magebrew_Options_Block_Catalog_Product_View_Type_Configurable extends Mage_Catalog_Block_Product_View_Type_Configurable
{
    /**
     * Get Allowed Products
     *
     * @return array
     */
    public function getAllowProducts()
    {
        if (!$this->hasAllowProducts()) {
            $skipSaleableCheck = Mage::helper('catalog/product')->getSkipSaleableCheck();
            Mage::helper('catalog/product')->setSkipSaleableCheck(true);
            parent::getAllowProducts();
            Mage::helper('catalog/product')->setSkipSaleableCheck($skipSaleableCheck);
        }
        return $this->getData('allow_products');
    }

    /**
     * Composes configuration for js
     * overridden to support Out Of Stock labels
     * @return string
     */
    public function getJsonConfig()
    {
        $configEncoded = parent::getJsonConfig();
        $config = Mage::helper('core')->jsonDecode($configEncoded);
        $unSaleable = array();
        foreach ($this->getAllowProducts() as $product) {
            if (!$product->isSaleable()) {
                $unSaleable[] = $product->getId();
            }
        }
        $config['unsaleable'] = $unSaleable;
        $config = Mage::helper('core')->jsonEncode($config);

        return $config;
    }
}