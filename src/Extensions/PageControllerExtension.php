<?php

namespace WWN\News\Extensions;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DB;
use SilverStripe\View\ArrayData;
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
     * @return ArrayList
     */
    public function GetLatestNews($limit = 2): ArrayList
    {
        $site = DataObject::get(SiteTree::class, ['ClassName' => 'WWN\News\NewsPage'])->first();
        $result = DataObject::get(
            NewsArticle::class,
            ['Status' => 1],
            'Date DESC',
            '',
            $limit
        );

        $news = new ArrayList();
        foreach ($result as $key => $val) {
            $news->push(
                new ArrayData(
                    [
                        'PageURL' => $site->URLSegment,
                        'Article' => $val,
                    ]
                )
            );
        }

        return $news;
    }
}
