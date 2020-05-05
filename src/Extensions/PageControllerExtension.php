<?php

namespace WWN\News\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use WWN\News\NewsArticle;

/**
 * Extends page controller
 *
 * @package wwn-news
 */
class PageControllerExtension extends Extension
{
    /**
     * @param int $limit
     *
     * @return DataList|null
     */
    public function GetLatestNews($limit = 2): ?DataList
    {
        return DataObject::get(NewsArticle::class, ['Status' => 1], 'Date DESC', '', $limit);
    }
}
