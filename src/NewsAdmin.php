<?php

namespace WWN\News;

use SilverStripe\Admin\ModelAdmin;

/**
 * Administration of news
 *
 * @package wwn-news
 */
class NewsAdmin extends ModelAdmin
{
    private static string $menu_icon_class = 'font-icon-info-circled';

    private static string $menu_title = 'News';

    private static string $url_segment = 'news';

    private static array $managed_models = [
        'WWN\News\NewsArticle',
        'WWN\News\NewsLink',
    ];

    private static array $model_importers = [
        NewsArticle::class => NewsCsvBulkLoader::class,
    ];
}
