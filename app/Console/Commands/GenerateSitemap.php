<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Crawler\Crawler;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SitemapGenerator::create(config('app.url'))->configureCrawler(function (Crawler $crawler) {
            $crawler->setMaximumDepth(3);
        })->writeToFile(public_path('sitemap.xml'));
    }
}
