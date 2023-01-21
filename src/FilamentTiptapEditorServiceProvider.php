<?php

namespace FilamentTiptapEditor;

use Livewire\Livewire;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Illuminate\Foundation\Vite;
use Illuminate\Support\HtmlString;
use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Illuminate\Contracts\Support\Htmlable;

class FilamentTiptapEditorServiceProvider extends PluginServiceProvider
{
    protected array $styles = [
        'filament-tiptap-editor-styles' => __DIR__ . '/../resources/dist/filament-tiptap-editor.css',
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-tiptap-editor')
            ->hasConfigFile()
            ->hasAssets()
            ->hasTranslations()
            ->hasViews();
    }

    public function getBeforeCoreScripts(): array
    {
        $extensions = config('filament-tiptap-editor.extensions') ?? [];

        $extensionSources = [];
        foreach ($extensions as $extension) {
            if (Str::of($extension['source'])->startsWith(['http', '\\\\'])) {
                $extensionSources[] = $extension['source'];
            } else {
                $extensionSources[] = $extension['builder'] === 'mix'
                    ? mix($extension['source'])
                    : \Illuminate\Support\Facades\Vite::asset($extension['source']);
            }
        }

        return [
            'filament-tiptap-editor-scripts' => __DIR__ . '/../resources/dist/filament-tiptap-editor.js',
            ...$extensionSources,
        ];
    }

    public function boot()
    {
        parent::boot();

        if ($theme = $this->getTiptapEditorStylesLink()) {
            Filament::registerRenderHook(
                'styles.end',
                fn (): string => $theme,
            );
        }
    }

    public function getTiptapEditorStylesLink(): ?Htmlable
    {
        $themeFile = config('filament-tiptap-editor.theme_file');

        if ($themeFile) {
            $builder = config('filament-tiptap-editor.theme_builder');

            if ($builder == 'vite') {
                $theme = app(Vite::class)($themeFile, config('filament-tiptap-editor.theme_folder'));
            } else {
                $theme = mix($themeFile);
            }

            if (Str::of($theme)->contains('<link')) {
                return $theme instanceof Htmlable ? $theme : new HtmlString($theme);
            }

            $url = $theme ?? route('filament.asset', [
                'id' => get_asset_id($theme),
                'file' => $theme,
            ]);

            return new HtmlString("<link rel=\"stylesheet\" href=\"{$url}\" />");
        }

        return null;
    }
}
