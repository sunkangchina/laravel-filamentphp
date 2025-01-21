<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleTypeResource\Pages;
use App\Filament\Resources\ArticleTypeResource\RelationManagers;
use App\Models\ArticleType;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification; 

class ArticleTypeResource extends Resource
{
    protected static ?string $navigationGroup = '文章管理';   

    protected static ?string $navigationLabel = '类型';

    protected static ?string $label = '文章类型';

    protected static ?string $model = ArticleType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack'; 

     
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //title
                Forms\Components\TextInput::make('title')->label('标题')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //title
                Tables\Columns\TextColumn::make('title')->label('标题'),  
            ])
            ->filters([ 
               
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->before(function ( Tables\Actions\DeleteAction $action, ArticleType $record) {
                    //判断有没有文章
                    if ($record->articles()->count() > 0) { 
                        //弹出错误提示
                        Notification::make()
                                ->danger()
                                ->title('删除文章分类'.$record->title.'失败')
                                ->body('该分类下有文章，无法删除')
                                ->send(); 
                        $action->cancel(); 
                    } else{
                        //发送通知
                        Notification::make()
                        ->success()
                        ->title('删除文章分类'.$record->title.'成功')
                        ->body('删除文章分类'.$record->title.'成功')
                        ->send(); 
                    } 
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticleTypes::route('/'),
            'create' => Pages\CreateArticleType::route('/create'),
            'edit' => Pages\EditArticleType::route('/{record}/edit'),
        ];
    }
}
