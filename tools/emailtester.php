<?php

/**
 * Email tester v 0.7
 *
 * @author      Darklg <darklg.blog@gmail.com>
 * @copyright   Copyright (c) 2015 Darklg
 * @license     MIT
 */

/**
 * Please install this file in a subfolder in the root of your Magento install.
 */

$testerVersion = '0_7';
$cachePrefixKey = 'integento__emailtester__' . $testerVersion . '__';

$templates = array(
    'catalog_productalert_email_price_template' => array(
        'alertGrid' => 1,
        'customer' => 1,
    ) ,
    'catalog_productalert_email_stock_template' => array(
        'alertGrid' => 1,
        'customer' => 1,
    ) ,
    'contacts_email_email_template' => 'contacts_email_email_template',
    'customer_create_account_email_template' => array(
        'customer' => 1
    ) ,
    'customer_password_forgot_email_template' => array(
        'customer' => 1
    ) ,
    'newsletter_subscription_confirm_email_template' => 'newsletter_subscription_confirm_email_template',
    'newsletter_subscription_success_email_template' => 'newsletter_subscription_success_email_template',
    'newsletter_subscription_un_email_template' => 'newsletter_subscription_un_email_template',
    'sales_email_creditmemo_template' => array(
        'order' => 1
    ) ,
    'sales_email_order_comment_template' => array(
        'order' => 1
    ) ,
    'sales_email_order_template' => array(
        'order' => 1
    ) ,
    'sales_email_shipment_template' => array(
        'order' => 1
    ) ,
    'sendfriend_email_template' => 'sendfriend_email_template',
    'wishlist_email_email_template' => array(
        'customer' => 1
    ) ,
);

/* ----------------------------------------------------------
  Default page
---------------------------------------------------------- */

if (!isset($_GET['template']) || !array_key_exists($_GET['template'], $templates)) {

    echo '<!DOCTYPE HTML><html lang="en_EN"><head><meta charset="UTF-8" /><title>Mail preview</title></head><body><h1>Mail preview</h1>';
    echo '<ul>';
    foreach ($templates as $tpl_id => $template) {
        echo '<li><a href="emailtester.php?template=' . $tpl_id . '">' . $tpl_id . '</a></li>';
    }
    echo '</ul>';
    echo '</body></html>';
    return;
}

/* ----------------------------------------------------------
  Load Magento
---------------------------------------------------------- */

$tpl = $_GET['template'];

require_once '../app/Mage.php';
Mage::app();

$datas = array(
    'store' => Mage::app()->getStore()
);

/* ----------------------------------------------------------
  Templates vars
---------------------------------------------------------- */

/* New order template
 -------------------------- */

if (isset($templates[$tpl]['order'])) {

    $cacheId = $cachePrefixKey . 'sales_email_order_template';
    if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
        $_datas = unserialize($data);
        $datas['order'] = $_datas['order'];
        $datas['billing'] = $_datas['billing'];
        $datas['payment_html'] = $_datas['payment_html'];
    }
    else {

        /* Load latest order */
        $orders = Mage::getModel('sales/order')->getCollection()->setOrder('created_at', 'DESC')->setPageSize(1)->setCurPage(1);
        $order = Mage::getModel('sales/order')->load($orders->getFirstItem()->getEntityId());
        $storeId = $order->getStore()->getId();
        $paymentBlock = Mage::helper('payment')->getInfoBlock($order->getPayment());
        $paymentBlock->getMethod()->setStore($storeId);

        $datas['order'] = $order;
        $datas['billing'] = $order->getBillingAddress();
        $datas['payment_html'] = $paymentBlock->toHtml();
        Mage::app()->getCache()->save(serialize($datas) , $cacheId);
    }
}

/* Shipment
 -------------------------- */

if ($tpl == 'sales_email_shipment_template' && is_object($datas['order'])) {
    $datas['shipment'] = new Varien_Object();
    $datas['shipment']->setData(array(
        'increment_id' => '100000022'
    ));
}

/* Credit memo
 -------------------------- */

if ($tpl == 'sales_email_creditmemo_template' && is_object($datas['order'])) {

    $datas['creditmemo'] = new Varien_Object();
    $datas['creditmemo']->setData(array(
        'increment_id' => '100000022'
    ));

    $h = Mage::getResourceModel('sales/order_creditmemo_collection');
    $collection = $h->setPageSize(1)->setCurPage(1);
    foreach ($collection as $item) {
        $datas['creditmemo'] = $item;
        break;
    }
}

