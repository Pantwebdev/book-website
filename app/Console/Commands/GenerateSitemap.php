<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ChildSubcategory;

class GenerateSitemap extends Command
{
    protected $signature = 'generate:sitemap';
    protected $description = 'Generate Sitemap Automatically';

    public function handle()
    {
        $sitemap = Sitemap::create();

        // Static pages
        $sitemap->add(Url::create('/')->setPriority(1.0));
        $sitemap->add(Url::create('/shop')->setPriority(0.9));

        // Categories
        Category::chunk(100, function ($categories) use ($sitemap) {
            foreach ($categories as $category) {
                if ($category->slug) {
                    $sitemap->add(
                        Url::create("/{$category->slug}")
                            ->setPriority(0.8)
                            ->setLastModificationDate($category->updated_at)
                    );
                }
            }
        });

        // Subcategories
        Subcategory::with('category')->chunk(100, function ($subs) use ($sitemap) {
            foreach ($subs as $sub) {
                if ($sub->slug && $sub->category) {
                    $sitemap->add(
                        Url::create("/{$sub->category->slug}/{$sub->slug}")
                            ->setPriority(0.7)
                            ->setLastModificationDate($sub->updated_at)
                    );
                }
            }
        });

        // Child Categories
        ChildSubcategory::with(['category', 'subcategory'])->chunk(100, function ($childs) use ($sitemap) {
            foreach ($childs as $child) {
                if ($child->slug && $child->category && $child->subcategory) {
                    $sitemap->add(
                        Url::create("/{$child->category->slug}/{$child->subcategory->slug}/{$child->slug}")
                            ->setPriority(0.6)
                            ->setLastModificationDate($child->updated_at)
                    );
                }
            }
        });

        // Products
        Product::chunk(100, function ($products) use ($sitemap) {
            foreach ($products as $product) {
                if ($product->slug) {
                    $sitemap->add(
                        Url::create("/product/{$product->slug}")
                            ->setPriority(0.7)
                            ->setLastModificationDate($product->updated_at)
                    );
                }
            }
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap Generated Successfully!');
    }
}