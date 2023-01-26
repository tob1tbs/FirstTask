<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\UserRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;

use Auth;
use Validator;
use Hash;
use Response;

class MainController extends Controller
{
    //

    public function __construct(
        UserRepository $userRepository,
        OrderRepository $orderRepository,
        ProductRepository $productRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    public function actionMainIndex(Request $request) {
        if(Auth::check()) {
            echo "Logined";
        } else {
            echo "Pleace login";
        }
    }

    public function actionMainLogin(Request $request) {
        if($request->isMethod('POST')) {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return Response::json(['status' => false, 'message' => $validator->getMessageBag()->toArray()], 200);
            } else {
                if(Auth::attempt(['email' => $request->email, 'password' => $request->password], 1)) {
                    return Response::json(['status' => true, 'message' => 'Auth success']);
                } else {
                    return Response::json(['status' => false, 'message' => 'Auth error']);
                }
            }
        }
    }

    public function actionMainRegister(Request $request) {
        if($request->isMethod('POST')) {

            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'lastname' => 'required|max:255',
                'email' => 'required|unique:users|max:255|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
                'phone' => 'required|unique:users|max:255',
                'password' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return Response::json(['status' => false, 'message' => $validator->getMessageBag()->toArray()], 200);
            } else {
                $data = [
                    'name' => $request->name,
                    'lastname' => $request->lastname,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                ];

                $this->userRepository->saveUser($data);

                return Response::json(['status' => true, 'message' => 'Registration success']);
            }

        }
    }

    public function actionMakeOrder(Request $request) {
        if($request->isMethod('POST')) {
            if(Auth::check()) {
                $validator = Validator::make($request->all(), [
                    'product_id|numeric' => 'required',
                ]);

                if ($validator->fails()) {
                    return Response::json(['status' => false, 'message' => $validator->getMessageBag()->toArray()], 200);
                } else {

                    $data = [
                        'product_id' => $request->product_id,
                        'total_price' => $this->productRepository->getProductByIds($request->product_id)->sum('price')
                    ];

                    return $this->orderRepository->saveOrder($data);
                }
            } else {
                echo "Pleace login";
            }
        }
    }

    public function actionMakeOrderPayment(Request $request) {
        if($request->isMethod('POST')) {
            if(Auth::check()) {
                $validator = Validator::make($request->all(), [
                    'order_id' => 'required|numeric',
                    'amount' => 'required|numeric',
                ]);

                if ($validator->fails()) {
                    return Response::json(['status' => false, 'message' => $validator->getMessageBag()->toArray()], 200);
                } else {

                    $order_data = $this->orderRepository->getOrderData($request->order_id);
                    $order_payments = $this->orderRepository->getOrderPayments($request->order_id);

                    if($order_data['total_price'] < $request->amount) {
                        return Response::json(['status' => false, 'message' => 'Incorect amount']);
                    } elseif($order_payments->sum('total_price') + $request->amount > $order_data['total_price']) {
                        return Response::json(['status' => false, 'message' => 'Incorect amount, need to pay '. $order_data['total_price'] - $order_payments->sum('total_price'),]);
                    } else {
                        $data = [
                            'order_id' => $request->order_id,
                            'amount' => $request->amount,
                        ];

                        $this->orderRepository->saveOrderPayment($data);
                    }
                }
            } else {
                echo "Pleace login";
            }
        }
    }

    public function actionMakeOrderPaymentSuggest(Request $request) {
        if($request->isMethod('POST')) {
            if(Auth::check()) {
                $validator = Validator::make($request->all(), [
                    'order_id' => 'required|numeric',
                    'amount' => 'required|numeric',
                    'user_id' => 'required|numeric',
                ]);

                if ($validator->fails()) {
                    return Response::json(['status' => false, 'message' => $validator->getMessageBag()->toArray()], 200);
                } else {

                    $order_data = $this->orderRepository->getOrderData($request->order_id);
                    $order_payments = $this->orderRepository->getOrderPayments($request->order_id);

                    if($order_data['total_price'] < $request->amount) {
                        return Response::json(['status' => false, 'message' => 'Incorect amount']);
                    } elseif($order_payments->sum('total_price') + $request->amount > $order_data['total_price']) {
                        return Response::json(['status' => false, 'message' => 'Incorect amount, need to pay '. $order_data['total_price'] - $order_payments->sum('total_price'),]);
                    } else {
                        $data = [
                            'order_id' => $request->order_id,
                            'amount' => $request->amount,
                            'user_id' => $request->user_id,
                        ];

                        $this->orderRepository->saveOrderPaymentSuggest($data);
                    }
                }
            } else {
                echo "Pleace login";
            }
        }
    } 
}
