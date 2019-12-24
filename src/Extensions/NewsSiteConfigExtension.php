<?php

namespace WWN\News\Extensions;

use SilverStripe\Assets\Folder;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\ORM\DataExtension;

/**
 * Siteconfig for news
 *
 * @package wwn-news
 */
class NewsSiteConfigExtension extends DataExtension
{
    /**
     * @var array $db
     */
    private static $db = array(
        'NewsImageUploadFolderByYear' => 'Boolean'
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
        $fields->findOrMakeTab('Root.Uploads', _t('NewsAdmin.SITECONFIGMENUTITLE', 'Uploads'));
        $newsFields = array(
            'NewsImageUploadFolderID' => TreeDropdownField::create('NewsImageUploadFolderID',
                _t('NewsSiteConfigExtension.has_one_NewsImageUploadFolder', 'Bilder'), Folder::class),
            'NewsImageUploadFolderByYear' => CheckboxField::create('NewsImageUploadFolderByYear',
                _t('NewsSiteConfigExtension.db_NewsImageUploadFolderByYear', 'Unterordner pro Jahr'))
        );
        $fields->addFieldsToTab('Root.Uploads', $newsFields);
        $newsHeaders = array(
            'NewsImageUploadFolderID' => _t('Header.UploadFolders', 'Ordner für Newsbilder')
        );
        foreach ($newsHeaders as $insertBefore => $header) {
            $fields->addFieldToTab('Root.Uploads', HeaderField::create($insertBefore . 'Header', $header),
                $insertBefore);
        }
    }
}
