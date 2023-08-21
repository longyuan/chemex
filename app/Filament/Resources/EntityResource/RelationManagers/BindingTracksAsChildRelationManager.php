<?php

namespace App\Filament\Resources\EntityResource\RelationManagers;

use App\Filament\Actions\EntityAction;
use App\Models\EntityBindingTrack;
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
                // 绑定至父实体
                EntityAction::createBindingTrackAsChild($this->getOwnerRecord()->getKey())
                    ->visible(function (EntityBindingTrack $entityBindingTrack) {
                        return $entityBindingTrack->getAttribute('deleted_at') == null;
                    }),
                // 解除绑定至父实体
                EntityAction::deleteBindingTrackAsChild($this->getOwnerRecord()->getKey())
                    ->visible(function (EntityBindingTrack $entityBindingTrack) {
                        return !$entityBindingTrack->getAttribute('deleted_at') == null;
                    }),
            ])
            ->actions([

            ])
            ->bulkActions([

            ])
            ->emptyStateActions([
//
            ])
            ->modifyQueryUsing(function (Builder $query) {
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
