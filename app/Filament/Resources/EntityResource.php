<?php

namespace App\Filament\Resources;

use App\Filament\Actions\EntityAction;
use App\Filament\Resources\EntityResource\Pages;
use App\Filament\Resources\EntityResource\RelationManagers;
use App\Models\Entity;
use App\Models\EntityCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EntityResource extends Resource
{
    protected static ?string $model = Entity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = '实体';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asset_number')
                    ->searchable()
                    ->label('资产编号'),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('分类'),
                Tables\Columns\TextColumn::make('users.name')
                    ->searchable()
                    ->label('管理者'),
                Tables\Columns\TextColumn::make('specification')
                    ->searchable()
                    ->label('规格'),
                Tables\Columns\TextColumn::make('warranty_date')
                    ->badge()
                    ->color(function (Entity $entity) {
                        if ($entity->getAttribute('warranty_date') >= now()) {
                            return 'success';
                        }
                        return 'danger';
                    })
                    ->date('Y 年 m 月 d 日')
                    ->alignRight()
                    ->label('保修日期'),
                Tables\Columns\TextColumn::make('entitiesCount')
                    ->badge()
                    ->color(function (Entity $entity) {
                        if ($entity->getAttribute('entitiesCount') > 0) {
                            return 'primary';
                        }
                        return 'gray';
                    })
                    ->alignRight()
                    ->label('子实体'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    // 分配管理者
                    EntityAction::createUserTrack()
                        ->visible(function (Entity $entity) {
                            return !$entity->userTracks()->count();
                        }),
                    // 解除管理者
                    EntityAction::deleteUserTrack()
                        ->visible(function (Entity $entity) {
                            return $entity->userTracks()->count();
                        }),
                    EntityAction::createBindingTrack()
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('asset_number')
                    ->maxLength(255)
                    ->label('资产编号')
                    ->required(),
                Forms\Components\Select::make('category_id')
                    ->options(EntityCategory::query()->pluck('name', 'id'))
                    ->label('分类')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->maxLength(255)
                    ->label('名称'),
                Forms\Components\TextInput::make('sn')
                    ->maxLength(255)
                    ->label('序列号'),
                Forms\Components\TextInput::make('specification')
                    ->maxLength(255)
                    ->label('规格'),
                Forms\Components\DatePicker::make('warranty_date')
                    ->label('保修日期'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BindingTracksAsChildRelationManager::class,
            RelationManagers\BindingTracksRelationManager::class,
            RelationManagers\UserTracksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEntities::route('/'),
            'create' => Pages\CreateEntity::route('/create'),
            'edit' => Pages\EditEntity::route('/{record}/edit'),
        ];
    }
}
