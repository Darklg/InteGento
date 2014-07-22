<?php
class Krr_CatalogTools_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getProductHTML($_product) {
        return Mage::app()
            ->getLayout()
            ->createBlock('catalog/product_list')
            ->setTemplate('krr_catalogtools/product-item.phtml')
            ->setProduct($_product)
            ->toHtml();
    }
}
