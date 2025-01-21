<?php

namespace App\Filament\Resources\ArticleTypeResource\Pages;

use App\Filament\Resources\ArticleTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArticleTypes extends ListRecords
{
    protected static string $resource = ArticleTypeResource::class;   
    protected static ?string $navigationLabel = '类型';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
