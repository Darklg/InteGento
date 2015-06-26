<?php

/**
 * Email tester v 0.4
 *
 * @author      Darklg <darklg.blog@gmail.com>
 * @copyright   Copyright (c) 2015 Darklg
 * @license     MIT
 */

/**
 * Please install this file in a subfolder in the root of your Magento install.
 */

$testerVersion = '0_4';
$cachePrefixKey = 'integento__emailtester__' . $testerVersion . '__';

$templates = array(
    'contacts_email_email_template' => 'contacts_email_email_template',
    'customer_create_account_email_template' => 'customer_create_account_email_template',
    'customer_password_forgot_email_template' => 'customer_password_forgot_email_template',
    'newsletter_subscription_confirm_email_template' => 'newsletter_subscription_confirm_email_template',
    'newsletter_subscription_success_email_template' => 'newsletter_subscription_success_email_template',
    'newsletter_subscription_un_email_template' => 'newsletter_subscription_un_email_template',
    'sales_email_order_comment_template' => 'sales_email_order_comment_template',
    'sales_email_order_template' => 'sales_email_order_template',
    'sales_email_shipment_template' => 'sales_email_shipment_template',
    'sendfriend_email_template' => 'sendfriend_email_template',
);

/* ----------------------------------------------------------
  Default page
---------------------------------------------------------- */

if (!isset($_GET['template']) || !in_array($_GET['template'], $templates)) {

    echo '<!DOCTYPE HTML><html lang="en_EN"><head><meta charset="UTF-8" /><title>Mail preview</title></head><body><h1>Mail preview</h1>';
    echo '<ul>';
    foreach ($templates as $template) {
        echo '<li><a href="emailtester.php?template=' . $template . '">' . $template . '</a></li>';
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

if ($tpl == 'sales_email_order_template' || $tpl == 'sales_email_order_comment_template' || $tpl == 'sales_email_shipment_template') {

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

if($tpl == 'sales_email_shipment_template' && is_object($datas['order'])) {
    $datas['shipment'] = new Varien_Object();
    $datas['shipment']->setData(array(
        'increment_id' => '100000022'
    ));
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

if ($tpl == 'customer_create_account_email_template' || $tpl == 'customer_password_forgot_email_template') {

    $cacheId = $cachePrefixKey . 'customer_data';
    if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
        $_datas = unserialize($data);
        $datas['customer'] = $_datas['customer'];
    }
    else {
        $collection = Mage::getModel('customer/customer')->getCollection()->addAttributeToSelect('*')->addAttributeToSort('entity_id', 'desc')->setPageSize(1);
        $datas['customer'] = $collection->getFirstItem();
        $datas['customer']->setData('password', '****');
        $datas['customer']->setData('rp_token', md5('coucou'));
        Mage::app()->getCache()->save(serialize($datas) , $cacheId);
    }
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

/* ----------------------------------------------------------
  Display template
---------------------------------------------------------- */

header('Content-Type: text/html; charset=utf-8');

echo Mage::getModel('core/email_template')->load(3)->loadDefault($tpl)->getProcessedTemplate($datas);
