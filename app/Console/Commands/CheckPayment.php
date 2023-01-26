<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderFine;

use Carbon\Carbon;

class CheckPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $order = Order::all()->load('payments');
        
        foreach($order as $item) {
            if(Carbon::parse($item->deadline_date) < Carbon::now()) {
                OrderFine::create([
                    'order_id' => $item->id,
                    'amount' => $item->total_price / 100 * 5,
                ]);
                Mail::send(['text'=>'mail'], $data, function($message) {
                     $message->to('abc@gmail.com', 'Tutorials Point')->subject('Laravel Basic Testing Mail');
                     $message->from('xyz@gmail.com','Virat Gandhi');
                });
            } elseif(
                Carbon::parse($item->deadline_date)->addDays() > Carbon::now(5) OR 
                Carbon::parse($item->deadline_date)->addDays(10) > Carbon::now() && $item->payment->sum('amount') > 0) {
                    User::find($item->payment->user_id)->update([
                        'status' => 0,
                    ]);
            }
        }
    }
}
