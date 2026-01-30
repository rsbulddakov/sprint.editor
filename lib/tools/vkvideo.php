<?php

namespace Sprint\Editor\Tools;

class Vkvideo
{
    static public function getVideoCode($url, $default = '')
    {
        $matches = [];
        if (preg_match('%vkvideo.ru/(?:video|clip)((-)?[0-9_]+)%i', $url, $matches)) {
            return $matches[1];
        }
        return $default;
    }

    static public function getPreviewImg($url)
    {
        return '';
    }

    static public function getVideoHtml($url, $width = '560', $height = '315')
    {
        $code = self::getVideoCode($url);
        if ($code) {
            [$oid, $videoId] = explode('_', $code);

            return sprintf(
                '<iframe src="https://vkvideo.ru/video_ext.php?oid=%s&id=%s&hd=2" width="%s" height="%s" allow="autoplay; encrypted-media; fullscreen; picture-in-picture;" allowfullscreen></iframe>',
                $oid,
                $videoId,
                $width,
                $height
            );
        }
        return '';
    }
}
