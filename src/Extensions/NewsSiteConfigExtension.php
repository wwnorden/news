<?php

namespace WWN\News\Extensions;

use SilverStripe\Assets\Folder;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\ORM\DataExtension;

/**
 * NewsSiteConfigExtension
 *
 * @package wwn-news
 */
class NewsSiteConfigExtension extends DataExtension
{
    /**
     * @var array $db
     */
    private static $db = array(
        'NewsImageUploadFolderByYear' => 'Boolean',
    );

    /**
     * @var array $has_one
     */
    private static $has_one = array(
        'NewsImageUploadFolder' => Folder::class,
    );

    /**
     * Set upload folder for news
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        Folder::find_or_make(
            _t(
                'WWN\News\Extensions\NewsSiteConfigExtension.Foldername',
                'Foldername'
            )
        );

        $fields->findOrMakeTab('Root.Uploads', _t(
                'WWN\News\Extensions\NewsSiteConfigExtension.SITECONFIGMENUTITLE',
                'Uploads'
            )
        );
        $newsFields = array(
            'NewsImageUploadFolderID' => TreeDropdownField::create(
                'NewsImageUploadFolderID',
                _t(
                    'WWN\News\Extensions\NewsSiteConfigExtension.has_one_NewsImageUploadFolder',
                    'Images'
                ),
                Folder::class
            ),
            'NewsImageUploadFolderByYear' => CheckboxField::create(
                'NewsImageUploadFolderByYear',
                _t(
                    'WWN\News\Extensions\NewsSiteConfigExtension.db_NewsImageUploadFolderByYear',
                    'Unterordner pro Jahr'
                )
            ),
        );
        $fields->addFieldsToTab('Root.Uploads', $newsFields);
        $newsHeaders = array(
            'NewsImageUploadFolderID' => _t(
                'WWN\News\Extensions\NewsSiteConfigExtension.UploadFolders',
                'UploadFolders'
            ),
        );
        foreach ($newsHeaders as $insertBefore => $header) {
            $fields->addFieldToTab(
                'Root.Uploads',
                HeaderField::create($insertBefore.'Header', $header),
                $insertBefore
            );
        }
    }

    public function onBeforeWrite()
    {
        if ($this->owner->NewsImageUploadFolderByYear) {
            Folder::find_or_make(
                _t(
                    'WWN\News\Extensions\NewsSiteConfigExtension.Foldername',
                    'Foldername'
                ).'\\'.date('Y')
            );
        }
    }
}
