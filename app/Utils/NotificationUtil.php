<?php

namespace App\Utils;

use Filament\Notifications\Notification;

class NotificationUtil
{
    public static function make(bool $result, string $body): void
    {
        $notification = Notification::make()
            ->body($body);
        if ($result) {
            $notification->title('成功');
            $notification->success();
        } else {
            $notification->title('失败');
            $notification->danger();
        }
        $notification->send();
    }
}
