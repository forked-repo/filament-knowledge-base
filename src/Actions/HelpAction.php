<?php

namespace Guava\FilamentKnowledgeBase\Actions;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Guava\FilamentKnowledgeBase\Contracts\Documentable;
use Guava\FilamentKnowledgeBase\Facades\KnowledgeBase;
use Guava\FilamentKnowledgeBase\Filament\Pages\ViewDocumentation;

class HelpAction extends Action
{
    protected function setUp(): void
    {
    }

    public function generic(): HelpAction
    {
        return $this->label(__('filament-knowledge-base::translations.help'))
            ->icon('heroicon-o-question-mark-circle')
            ->iconSize('lg')
            ->color('gray')
            ->button()
        ;
    }

    public static function forDocumentable(Documentable $documentable): HelpAction
    {
        return HelpAction::make("help.{$documentable->getId()}")
            ->label($documentable->getTitle())
            ->icon($documentable->getIcon())
            ->when(
                Filament::getPlugin('guava::filament-knowledge-base')->hasModalPreviews(),
                fn (HelpAction $action) => $action
                    ->alpineClickHandler('$dispatch("open-modal", {id: "' . $documentable->getId() . '"})')
                    ->when(
                        Filament::getPlugin('guava::filament-knowledge-base')->hasSlideOverPreviews(),
                        fn (HelpAction $action) => $action->slideOver()
                    ),
                fn (HelpAction $action) => $action->url(ViewDocumentation::getUrl([
                    'record' => KnowledgeBase::documentable($documentable),
                ], panel: config('filament-knowledge-base.panel.id', 'knowledge-base')))
            )
        ;
    }
}
