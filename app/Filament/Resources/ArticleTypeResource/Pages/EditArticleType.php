<?php

namespace App\Filament\Resources\ArticleTypeResource\Pages;

use App\Filament\Resources\ArticleTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArticleType extends EditRecord
{
    protected static string $resource = ArticleTypeResource::class;

    
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
