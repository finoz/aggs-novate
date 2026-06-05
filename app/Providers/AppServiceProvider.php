<?php

namespace App\Providers;

use App\Models\Page;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\MarkdownConverter;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $view->with('navPages', Page::inMenu()->orderBy('ordinamento')->get());
        });

        Str::macro('markdownLinks', function (string $text): string {
            $env = new Environment([
                'external_link' => [
                    'internal_hosts'     => [],
                    'open_in_new_window' => true,
                    'html_class'         => '',
                    'noopener'           => 'external',
                    'noreferrer'         => 'external',
                ],
            ]);
            $env->addExtension(new CommonMarkCoreExtension());
            $env->addExtension(new ExternalLinkExtension());

            return (new MarkdownConverter($env))->convert($text)->getContent();
        });
    }
}
