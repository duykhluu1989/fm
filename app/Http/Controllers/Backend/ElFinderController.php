<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Barryvdh\Elfinder\Connector;
use App\Libraries\Helpers\Utility;
use App\Models\User;

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

    public function popupUserAttachment($id)
    {
        return view('backend.elFinders.popup_user_attachment', ['id' => $id]);
    }

    public function popupConnectorUserAttachment($id)
    {
        $path = public_path() . User::ORDER_UPLOAD_PATH . '/' . $id;

        if(!file_exists($path))
            mkdir($path, 0755, true);

        $opts = [
            'bind' => [
                'rm' => function($cmd) use($id) {
                    if($cmd == 'rm')
                    {
                        $user = User::find($id);

                        if($user->attachment == true)
                        {
                            $fullSavePath = public_path() . User::ORDER_UPLOAD_PATH . '/' . $user->id;

                            if(file_exists($fullSavePath) && count(glob($fullSavePath . '/*.{' . implode(',', Utility::getValidExcelExt()) . '}', GLOB_BRACE)) == 0)
                            {
                                $user->attachment = false;
                                $user->save();
                            }
                        }
                    }
                },
            ],
            'roots'  => [
                [
                    'driver'        => 'LocalFileSystem',
                    'path'          => $path,
                    'URL'           => url(User::ORDER_UPLOAD_PATH . '/' . $id),
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
}