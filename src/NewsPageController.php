<?php

namespace WWN\News;

use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\PaginatedList;

/**
 * NewsPage Controller
 *
 * @package wwn-news
 */
class NewsPageController extends \PageController
{
    /**
     * Return paginated news
     *
     * @return PaginatedList
     * @throws \Exception
     */
    public function PaginatedNews()
    {
        $articles = DataObject::get(NewsArticle::class, ['Status' => 1], 'Date DESC');
        return new PaginatedList($articles, $this->getRequest());
    }
}
