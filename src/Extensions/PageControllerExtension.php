<?php

namespace WWN\News\Extensions;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\ArrayData;
use WWN\News\NewsArticle;

/**
 * Extends page controller
 *
 * @package wwn-news
 */
class PageControllerExtension extends Extension
{
    public function GetLatestNews(int $limit = 2): ArrayList
    {
        $site = DataObject::get(SiteTree::class, ['ClassName' => 'WWN\News\NewsPage'])->first();
        $result = DataObject::get(
            NewsArticle::class,
            ['Status' => 1],
            'Date DESC',
            '',
            $limit
        );

        // ancestors
        $url = [];
        foreach ($site->getAncestors() as $page) {
            $url[] = $page->URLSegment;
        }
        $url = implode('/', array_reverse($url)).'/';

        $news = new ArrayList();
        foreach ($result as $key => $val) {
            $news->push(
                new ArrayData(
                    [
                        'PageURL' => $url.$site->URLSegment,
                        'Article' => $val,
                    ]
                )
            );
        }

        return $news;
    }
}
