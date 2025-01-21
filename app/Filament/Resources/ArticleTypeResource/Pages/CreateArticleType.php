<?php

namespace App\Filament\Resources\ArticleTypeResource\Pages;

use App\Filament\Resources\ArticleTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateArticleType extends CreateRecord
{
    protected static string $resource = ArticleTypeResource::class;
}
