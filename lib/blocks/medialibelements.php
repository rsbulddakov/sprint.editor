<?php

namespace Sprint\Editor\Blocks;

use Sprint\Editor\Tools\Medialib;

class MedialibElements
{
    static public function getImages($block, $resizePreview = [], $resizeDetail = [])
    {
        $result = Medialib::GetElements(
            ['id' => $block['element_ids']],
            [],
            $resizePreview,
            $resizeDetail
        );

        return $result['items'];
    }

    static public function getElements($block)
    {
        $result = Medialib::GetElements(
            ['id' => $block['element_ids']],
            [],
            ['width' => 0, 'height' => 0]
        );

        return $result['items'];
    }
}
