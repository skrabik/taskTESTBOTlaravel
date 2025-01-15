<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Keyboard\Keyboard;
use App\Models\Customer;

class WebHookController extends Controller
{
    public function __construct()
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
    }
    public function handler(Request $request)
    {
        $update = $this->telegram->getWebhookUpdate();

        if ($update->has('message')) {
            if ($update->getMessage()->has('photo')) {
                $this->handleImage($update);
            } else {
                $this->handleMessage($update);
            }
        } elseif ($update->has('callback_query')) {
            $this->handleButtonClick($update);
        } elseif ($update->has('my_chat_member')) {
            $this->updateActivity($update);
        }
    }

    private function handleMessage(Update $update)
    {
        $message = $update->getMessage();
        $chatId = $message->getChat()->getId();

        $text = $message->getText();

        // добавляем нового пользователя в Customers
        if ($text == '/start') {
            $this->addNewCustomer($update);
        }

        $responseText = "Ваш ID: $chatId\nВы написали: $text";

        $keyboard = Keyboard::make()
            ->inline()
            ->row(Keyboard::inlineButton(['text' => 'Нажми меня', 'callback_data' => 'button_click']));

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $responseText,
            'reply_markup' => $keyboard
        ]);
    }

    private function handleButtonClick(Update $update)
    {
        $callback = $update->getCallbackQuery();
        $message = $callback->getMessage();
        $telegram_user_id = $message->getChat()->getId();
        $customer = Customer::where('telegram_user_id', $telegram_user_id)->first();
        if ($customer) {
            $customer->count_of_clicks += 1;
            $customer->save();
        }

        $responseText = "Вы нажали на кнопку: $customer->count_of_clicks раз";

        $this->telegram->sendMessage([
            'chat_id' => $telegram_user_id,
            'text' => $responseText
        ]);
    }

    private function handleImage(Update $update)
    {
        $message = $update->getMessage();

        $photos = $message->getPhoto();
        $chatId = $message->getChat()->getId();

        $photo = $photos->last();

        $photo_name = $photo->getFileId();
        $photo_size = $photo->getFileSize();

        $responseText = "Вы отправили фотографию: $photo_name\nРазмером $photo_size байт";

        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $responseText
        ]);
    }

    private function addNewCustomer(Update $update) {
        $message = $update->getMessage();

        $user_name = $message->getFrom()->getUsername();
        $telegram_user_id = $message->getChat()->getId();

        $customer = Customer::where('telegram_user_id', $telegram_user_id)->first();

        // либо создаём нового пользователя, либо обновляем status
        if (!$customer) {
            Customer::create([
                'user_name' => $user_name,
                'telegram_user_id' => $telegram_user_id,
            ]);
        } else {
            if ($customer->status == 'kicked') {
                $this->updateStatus($customer, 'active');
            }
        }
    }

    private function updateActivity(Update $update) {
        $telegram_user_id =  $update->getMyChatMember()->getChat()->getId();
        $customer = Customer::where('telegram_user_id', $telegram_user_id)->first();
        $this->updateStatus($customer, 'kicked');
    }

    private function updateStatus(Customer $customer, $status) {
        $customer->status = $status;
        $customer->save();
    }
}
