<?php

namespace WWN\News;

use SilverStripe\Control\Director;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\HasManyList;

/**
 * News article
 *
 * @package wwn-news
 * @property string  $Title
 * @property string  $Date
 * @property string  $Content
 * @property boolean $Status
 * @method HasManyList Links()
 * @method HasManyList NewsImages()
 */
class NewsArticle extends DataObject
{
    /**
     * @var string
     */
    private static $table_name = 'WWNNewsArticle';

    /**
     * @var array $db
     */
    private static $db = [
        'Title'   => 'Varchar(150)',
        'Date'    => 'Date',
        'Content' => 'HTMLText',
        'Status'  => 'Boolean' // Update `WWNNewsArticle` SET `Status` = 1
    ];

    /**
     * @var array $has_many
     */
    private static $has_many = [
        'Links'      => NewsLink::class,
        'NewsImages' => NewsImage::class,
    ];

    /**
     * @var array $indexes
     */
    private static $indexes = [
        'SearchFields' => [
            'type'    => 'fulltext',
            'columns' => ['Title', 'Content'],
        ],
    ];

    /**
     * @var array $defaults
     */
    private static $defaults = array(
        'Status' => 0,
    );

    /**
     * @var array $default_sort
     */
    private static $default_sort = [
        'Date' => 'DESC',
        'ID'   => 'DESC',
    ];

    /**
     * @var array $summary_fields
     */
    private static $summary_fields = [
        'Title',
        'DateFormatted' => 'Datum',
    ];

    /**
     * @return mixed
     */
    public function getDateFormatted()
    {
        return date('d.m.Y', strtotime($this->dbObject('Date')->getValue()));
    }

    /**
     * @var array $searchable_fields
     */
    private static $searchable_fields = [
        'Title',
        'Content',
    ];

    /**
     * @return DataObject|void
     */
    public function populateDefaults()
    {
        parent::populateDefaults();
        $this->Date = date('d.m.Y');
    }

    /**
     * @return FieldList $fields
     */
    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        // Content field
        $fields->findOrMakeTab('Root.ContentTab', _t('Tab.Content', 'Inhalt'));
        $contentFields = ['Content' => $fields->fieldByName('Root.Main.Content')];
        $fields->addFieldsToTab('Root.ContentTab', $contentFields);

        return $fields;
    }

    /**
     * @return RequiredFields
     */
    public function getCMSValidator(): RequiredFields
    {
        return RequiredFields::create('Title');
    }

    /**
     * link to backend edit form
     *
     * @return boolean|string
     */
    public function EditLink()
    {
        $editLink = false;
        if ($this->canEdit()) {
            $editLink = Director::baseURL()
                .'admin/news/NewsArticle/EditForm/field/NewsArticle/item/'
                .$this->ID.'/edit/';
        }

        return $editLink;
    }
}
