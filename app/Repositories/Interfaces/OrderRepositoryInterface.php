<?php

namespace App\Repositories\Interfaces;

interface OrderRepositoryInterface
{

    public function saveOrder($data);

    public function getOrderData($order_id);

    public function getOrderPayments($order_data);
    
    public function saveOrderPayment($order_data);

    public function saveOrderPaymentSuggest($order_data);
}