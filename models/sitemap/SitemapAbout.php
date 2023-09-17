<?php

namespace app\models\sitemap;

use yii\helpers\Url;
use Itstructure\Sitemap\interfaces\Basic;
use app\models\About;

/**
 * Class SitemapAbout
 *
 * @package app\commands\models\sitemap
 */
class SitemapAbout extends About implements Basic
{
    /**
     * Handle materials by selecting batch of elements.
     * Increase this value and got more handling speed but more memory usage.
     *
     * @var int
     */
    public $sitemapBatchSize = 10;

    /**
     * @var About
     */
    private $model;

    /**
     * @inheritdoc
     */
    public function getSitemapItems($lang = null)
    {
        $this->model = About::getDefaultAbout();

        if (empty($this->model)) {
            return null;
        }

        return [
            [
                'loc' => Url::to('/about', true),
                'lastmod' => $this->getSitemapLastmod(),
                'changefreq' => $this->getSitemapChangefreq(),
                'priority' => $this->getSitemapPriority(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSitemapItemsQuery($lang = null)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getSitemapLoc($lang = null)
    {
        return Url::to('/about', true);
    }

    /**
     * @inheritdoc
     */
    public function getSitemapLastmod($lang = null)
    {
        return (new \DateTime($this->model->updated_at))->getTimestamp();
    }

    /**
     * @inheritdoc
     */
    public function getSitemapChangefreq($lang = null)
    {
        return static::CHANGEFREQ_MONTHLY;
    }

    /**
     * @inheritdoc
     */
    public function getSitemapPriority($lang = null)
    {
        return static::PRIORITY_8;
    }
}
