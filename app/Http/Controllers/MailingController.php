<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Telegram\Bot\Api;

class MailingController extends Controller
{
    public function __construct()
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
    }

    public function sendAll (Request $request)
    {
        $message = $request->input('message');

        Customer::where('status', 'active')->chunk(100, function ($customers) use ($message) {
            foreach ($customers as $customer) {
                $this->send($customer->telegram_user_id, $message);
            }
        });

        return redirect('/admin/mailing');
    }

    public function send ($user_telegram_id, $message)
    {
        return $this->telegram->sendMessage([
            'chat_id' => $user_telegram_id,
            'text' => $message
        ]);
    }
}
