<?php

namespace WWN\News;

use SilverStripe\CMS\Forms\SiteTreeURLSegmentField;
use SilverStripe\Control\Director;
use SilverStripe\Core\Convert;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\HasManyList;
use SilverStripe\View\Parsers\URLSegmentFilter;
use SilverStripe\View\Requirements;

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
        'Title' => 'Varchar(150)',
        'URLSegment' => 'Varchar(255)',
        'Date' => 'Date',
        'Content' => 'HTMLText',
        'Status' => 'Boolean' // Update `WWNNewsArticle` SET `Status` = 1
    ];

    /**
     * @var array $has_many
     */
    private static $has_many = [
        'Links' => NewsLink::class,
        'NewsImages' => NewsImage::class,
    ];

    /**
     * @var array $indexes
     */
    private static $indexes = [
        'SearchFields' => [
            'type' => 'fulltext',
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
        'ID' => 'DESC',
    ];

    /**
     * @var array $summary_fields
     */
    private static $summary_fields = [
        'Title',
        'DateFormatted' => 'Datum',
        'URLSegment'
    ];

    /**
     * @return mixed
     */
    public function getDateFormatted()
    {
        return date(
            _t(
                'WWN\News\NewsArticle.DateFormatList',
                'm/d/Y'
            ),
            strtotime($this->dbObject('Date')->getValue())
        );
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

        // Url segment
        $mainFields = array(
            'URLSegment' => SiteTreeURLSegmentField::create(
                'URLSegment',
                _t('WWN\News\NewsArticle.db_URLSegment', 'URL-segment')
            ),
        );

        // Content field
        $fields->findOrMakeTab(
            'Root.ContentTab',
            _t('WWN\News\NewsArticle.ContentTab', 'Content')
        );
        $contentFields = [
            'Content' => $fields->fieldByName('Root.Main.Content')
        ];
        $fields->addFieldsToTab('Root.ContentTab', $contentFields);

        // Date
        $date = DateField::create(
            'Date',
            _t('WWN\News\NewsArticle.db_Date', 'Date')
        )
            ->setHTML5(false)
            ->setDateFormat(
                _t('WWN\News\NewsArticle.DateFormat',
                    'MM/dd/yyyy')
            );
        $date->setDescription(
            _t(
                'WWN\News\NewsArticle.DateDescription',
                'e.g. {format}',
                ['format' => $date->getDateFormat()]
            )
        );
        $date->setAttribute(
            'placeholder',
            $date->getDateFormat()
        );
        $mainFields['Date'] = $date;

        $fields->addFieldsToTab('Root.Main', $mainFields);

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

    /**
     * rewrite urlsegment
     */
    protected function onBeforeWrite()
    {
        parent::onBeforeWrite();

        if (!$this->URLSegment || ($this->URLSegment && $this->isChanged('URLSegment'))){
            $urlFilter = URLSegmentFilter::create();
            $filteredTitle = $urlFilter->filter($this->Title);

            // check if duplicate
            $filter['URLSegment'] = Convert::raw2sql($filteredTitle);
            $filter['ID:not'] = $this->ID;
            $object = DataObject::get($this->getClassName())->filter($filter)->first();
            if ($object){
                $filteredTitle .= '-' . $this->ID;
            }

            // Fallback to generic page name if path is empty (= no valid, convertable characters)
            if (! $filteredTitle || $filteredTitle == '-'
                || $filteredTitle == '-1'
            ) {
                $filteredTitle = "news-$this->ID";
            }
            $this->URLSegment = $filteredTitle;
        }
    }
}
