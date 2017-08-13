<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Barryvdh\Elfinder\Connector;

class ElFinderController extends Controller
{
    const FILE_BROWSER_PATH = '/uploads/medias';

    public function popup()
    {
        return view('backend.elFinders.popup');
    }

    public function popupConnector()
    {
        $path = public_path() . self::FILE_BROWSER_PATH;

        if(!file_exists($path))
            mkdir($path, 0755, true);

        $opts = [
            'roots'  => [
                [
                    'driver'        => 'LocalFileSystem',
                    'path'          => $path,
                    'URL'           => url(self::FILE_BROWSER_PATH),
                    'uploadDeny'    => ['all'],
                    'uploadAllow'   => ['image'],
                    'uploadOrder'   => ['deny', 'allow'],
                    'accessControl' => 'Barryvdh\Elfinder\Elfinder::checkAccess',
                ]
            ]
        ];

        $connector = new Connector(new \elFinder($opts));
        $connector->run();
        return $connector->getResponse();
    }

    public function tinymce()
    {
        return view('backend.elFinders.tinymce');
    }
}