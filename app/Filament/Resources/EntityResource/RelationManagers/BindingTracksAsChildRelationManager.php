<?php

namespace App\Filament\Resources\EntityResource\RelationManagers;

use App\Models\Entity;
use App\Models\EntityBindingTrack;
use App\Utils\NotificationUtil;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BindingTracksAsChildRelationManager extends RelationManager
{
    protected static string $relationship = 'bindingTracksAsChild';

    protected static ?string $title = '所属实体';

    protected static ?string $icon = 'heroicon-m-device-tablet';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('entityAsChild.asset_number')
                    ->label('资产编号'),
                Tables\Columns\TextColumn::make('created_at')
                    ->size(Tables\Columns\TextColumn\TextColumnSize::ExtraSmall)
                    ->label('绑定时间'),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->size(Tables\Columns\TextColumn\TextColumnSize::ExtraSmall)
                    ->label('解绑时间'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('绑定至父实体')
                    ->form([
                        Select::make('entity')
                            ->options(Entity::query()
                                ->doesntHave('bindingTracksAsChild')
                                ->pluck('name', 'id'))
                            ->label('实体')
                            ->required(),
                    ])
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('解除绑定')
                    ->action(function (EntityBindingTrack $entityBindingTrack) {
                        if ($entityBindingTrack->delete()) {
                            NotificationUtil::make(true, '已解除子实体绑定');
                        } else {
                            NotificationUtil::make(false, '解除失败');
                        }
                    })
                    ->visible(function (EntityBindingTrack $entityBindingTrack) {
                        return $entityBindingTrack->getAttribute('deleted_at') == null;
                    })
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ])
            ->emptyStateActions([
//                Tables\Actions\CreateAction::make(),
            ])
            ->modifyQueryUsing(function (Builder $query) {
//                dd($query->get());
//                dd($this->getOwnerRecord()->bindingTracksAsChild);
                return $query
                    ->where('entity_id', '!=', $this->getOwnerRecord()->getKey())
                    ->orderByDesc('id')
                    ->withoutGlobalScopes([
                        SoftDeletingScope::class,
                    ]);
            });
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }
}
