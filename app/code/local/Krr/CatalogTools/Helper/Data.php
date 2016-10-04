<?php
class Krr_CatalogTools_Helper_Data extends Mage_Core_Helper_Abstract {
    /**
     * Get HTML for Product item
     * @param  object $_product     Magento Product item
     * @param  string $block_type   Block type
     * @param  string $template     Custom template
     * @return string               HTML to display
     */
    public function getProductHTML($_product, $block_type = 'catalog/product_list', $template = 'krr_catalogtools/product-item.phtml') {
        return $this->getLayout()
            ->createBlock($block_type)
            ->setProduct($_product)
            ->setTemplate($template)
            ->toHtml();
    }
}
