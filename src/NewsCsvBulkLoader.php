<?php

namespace WWN\News;

use SilverStripe\Dev\CsvBulkLoader;

/**
 * NewsCsvBulkLoader for loading news from csv
 *
 * @package wwn-news
 */
class NewsCsvBulkLoader extends CsvBulkLoader
{
    public $columnMap = [
        'title'          => 'Title',
        'description'    => 'Content',
        'date'           => 'Date',
        'status'         => 'Status',
        'links/0/source' => '->setLinkByTitle',
        'links/1/source' => '->setLink1',
        'links/2/source' => '->setLink2',
        'links/3/source' => '->setLink3',
    ];

    /**
     * @param NewsArticle $obj
     * @param string      $val
     * @param array       $record
     *
     * @throws \SilverStripe\ORM\ValidationException
     */
    public static function setLinkByTitle(&$obj, $val, $record)
    {
        if (!empty($val)) {
            $obj->write();
            $link = new NewsLink();
            $link->Title = $val;
            $link->Source = $val;
            $link->URL = $record['links/0/url'];
            $link->NewsArticleID = $obj->ID;
            $link->write();
        }
    }

    /**
     * @param NewsArticle $obj
     * @param string      $val
     * @param array       $record
     *
     * @throws \SilverStripe\ORM\ValidationException
     */
    public static function setLink1(&$obj, $val, $record)
    {
        if (!empty($val)) {
            $link = new NewsLink();
            $link->Title = $val;
            $link->Source = $val;
            $link->NewsArticleID = $obj->ID;
            $link->URL = $record['links/1/url'];
            $link->write();
        }
    }

    /**
     * @param NewsArticle $obj
     * @param string      $val
     * @param array       $record
     *
     * @throws \SilverStripe\ORM\ValidationException
     */
    public static function setLink2(&$obj, $val, $record)
    {
        if (!empty($val)) {
            $link = new NewsLink();
            $link->Title = $val;
            $link->Source = $val;
            $link->NewsArticleID = $obj->ID;
            $link->URL = $record['links/2/url'];
            $link->write();
        }
    }

    /**
     * @param NewsArticle $obj
     * @param string      $val
     * @param array       $record
     *
     * @throws \SilverStripe\ORM\ValidationException
     */
    public static function setLink3(&$obj, $val, $record)
    {
        if (!empty($val)) {
            $link = new NewsLink();
            $link->Title = $val;
            $link->Source = $val;
            $link->NewsArticleID = $obj->ID;
            $link->URL = $record['links/3/url'];
            $link->write();
        }
    }
}
