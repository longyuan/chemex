<?php

namespace App\Filament\Actions;

use App\Models\VendorContactTrack;
use App\Utils\NotificationUtil;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;

class VendorAction
{
    /**
     * 创建联系人按钮.
     *
     * @param string $vendor_id
     * @return Action
     */
    public static function createContactTrack(string $vendor_id): Action
    {
        return Action::make('添加联系人')
            ->form([
                TextInput::make('name')
                    ->maxLength(255)
                    ->required()
                    ->label('名称'),
                TextInput::make('phone_number')
                    ->maxLength(255)
                    ->required()
                    ->label('电话'),
                TextInput::make('email')
                    ->maxLength(255)
                    ->label('邮箱')
            ])
            ->action(function (array $data) use ($vendor_id) {
                $vendor_contact_track = VendorContactTrack::query()
                    ->create([
                        'vendor_id' => $vendor_id,
                        'name' => $data['name'],
                        'phone_number' => $data['phone_number'],
                        'email' => $data['email'],
                    ]);
                if ($vendor_contact_track) {
                    NotificationUtil::make(true, '已添加联系人');
                } else {
                    NotificationUtil::make(false, '添加失败');
                }
            });
    }
}
