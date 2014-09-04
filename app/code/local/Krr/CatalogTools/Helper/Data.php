<?php
class Krr_CatalogTools_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getProductHTML($_product, $block_type = 'catalog/product_list') {
        $block = Mage::app()
            ->getLayout()
            ->createBlock($block_type)
            ->setTemplate('krr_catalogtools/product-item.phtml');

        $block->setProduct($_product);

        return $block->toHtml();
    }
}
