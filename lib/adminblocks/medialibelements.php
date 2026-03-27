<?php

namespace Sprint\Editor\AdminBlocks;

use Sprint\Editor\Locale;
use Sprint\Editor\Tools\Medialib;

class MedialibElements
{
    protected $params = [];

    public function __construct()
    {
        $medialibType = $_REQUEST['medialib_type'] ?? 'image';

        $ids = !empty($_REQUEST['element_ids']) ? $_REQUEST['element_ids'] : [];
        $ids = array_map(
            function ($val) {
                return intval($val);
            }, $ids
        );

        $ids = array_unique($ids);

        $ibid = !empty($_REQUEST['collection_id']) ? intval($_REQUEST['collection_id']) : 0;
        $page = !empty($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $page = ($page >= 1) ? $page : 1;

        $this->params = [
            'page'          => $page,
            'limit'         => 10,
            'collection_id' => $ibid,
            'element_ids'   => $ids,
            'medialib_type' => $medialibType,
        ];
    }

    public function execute()
    {
        $pageNum = 1;
        $pageCnt = 1;
        $elements = [];
        $source = [];

        $collections = Medialib::GetCollections([
            'medialib_type' => $this->params['medialib_type'],
        ]);

        $collections = array_map(function ($item) {
            return [
                'title' => Locale::truncateText($item['NAME']),
                'id'    => $item['ID'],
            ];
        }, $collections);

        if ($this->params['collection_id'] > 0) {
            $res = Medialib::GetElements(
                [
                    'collection_id' => $this->params['collection_id'],
                    'medialib_type' => $this->params['medialib_type'],
                ],
                [
                    'page_size' => $this->params['limit'],
                    'page_num'  => $this->params['page'],
                ]
            );

            $pageCnt = $res['page_count'];
            $pageNum = $res['page_num'];
            $source = $res['items'];
        }

        if (!empty($this->params['element_ids'])) {
            $res = Medialib::GetElements([
                'id'            => $this->params['element_ids'],
                'medialib_type' => $this->params['medialib_type'],
            ]);

            $elements = $res['items'];
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode(
            Locale::convertToUtf8IfNeed(
                [
                    'collections'    => $collections,
                    'elements'       => $elements,
                    'source'         => $source,
                    'collection_id'  => $this->params['collection_id'],
                    'element_ids'    => $this->params['element_ids'],
                    'page_num'       => $pageNum,
                    'page_cnt'       => $pageCnt,
                    'medialib_types' => Medialib::GetTypes(),
                    'medialib_type'  => $this->params['medialib_type'],
                ]
            )
        );
    }

}
