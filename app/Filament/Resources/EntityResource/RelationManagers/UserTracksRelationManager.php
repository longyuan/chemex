<?php

namespace App\Filament\Resources\EntityResource\RelationManagers;

use App\Filament\Actions\EntityAction;
use App\Models\EntityUserTrack;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserTracksRelationManager extends RelationManager
{
    protected static string $relationship = 'userTracks';

    protected static ?string $title = '管理者';

    protected static ?string $icon = 'heroicon-o-user';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->badge()
                    ->color(function (EntityUserTrack $entityUserTrack) {
                        if ($entityUserTrack->getAttribute('deleted_at')) {
                            return 'danger';
                        } else {
                            return 'success';
                        }
                    })
                    ->searchable()
                    ->label('管理者'),
                Tables\Columns\TextColumn::make('comment')
                    ->label('分配说明'),
                Tables\Columns\TextColumn::make('delete_comment')
                    ->label('解除分配说明'),
                Tables\Columns\TextColumn::make('created_at')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('分配时间'),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('解除分配时间'),
            ])
            ->filters([

            ])
            ->headerActions([
                // 分配管理者
                EntityAction::createUserTrack($this->getOwnerRecord())
                    ->visible(function () {
                        return !$this->getOwnerRecord()->userTracks()->count();
                    }),
                // 解除管理者
                EntityAction::deleteUserTrack($this->getOwnerRecord())
                    ->visible(function () {
                        return $this->getOwnerRecord()->userTracks()->count();
                    }),
            ])
            ->actions([

            ])
            ->bulkActions([

            ])
            ->emptyStateActions([

            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->orderByDesc('id')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