/* Contact template
 -------------------------- */

if ($tpl == 'contacts_email_email_template') {
    $datas['data'] = new Varien_Object();
    $datas['data']->setData(array(
        'name' => 'Jean-Michel Lorem',
        'email' => 'foo@bar.com',
        'telephone' => '123-4567890',
        'comment' => 'This is a test'
    ));
}

/* Product share
 -------------------------- */

if ($tpl == 'sendfriend_email_template') {
    $datas['name'] = 'Jean-Michel Lorem';
    $datas['product_url'] = 'https://github.com/Darklg';
    $datas['product_name'] = 'Barre de faire';
    $datas['product_image'] = 'http://placehold.it/75x75';
    $datas['message'] = 'The world needs dreamers and the world needs doers. But above all, the world needs dreamers who do — Sarah Ban Breathnach. Everyone who has ever taken a shower has had an idea. It’s the person who gets out of the shower, dries off, and does something about it that makes a difference — Nolan Bushnell. ';
}

/* New account & Forgot password
 -------------------------- */

if (isset($templates[$tpl]['customer'])) {

    $cacheId = $cachePrefixKey . 'customer_data';
    if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
        $_datas = unserialize($data);
        $datas['customer'] = $_datas['customer'];
        $datas['customerName'] = $_datas['customer']->getName();
    }
    else {
        $collection = Mage::getModel('customer/customer')->getCollection()->addAttributeToSelect('*')->addAttributeToSort('entity_id', 'desc')->setPageSize(1);
        $datas['customer'] = $collection->getFirstItem();
        $datas['customer']->setData('password', '****');
        $datas['customer']->setData('rp_token', md5('coucou'));
        $datas['customerName'] = $_datas['customer']->getName();
        Mage::app()->getCache()->save(serialize($datas) , $cacheId);
    }
}

/* Stock & price alert
 -------------------------- */

if (isset($templates[$tpl]['alertGrid'])) {
    $_nbProducts = 2;
    $_stockProducts = array();
    $_stockBlock = Mage::helper('productalert')->createBlock('productalert/email_stock');
    $products = Mage::getModel('catalog/product')->getCollection()->setPageSize($_nbProducts);
    foreach ($products as $prod) {
        $product = Mage::getModel('catalog/product')->load($prod->getId());
        $_stockProducts[$product->getId() ] = $product;
        if (count($_stockProducts) >= $_nbProducts) {
            break;
        }
    }
    foreach ($_stockProducts as $product) {
        $product->setCustomerGroupId($datas['customer']->getGroupId());
        $_stockBlock->addProduct($product);
    }

    $datas['alertGrid'] = $_stockBlock->toHtml();
}

/* Subscription confirmation
 -------------------------- */

if ($tpl == 'newsletter_subscription_confirm_email_template') {
    $collection = Mage::getModel('newsletter/subscriber')->getCollection()->setPageSize(1)->setOrder('subscriber_id', 'desc');
    foreach ($collection as $subscriber) {
        $datas['subscriber'] = $subscriber;
        break;
    }
}

/* Wishlist
 -------------------------- */

if ($tpl == 'wishlist_email_email_template') {

    // Get latest shared wishlist ID
    $resource = Mage::getSingleton('core/resource');
    $readConnection = $resource->getConnection('core_read');
    $table = $resource->getTableName('wishlist/wishlist');
    $wishlist_id = $readConnection->fetchCol('SELECT wishlist_id FROM ' . $table . ' WHERE shared=1 ORDER BY wishlist_id DESC LIMIT 0,1');

    if (empty($wishlist_id)) {
        $wishlist_id = array(
            0
        );
    }

    // Register wishlist
    Mage::register('wishlist', Mage::getSingleton('wishlist/wishlist')->load($wishlist_id[0]));

    // Load wishlist vars
    $datas['salable'] = 'yes';
    $datas['addAllLink'] = Mage::getUrl('*/shared/allcart', array(
        'code' => 'foo'
    ));
    $datas['viewOnSiteLink'] = Mage::getUrl('*/shared/index', array(
        'code' => 'foo'
    ));
    $datas['message'] = 'Buy this';
    $datas['items'] = Mage::app()->getLayout()->createBlock('wishlist/share_email_items')->toHtml();
}

/* ----------------------------------------------------------
  Display template
---------------------------------------------------------- */

header('Content-Type: text/html; charset=utf-8');

echo Mage::getModel('core/email_template')->load(3)->loadDefault($tpl)->getProcessedTemplate($datas);
