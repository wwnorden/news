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
     * @return \SilverStripe\ORM\DataList
     */
    public function GetLatestNews(): ?DataList
    {
        $articles = DataObject::get(NewsArticle::class, ['Status' => 1], 'Date DESC', '', '2');
        return $articles;
    }
}
