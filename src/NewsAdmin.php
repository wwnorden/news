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
    /**
     * @var string menuicon svg
     */
    private static $menu_icon_class = 'font-icon-info-circled';

    /**
     * @var string $menu_title
     */
    private static $menu_title = 'News';

    /**
     * @var string $url_segment
     */
    private static $url_segment = 'news';

    /**
     * @var array $managed_models
     */
    private static $managed_models = array(
        'WWN\News\NewsArticle',
        'WWN\News\NewsLink'
    );

    /**
     * @var array $model_importers
     */
    private static $model_importers = [
        NewsArticle::class => NewsCsvBulkLoader::class,
    ];
}
