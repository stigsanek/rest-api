<?php

namespace App\Service;

class Extractor
{
    /**
     * Метод получения данных для всех видов запросов
     * GET, POST, PUT, DELETE...
     */
    public static function extractData($method)
    {
        if ($method === 'GET') {
            return $_GET;
        }

        if ($method === 'POST') {
            return $_POST;
        }

        $data = [];
        $exploded = explode('&', file_get_contents('php://input'));

        foreach ($exploded as $pair) {
            $item = explode('=', $pair);
            if (count($item) == 2) {
                $data[urldecode($item[0])] = urldecode($item[1]);
            }
        }

        return $data;
    }
}
