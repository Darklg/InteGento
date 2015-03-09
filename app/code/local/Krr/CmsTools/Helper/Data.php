<?php
class Krr_CmsTools_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getPage() {
        $pageId = Mage::app()->getRequest()->getParam('page_id', Mage::app()->getRequest()->getParam('id', false));
        $page = Mage::getModel('cms/page')->load($pageId);
        $pageContent = Mage::helper('cms')->getPageTemplateProcessor()->filter($page->getContent());
        $pageTitle = $page->getTitle();
        return array(
            'title' => $pageTitle,
            'content' => $pageContent
        );
    }
}
