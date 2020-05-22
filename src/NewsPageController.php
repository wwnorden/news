<?php

namespace WWN\News;

use Exception;
use PageController;
use SilverStripe\Core\Convert;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\ORM\PaginatedList;
use SilverStripe\View\ArrayData;

/**
 * NewsPage Controller
 *
 * @package wwn-news
 */
class NewsPageController extends PageController
{
    private static $allowed_actions = [
        'showNewsArticle',
    ];

    private static $url_handlers = [
        '$URLSegment!' => 'showNewsArticle',
    ];

    /**
     * Return paginated news
     *
     * @return PaginatedList
     * @throws Exception
     */
    public function PaginatedNews()
    {
        $articles =
            DataObject::get(
                NewsArticle::class,
                ['Status' => 1],
                'Date DESC'
            );

        return new PaginatedList($articles, $this->getRequest());
    }

    /**
     * Detail view
     *
     * @return DBHTMLText
     * @throws Exception
     */
    public function showNewsArticle(): DBHTMLText
    {
        $name = Convert::raw2sql($this->getRequest()->param('URLSegment'));
        $filter = [
            'URLSegment' => $name,
        ];

        $article = NewsArticle::get()->filter($filter)->first();
        $customise = [
            'Article' => $article,
            'ExtraBreadcrumb' => ArrayData::create(
                [
                    'Title' => $article->Name,
                    'Link' => $this->Link($name),
                ]
            ),
            'Name' => $article->Name,
        ];

        $renderWith = [
            'WWN/News/NewsArticle',
            'Page',
        ];

        return $this->customise($customise)->renderWith($renderWith);
    }
}
