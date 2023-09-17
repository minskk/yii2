<?php

namespace app\controllers\admin\album;

use Itstructure\MFUploader\controllers\album\AudioAlbumController as BaseAudioAlbumController;
use app\traits\{AdminBeforeActionTrait, AccessTrait};

/**
 * AudioAlbumController
 *
 * @author Andrey Girnik <girnikandrey@gmail.com>
 */
class AudioAlbumController extends BaseAudioAlbumController
{
    use AdminBeforeActionTrait, AccessTrait;
}
