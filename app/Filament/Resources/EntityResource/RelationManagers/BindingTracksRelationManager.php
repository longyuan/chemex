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

class BindingTracksRelationManager extends RelationManager
{
    protected static string $relationship = 'bindingTracks';

    protected static ?string $title = '子实体';

    protected static ?string $icon = 'heroicon-m-device-tablet';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('entity.asset_number')
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
                EntityAction::createBindingTrack($this->getOwnerRecord()->getKey()),
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\ViewAction::make(),
                EntityAction::deleteBindingTrack()
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
            ->modifyQueryUsing(fn(Builder $query) => $query
                ->where('child_entity_id', '!=', $this->getOwnerRecord()->getKey())
                ->orderByDesc('id')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }
}
