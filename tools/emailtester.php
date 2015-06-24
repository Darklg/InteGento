<?php

/**
 * Email tester v 0.1.1
 *
 * @author      Darklg <darklg.blog@gmail.com>
 * @copyright   Copyright (c) 2015 Darklg
 * @license     MIT
 */

$templates = array(
    'sales_email_order_template' => 'sales_email_order_template',
    'contacts_email_email_template' => 'contacts_email_email_template',
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

if ($tpl == 'sales_email_order_template') {

    /* Load latest order */
    $orders = Mage::getModel('sales/order')->getCollection()->setOrder('created_at', 'DESC')->setPageSize(1)->setCurPage(1);
    $order = Mage::getModel('sales/order')->load($orders->getFirstItem()->getEntityId());
    $storeId = $order->getStore()->getId();
    $paymentBlock = Mage::helper('payment')->getInfoBlock($order->getPayment());
    $paymentBlock->getMethod()->setStore($storeId);
    $datas['order'] = $order;
    $datas['billing'] = $order->getBillingAddress();
    $datas['payment_html'] = $paymentBlock->toHtml();
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

/* ----------------------------------------------------------
  Display template
---------------------------------------------------------- */

echo Mage::getModel('core/email_template')->load(3)->loadDefault($tpl)->getProcessedTemplate($datas);
