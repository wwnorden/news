<?php

namespace WWN\News;

use SilverStripe\Assets\Image;
use SilverStripe\Assets\Storage\DBFile;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\Security\Permission;
use SilverStripe\Security\PermissionProvider;
use SilverStripe\Security\Security;

/**
 * NewsImage(s) for news article
 *
 * @package wwn-news
 * @property string $Title
 * @property int    $SortOrder
 */
class NewsImage extends DataObject implements PermissionProvider
{
    /**
     * @var string
     */
    private static $table_name = 'WWNNewsImage';

    /**
     * @var string[]
     */
    private static $db = [
        'Title' => 'Varchar(100)',
        'SortOrder' => 'Int',
    ];

    /**
     * @var string[]
     */
    private static $has_one = [
        'NewsArticle' => NewsArticle::class,
        'Image' => Image::class,
    ];

    /**
     * @var string
     */
    private static $default_sort = 'SortOrder';

    /**
     * @var string[]
     */
    private static $field_labels = [
        'Title' => 'Titel',
        'Thumbnail' => 'Vorschau',
    ];

    /**
     * @var string[]
     */
    private static $searchable_fields = [
        'Title',
    ];

    /**
     * @var string[]
     */
    private static $summary_fields = [
        'Title',
        'Thumbnail',
        //for virtual field Thumbnail, set $searchable_fields without Thumbnail
    ];

    /**
     * Publish image by default while news article is published
     *
     * @var string[]
     */
    private static $owns = [
        'Image',
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('NewsArticleID');
        $fields->removeByName('SortOrder');

        $image = $fields->dataFieldByName('Image');
        $image->setFolderName(
            _t(
                'WWN\News\Extensions\NewsSiteConfigExtension.Foldername',
                'Foldername'
            ).'/'.date('Y')
        );

        return $fields;
    }

    /**
     * @return DBFile|DBHTMLText
     */
    public function getThumbnail()
    {
        return $this->Image()->CMSThumbnail();
    }

    /**
     * @param mixed $member
     *
     * @return bool|int
     */
    public function canView($member = false)
    {
        if (! $member) {
            $member = Security::getCurrentUser();
        }

        return Permission::checkMember($member, 'NEWSIMAGE_VIEW');
    }

    /**
     * @param mixed $member
     *
     * @return bool|int
     */
    public function canEdit($member = false)
    {
        if (! $member) {
            $member = Security::getCurrentUser();
        }

        return Permission::checkMember($member, 'NEWSIMAGE_EDIT');
    }

    /**
     * @param mixed $member
     * @param array $context
     *
     * @return bool|int
     */
    public function canCreate($member = false, $context = [])
    {
        if (! $member) {
            $member = Security::getCurrentUser();
        }

        return Permission::checkMember($member, 'NEWSIMAGE_CREATE');
    }

    /**
     * @param mixed $member
     *
     * @return bool|int
     */
    public function canDelete($member = false)
    {
        if (! $member) {
            $member = Security::getCurrentUser();
        }

        return Permission::checkMember($member, 'NEWSIMAGE_DELETE');
    }

    /**
     * @return array
     */
    public function providePermissions(): array
    {
        return [
            'NEWSIMAGE_VIEW' => 'View news images',
            'NEWSIMAGE_EDIT' => 'Edit news images',
            'NEWSIMAGE_CREATE' => 'Create news images',
            'NEWSIMAGE_DELETE' => 'Delete news images',
        ];
    }

    /**
     * Increment SortOrder on save
     */
    public function onBeforeWrite()
    {
        if (! $this->SortOrder) {
            $this->SortOrder = NewsImage::get()->max('SortOrder') + 1;
        }
        parent::onBeforeWrite();
    }

    /**
     * publish images after save to db
     */
    public function onAfterWrite()
    {
        if ($this->owner->ImageID) {
            $this->owner->Image()->publishSingle();
        }
        parent::onAfterWrite();
    }
}