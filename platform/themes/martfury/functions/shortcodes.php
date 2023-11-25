<?php

use Botble\Ads\Repositories\Interfaces\AdsInterface;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Botble\Faq\Repositories\Interfaces\FaqCategoryInterface;
use Botble\Theme\Supports\ThemeSupport;
use Theme\Martfury\Http\Resources\ProductCategoryResource;
use Theme\Martfury\Http\Resources\ProductCollectionResource;
use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Botble\Blog\Repositories\Interfaces\PostInterface;
app()->booted(function () {
    ThemeSupport::registerGoogleMapsShortcode();
    ThemeSupport::registerYoutubeShortcode();



    if (is_plugin_active('ecommerce')) {
        add_shortcode(
            'featured-product-categories',
            __('Featured Product Categories'),
            __('Featured Product Categories'),
            function ($shortCode) {
                return Theme::partial('short-codes.featured-product-categories', [
                    'title' => $shortCode->title,
                ]);
            }
        );

        shortcode()->setAdminConfig('featured-product-categories', function ($attributes) {
            return Theme::partial('short-codes.featured-product-categories-admin-config', compact('attributes'));
        });

        add_shortcode('featured-products', __('Featured products'), __('Featured products'), function ($shortCode) {
            return Theme::partial('short-codes.featured-products', [
                'title' => $shortCode->title,
                'limit' => $shortCode->limit,
            ]);
        });


        add_shortcode('recent-posts', __('Recent posts'), __('Recent posts'), function ( $shortcode) {
            $posts = get_latest_posts(7, [], ['slugable']);

            return Theme::partial('short-codes.recent-posts', ['title' => $shortcode->title, 'posts' => $posts]);
        });

        shortcode()->setAdminConfig('recent-posts', function (array $attributes, string $content) {
            return Theme::partial('short-codes.recent-posts-admin-config', compact('attributes', 'content'));
        });
        
        add_shortcode(
            'featured-categories-posts',
            __('Featured categories posts'),
            __('Featured categories posts'),
            function ( $shortcode) {
                $with = [
                    'slugable',
                    'posts' => function (BelongsToMany $query) {
                        $query
                            ->where('status', BaseStatusEnum::PUBLISHED)
                            ->orderBy('created_at', 'DESC');
                    },
                    'posts.slugable',
                ];

                if (is_plugin_active('language-advanced')) {
                    $with[] = 'posts.translations';
                }

                $posts = collect();
                $posts2 = collect();
                if ($shortcode->category_id) {
                    $with['posts'] = function (BelongsToMany $query) {
                        $query
                            ->where('status', BaseStatusEnum::PUBLISHED)
                            ->orderBy('created_at', 'DESC')
                            ->take(6);
                    };

                    $category = app(CategoryInterface::class)
                        ->getModel()
                        ->with($with)
                        ->where([
                            'status' => BaseStatusEnum::PUBLISHED,
                            'id' => $shortcode->category_id,
                        ])
                        ->select([
                            'id',
                            'name',
                            'description',
                            'icon',
                        ])
                        ->first();

                    if ($category) {
                        $posts = $category->posts;
                    } else {
                        $posts = collect();
                    }
                } else {
                    $categories = get_featured_categories(2, $with);

                    foreach ($categories as $category) {
                        $posts = $posts->merge($category->posts->take(3));
                    }

                    $posts = $posts->sortByDesc('created_at');
                }
                if ($shortcode->category_2_id) {
                    $with['posts'] = function (BelongsToMany $query) {
                        $query
                            ->where('status', BaseStatusEnum::PUBLISHED)
                            ->orderBy('created_at', 'DESC')
                            ->take(6);
                    };

                    $category = app(CategoryInterface::class)
                        ->getModel()
                        ->with($with)
                        ->where([
                            'status' => BaseStatusEnum::PUBLISHED,
                            'id' => $shortcode->category_2_id,
                        ])
                        ->select([
                            'id',
                            'name',
                            'description',
                            'icon',
                        ])
                        ->first();

                    if ($category) {
                        $posts2 = $category->posts;
                    } else {
                        $posts2 = collect();
                    }
                } else {
                    $categories = get_featured_categories(2, $with);

                    foreach ($categories as $category) {
                        $posts2 = $posts2->merge($category->posts->take(3));
                    }

                    $posts2 = $posts2->sortByDesc('created_at');
                }
                return Theme::partial(
                    'short-codes.featured-categories-posts',
                    ['title' => $shortcode->title,'title2' => $shortcode->title2, 'posts' => $posts,'posts2' => $posts2]
                );
            }
        );

        shortcode()->setAdminConfig('featured-categories-posts', function (array $attributes) {
            $categories = app(CategoryInterface::class)->pluck('name', 'id', ['status' => BaseStatusEnum::PUBLISHED]);

            return Theme::partial(
                'short-codes.featured-categories-posts-admin-config',
                compact('attributes', 'categories')
            );
        });
    
        shortcode()->setAdminConfig('featured-products', function ($attributes) {
            return Theme::partial('short-codes.featured-products-admin-config', compact('attributes'));
        });

        add_shortcode('featured-places', __('Featured Places'), __('Featured Places'), function ($shortCode) {
            $places = app(\Botble\Marketplace\Models\Store::class)->getModel()
                ->where('status', BaseStatusEnum::PUBLISHED)
                ->where('logo', '!=','')
                ->whereNotNull('logo')
                ->limit(12)
                ->orderBy('id','DESC')
                ->get();
            //dd($places);
            return Theme::partial('short-codes.featured-places', [
                'title' => $shortCode->title,
                'places' => $places,
            ]);
        });

//        shortcode()->setAdminConfig('featured-places', function ($attributes) {
//            return Theme::partial('short-codes.featured-places-admin-config', compact('attributes'));
//        });

        add_shortcode('featured-brands', __('Featured Brands'), __('Featured Brands'), function ($shortCode) {
            return Theme::partial('short-codes.featured-brands', [
                'title' => $shortCode->title,
            ]);
        });

        shortcode()->setAdminConfig('featured-brands', function ($attributes) {
            return Theme::partial('short-codes.featured-brands-admin-config', compact('attributes'));
        });

        add_shortcode(
            'product-collections',
            __('Product Collections'),
            __('Product Collections'),
            function ($shortCode) {
                $productCollections = get_product_collections(
                    ['status' => BaseStatusEnum::PUBLISHED],
                    [],
                    ['id', 'name', 'slug']
                );

                return Theme::partial('short-codes.product-collections', [
                    'title' => $shortCode->title,
                    'productCollections' => ProductCollectionResource::collection($productCollections),
                ]);
            }
        );

        shortcode()->setAdminConfig('product-collections', function ($attributes) {
            return Theme::partial('short-codes.product-collections-admin-config', compact('attributes'));
        });

        add_shortcode('trending-products', __('Trending Products'), __('Trending Products'), function ($shortCode) {
            return Theme::partial('short-codes.trending-products', [
                'title' => $shortCode->title,
            ]);
        });

        shortcode()->setAdminConfig('trending-products', function ($attributes) {
            return Theme::partial('short-codes.trending-products-admin-config', compact('attributes'));
        });

        add_shortcode(
            'product-category-products',
            __('Product category products'),
            __('Product category products'),
            function ($shortCode) {
                $category = app(ProductCategoryInterface::class)->getFirstBy([
                    'status' => BaseStatusEnum::PUBLISHED,
                    'id' => $shortCode->category_id,
                ], ['*'], [
                    'activeChildren' => function ($query) use ($shortCode) {
                        $query->limit($shortCode->number_of_categories ? (int) $shortCode->number_of_categories : 3);
                    },
                    'activeChildren.slugable',
                ]);

                if (!$category) {
                    return null;
                }

                $limit = $shortCode->limit;

                $category = new ProductCategoryResource($category);
                $category->activeChildren = ProductCategoryResource::collection($category->activeChildren);

                return Theme::partial('short-codes.product-category-products', compact('category', 'limit'));
            }
        );

        shortcode()->setAdminConfig('product-category-products', function ($attributes) {
            $categories = ProductCategoryHelper::getProductCategoriesWithIndent();

            return Theme::partial('short-codes.product-category-products-admin-config', compact('attributes', 'categories'));
        });

        add_shortcode('flash-sale', __('Flash sale'), __('Flash sale'), function ($shortCode) {
            $flashSale = app(FlashSaleInterface::class)->getModel()
                ->where('id', $shortCode->flash_sale_id)
                ->notExpired()
                ->first();

            if (!$flashSale || !$flashSale->products()->count()) {
                return null;
            }

            return Theme::partial('short-codes.flash-sale', [
                'title' => $shortCode->title,
                'flashSale' => $flashSale,
            ]);
        });

        shortcode()->setAdminConfig('flash-sale', function ($attributes) {
            $flashSales = app(FlashSaleInterface::class)
                ->getModel()
                ->where('status', BaseStatusEnum::PUBLISHED)
                ->notExpired()
                ->get();

            return Theme::partial('short-codes.flash-sale-admin-config', compact('flashSales', 'attributes'));
        });
    }

    if (is_plugin_active('simple-slider')) {
        add_filter(SIMPLE_SLIDER_VIEW_TEMPLATE, function () {
            return Theme::getThemeNamespace() . '::partials.short-codes.sliders';
        }, 120);

        add_filter(SHORTCODE_REGISTER_CONTENT_IN_ADMIN, function ($data, $key, $attributes) {
            if (in_array($key, ['simple-slider'])) {
                $ads = app(AdsInterface::class)->getModel()
                    ->where('status', BaseStatusEnum::PUBLISHED)
                    ->notExpired()
                    ->get();

                $maxAds = 2;

                return $data . Theme::partial('short-codes.select-ads-admin-config', compact('ads', 'attributes', 'maxAds'));
            }

            return $data;
        }, 50, 3);

        /**
         * @param $shortcode
         * @return array
         */
        function get_ads_keys_from_shortcode($shortcode): array
        {
            $keys = collect($shortcode->toArray())
                ->sortKeys()
                ->filter(function ($value, $key) use ($shortcode) {
                    return Str::startsWith($key, 'ads_') ||
                        ($shortcode->name == 'theme-ads' && Str::startsWith($key, 'key_'));
                });

            return array_filter($keys->toArray() + [$shortcode->ads]);
        }
    }

    if (is_plugin_active('newsletter')) {
        add_shortcode('newsletter-form', __('Newsletter Form'), __('Newsletter Form'), function ($shortCode) {
            return Theme::partial('short-codes.newsletter-form', [
                'title' => $shortCode->title,
                'description' => $shortCode->description,
                'subtitle' => $shortCode->subtitle,
            ]);
        });

        shortcode()->setAdminConfig('newsletter-form', function ($attributes) {
            return Theme::partial('short-codes.newsletter-form-admin-config', compact('attributes'));
        });
    }

    add_shortcode('download-app', __('Download Apps'), __('Download Apps'), function ($shortCode) {
        return Theme::partial('short-codes.download-app', [
            'title' => $shortCode->title,
            'description' => $shortCode->description,
            'subtitle' => $shortCode->subtitle,
            'screenshot' => $shortCode->screenshot,
            'androidAppUrl' => $shortCode->android_app_url,
            'iosAppUrl' => $shortCode->ios_app_url,
        ]);
    });

    shortcode()->setAdminConfig('download-app', function ($attributes) {
        return Theme::partial('short-codes.download-app-admin-config', compact('attributes'));
    });

    if (is_plugin_active('faq')) {
        add_shortcode('faq', __('FAQs'), __('FAQs'), function ($shortCode) {
            $categories = app(FaqCategoryInterface::class)
                ->advancedGet([
                    'condition' => [
                        'status' => BaseStatusEnum::PUBLISHED,
                    ],
                    'with' => [
                        'faqs' => function ($query) {
                            $query->where('status', BaseStatusEnum::PUBLISHED);
                        },
                    ],
                    'order_by' => [
                        'faq_categories.order' => 'ASC',
                        'faq_categories.created_at' => 'DESC',
                    ],
                ]);

            return Theme::partial('short-codes.faq', [
                'title' => $shortCode->title,
                'categories' => $categories,
            ]);
        });

        shortcode()->setAdminConfig('faq', function ($attributes) {
            return Theme::partial('short-codes.faq-admin-config', compact('attributes'));
        });
    }

    add_shortcode('site-features', __('Site features'), __('Site features'), function ($shortcode) {
        return Theme::partial('short-codes.site-features', compact('shortcode'));
    });

    shortcode()->setAdminConfig('site-features', function ($attributes) {
        return Theme::partial('short-codes.site-features-admin-config', compact('attributes'));
    });

    if (is_plugin_active('contact')) {
        add_filter(CONTACT_FORM_TEMPLATE_VIEW, function () {
            return Theme::getThemeNamespace() . '::partials.short-codes.contact-form';
        }, 120);
    }

    add_shortcode('contact-info-boxes', __('Contact info boxes'), __('Contact info boxes'), function ($shortCode) {
        return Theme::partial('short-codes.contact-info-boxes', ['title' => $shortCode->title]);
    });

    shortcode()->setAdminConfig('contact-info-boxes', function ($attributes) {
        return Theme::partial('short-codes.contact-info-boxes-admin-config', compact('attributes'));
    });

    if (is_plugin_active('ads')) {
        add_shortcode('theme-ads', __('Theme ads'), __('Theme ads'), function ($shortCode) {
            $ads = [];
            $attributes = $shortCode->toArray();

            for ($i = 1; $i < 5; $i++) {
                if (isset($attributes['key_' . $i]) && !empty($attributes['key_' . $i])) {
                    $ad = AdsManager::displayAds((string)$attributes['key_' . $i]);
                    if ($ad) {
                        $ads[] = $ad;
                    }
                }
            }

            $ads = array_filter($ads);

            return Theme::partial('short-codes.theme-ads', compact('ads'));
        });

        shortcode()->setAdminConfig('theme-ads', function ($attributes) {
            $ads = app(AdsInterface::class)->getModel()
                ->where('status', BaseStatusEnum::PUBLISHED)
                ->notExpired()
                ->get();

            return Theme::partial('short-codes.theme-ads-admin-config', compact('ads', 'attributes'));
        });
    }

    add_shortcode('coming-soon', __('Coming soon'), __('Coming soon'), function ($shortCode) {
        return Theme::partial('short-codes.coming-soon', [
            'time' => $shortCode->time,
            'image' => $shortCode->image,
        ]);
    });

    shortcode()->setAdminConfig('coming-soon', function ($attributes) {
        return Theme::partial('short-codes.coming-soon-admin-config', compact('attributes'));
    });
    
    add_shortcode('featured-posts', __('Featured posts'), __('Featured posts'), function ($shortcode) {
            $posts = get_featured_posts((int) $shortcode->limit ?: 5, [
                'author',
                'categories' => function ($query) {
                    $query->limit(1);
                },
            ]);
//dd($posts);
            return Theme::partial('short-codes.featured-posts', compact('posts'));
        });

        shortcode()->setAdminConfig('featured-posts', function (array $attributes, string|null $content) {
            return Theme::partial('short-codes.featured-posts-admin-config', compact('attributes', 'content'));
        });

       add_shortcode(
           'col-3-category-posts',
           __('col-3-category-posts'),
           __('col-3-category-posts'),
           function ( $shortcode) {
               $with = [
                   'slugable',
                   'posts' => function (BelongsToMany $query) {
                       $query
                           ->where('status', BaseStatusEnum::PUBLISHED)
                           ->orderBy('created_at', 'DESC');
                   },
                   'posts.slugable',
               ];

               if (is_plugin_active('language-advanced')) {
                   $with[] = 'posts.translations';
               }

               $posts = collect();
               $posts2 = collect();
               $posts3 = collect();
               if ($shortcode->category_id) {
                   $with['posts'] = function (BelongsToMany $query) {
                       $query
                           ->where('status', BaseStatusEnum::PUBLISHED)
                           ->orderBy('created_at', 'DESC')
                           ->take(6);
                   };

                   $category = app(CategoryInterface::class)
                       ->getModel()
                       ->with($with)
                       ->where([
                           'status' => BaseStatusEnum::PUBLISHED,
                           'id' => $shortcode->category_id,
                       ])
                       ->select([
                           'id',
                           'name',
                           'description',
                           'icon',
                       ])
                       ->first();

                   if ($category) {
                       $posts = $category->posts;
                   } else {
                       $posts = collect();
                   }
               } else {
                   $categories = get_featured_categories(2, $with);

                   foreach ($categories as $category) {
                       $posts = $posts->merge($category->posts->take(3));
                   }

                   $posts = $posts->sortByDesc('created_at');
               }
               if ($shortcode->category_2_id) {
                   $with['posts'] = function (BelongsToMany $query) {
                       $query
                           ->where('status', BaseStatusEnum::PUBLISHED)
                           ->orderBy('created_at', 'DESC')
                           ->take(6);
                   };

                   $category = app(CategoryInterface::class)
                       ->getModel()
                       ->with($with)
                       ->where([
                           'status' => BaseStatusEnum::PUBLISHED,
                           'id' => $shortcode->category_2_id,
                       ])
                       ->select([
                           'id',
                           'name',
                           'description',
                           'icon',
                       ])
                       ->first();

                   if ($category) {
                       $posts2 = $category->posts;
                   } else {
                       $posts2 = collect();
                   }
               } else {
                   $categories = get_featured_categories(2, $with);

                   foreach ($categories as $category) {
                       $posts2 = $posts2->merge($category->posts->take(3));
                   }

                   $posts2 = $posts2->sortByDesc('created_at');
               }
               if ($shortcode->category_3_id) {
                   $with['posts'] = function (BelongsToMany $query) {
                       $query
                           ->where('status', BaseStatusEnum::PUBLISHED)
                           ->orderBy('created_at', 'DESC')
                           ->take(6);
                   };

                   $category3 = app(CategoryInterface::class)
                       ->getModel()
                       ->with($with)
                       ->where([
                           'status' => BaseStatusEnum::PUBLISHED,
                           'id' => $shortcode->category_3_id,
                       ])
                       ->select([
                           'id',
                           'name',
                           'description',
                           'icon',
                       ])
                       ->first();

                   if ($category3) {
                       $posts3 = $category3->posts;
                   } else {
                       $posts3 = collect();
                   }
               } else {
                   $categories = get_featured_categories(2, $with);

                   foreach ($categories as $category) {
                       $posts3 = $posts3->merge($category->posts->take(3));
                   }

                   $posts3 = $posts3->sortByDesc('created_at');
               }
               return Theme::partial(
                   'short-codes.col-3-category-posts',
                   ['title' => $shortcode->title,'title2' => $shortcode->title2,'title3' => $shortcode->title3, 'posts' => $posts,'posts2' => $posts2,'posts3' => $posts3]
               );
           }
       );

        shortcode()->setAdminConfig('col-3-category-posts', function (array $attributes) {
            $categories = app(CategoryInterface::class)->pluck('name', 'id', ['status' => BaseStatusEnum::PUBLISHED]);

            return Theme::partial(
                'short-codes.col-3-category-posts-admin-config',
                compact('attributes', 'categories')
            );
        });
add_shortcode('internal-link', __('Internal Link'), __('Internal Link'), function ($shortcode) {

    $post = app(PostInterface::class)->getModel()
        ->where('status', BaseStatusEnum::PUBLISHED)
        ->where('id', $shortcode->post)
        ->first();

    return Theme::partial('short-codes.internal-link', [
        'shortcode' => $shortcode,
        'post' => $post,
    ]);
});
    shortcode()->setAdminConfig('internal-link', function ($attributes) {

        $posts = app(PostInterface::class)->getModel()
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->get();

        return Theme::partial('short-codes.internal-link-admin-config', compact('attributes' , 'posts'));
    });

});
