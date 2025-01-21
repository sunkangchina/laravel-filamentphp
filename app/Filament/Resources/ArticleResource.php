<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope; 

class ArticleResource extends Resource 
{
    protected static ?string $navigationGroup = '文章管理';  

    protected static ?string $navigationLabel = '文章';

    protected static ?string $label = '文章';

    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
 
    protected static ?array $permissionPrefixes = [
        'resource' => [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'replicate',
            'reorder',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'lock'
        ], 
    ];
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //title
                Forms\Components\TextInput::make('title')->label('标题')
                    ->required()
                    ->maxLength(255),
                //article_type_id
                Forms\Components\Select::make('type_id')->label('分类')
                    ->relationship('type', 'title')
                    ->required(),
                
                //content
                Forms\Components\MarkdownEditor::make('body')->label('内容')
                    ->required(),
                
                //status
                Forms\Components\Select::make('status')->label('状态')
                    ->options([
                        'published' => '已发布',
                        'draft' => '草稿', 
                    ])
                    ->required(), 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //title
                Tables\Columns\TextColumn::make('title')->label('标题'), 
                //type
                Tables\Columns\TextColumn::make('type.title')->label('分类'),
                //status
                Tables\Columns\TextColumn::make('status_label')->label('状态'),
                //created_at
                Tables\Columns\TextColumn::make('created_at')->label('创建时间'), 
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'published' => '已发布',
                    'draft' => '草稿', 
                ])->label('状态'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    } 
    
}
