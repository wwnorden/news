<?php

namespace WWN\News;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\DataObject;

/**
 * Newslink(s) for news article
 *
 * @package wwn-news
 * @property string $Title
 * @property string $URL
 * @property string $Source
 */
class NewsLink extends DataObject
{
    private static string $table_name = 'WWNNewsLink';

    private static array $db = [
        'Title' => 'Varchar(255)',
        'URL' => 'Varchar(255)',
        'Source' => 'Varchar(255)',
    ];

    private static array $has_one = [
        'NewsArticle' => NewsArticle::class,
    ];

    private static array $default_sort = ['Title'];

    private static array $summary_fields = [
        'Title',
        'URL',
    ];

    /**
     * @return RequiredFields
     */
    public function getCMSValidator(): RequiredFields
    {
        return RequiredFields::create('Title');
    }

    /**
     * @return FieldList
     */
    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        //Main Tab
        $fields->findOrMakeTab('Root.Main');
        $contentFields = array(
            'URL' => $fields->fieldByName('Root.Main.URL')
        );
        $contentFields['URL']->setDescription(_t('URL.Form', 'URL mit http(s) angeben'));
        $fields->addFieldsToTab('Root.Main', $contentFields);

        return $fields;
    }
}
