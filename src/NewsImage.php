<?php

namespace WWN\News;

use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;
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
     * @var array $db
     */
    private static $db = array(
        'Title'     => 'Varchar(100)',
        'SortOrder' => 'Int',
    );

    /**
     * @var array $has_one
     */
    private static $has_one = array(
        'NewsArticle' => NewsArticle::class,
        'Image'       => Image::class,
    );

    /**
     * @var string|array $default_sort
     */
    private static $default_sort = 'SortOrder';

    /**
     * @var array $field_labels
     */
    private static $field_labels = array(
        'Title'     => 'Titel',
        'Thumbnail' => 'Vorschau',
    );

    /**
     * @var array $searchable_fields
     */
    private static $searchable_fields = array(
        'Title',
    );

    /**
     * @var array $summary_fields
     */
    private static $summary_fields = array(
        'Title',
        'Thumbnail',
        //for virtual field Thumbnail, set $searchable_fields without Thumbnail
    );

    /**
     * Publish image by default while news article is published
     *
     * @var array $owns
     */
    private static $owns = [
        'Image',
    ];

    /**
     * @return \SilverStripe\Forms\FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('NewsArticleID');
        $fields->removeByName('SortOrder');

        return $fields;
    }

    /**
     * @return Image
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
        if (!$member) {
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
        if (!$member) {
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
    public function canCreate($member = false, $context = array())
    {
        if (!$member) {
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
        if (!$member) {
            $member = Security::getCurrentUser();
        }

        return Permission::checkMember($member, 'NEWSIMAGE_DELETE');
    }

    /**
     * @return array
     */
    public function providePermissions()
    {
        return array(
            'NEWSIMAGE_VIEW'   => 'View news images',
            'NEWSIMAGE_EDIT'   => 'Edit news images',
            'NEWSIMAGE_CREATE' => 'Create news images',
            'NEWSIMAGE_DELETE' => 'Delete news images',
        );
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

