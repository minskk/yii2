<?php

namespace app\controllers\admin\album;

use Itstructure\MFUploader\controllers\album\ImageAlbumController as BaseImageAlbumController;
use app\traits\{AdminBeforeActionTrait, AccessTrait};

/**
 * ImageAlbumController
 *
 * @author Andrey Girnik <girnikandrey@gmail.com>
 */
class ImageAlbumController extends BaseImageAlbumController
{
    use AdminBeforeActionTrait, AccessTrait;
}
