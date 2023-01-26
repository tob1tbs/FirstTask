<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderUser;
use App\Models\OrderPayment;
use Carbon\Carbon;
use Auth;

class OrderRepository implements Interfaces\OrderRepositoryInterface
{

    protected $orderModel;
    protected $orderItemModel;
    protected $orderUserModel;
    protected $orderPaymentModel;
    protected $order;
    protected $orderItem;
    protected $orderUser;
    protected $orderPayment;

    public function __construct(
        Order $order,
        OrderItem $orderItem,
        OrderUser $orderUser,
        OrderPayment $orderPayment,
    )
    {
        $this->orderModel = $order;
        $this->orderItemModel = $orderItem;
        $this->orderUserModel = $orderUser;
        $this->orderPaymentModel = $orderPayment;
    }

    public function saveOrder($data) {
        if(!empty($data) && count($data) > 0) {

            $Order = Order::insertGetId([
                'total_price' => $data['total_price'],
                'deadline_date' => Carbon::now()->addDays(10)
            ]);

            foreach($data['product_id'] as $item) {
                OrderItem::create([
                    'product_id' => $item,
                    'order_id' => $Order
                ]);
            }

            OrderUser::create([
                'order_id' => $Order,
                'user_id' => Auth::user()->id,
            ]);
        }
    }

    public function getOrderData($order_id) {
        return Order::find($order_id);
    }

    public function getOrderPayments($order_id) {
        return OrderPayment::where('order_id', $order_id)->get();
    }

    public function saveOrderPayment($data) {
        return OrderPayment::create([
            'order_id' => $data['order_id'],
            'user_id' => Auth::user()->id,
            'total_price' => $data['amount'],
        ]);
    }

    public function saveOrderPaymentSuggest($data) {

        $OrderUserData = OrderUser::where('user_id', $data['user_id'])->where('order_id', $data['order_id'])->get();

        if(count($OrderUserData) == 0) {
            OrderUser::create([
                'order_id' => $data['order_id'],
                'user_id' => $data['user_id'],
            ]);
        }

        OrderPayment::create([
            'order_id' => $data['order_id'],
            'user_id' => $data['user_id'],
            'total_price' => $data['amount'],
        ]);
    }
}

?>