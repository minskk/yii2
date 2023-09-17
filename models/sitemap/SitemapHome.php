<?php

namespace app\models\sitemap;

use yii\helpers\Url;
use Itstructure\Sitemap\interfaces\Basic;
use app\models\Home;

/**
 * Class SitemapHome
 *
 * @package app\commands\models\sitemap
 */
class SitemapHome extends Home implements Basic
{
    /**
     * Handle materials by selecting batch of elements.
     * Increase this value and got more handling speed but more memory usage.
     *
     * @var int
     */
    public $sitemapBatchSize = 10;

    /**
     * @var Home
     */
    private $model;

    /**
     * @inheritdoc
     */
    public function getSitemapItems($lang = null)
    {
        $this->model = Home::getDefaultHome();

        if (empty($this->model)) {
            return null;
        }

        return [
            [
                'loc' => Url::to('/', true),
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
        return Url::to('/', true);
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
