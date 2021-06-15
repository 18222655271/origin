<?php

namespace addons\qrcode\controller;

use think\addons\Controller;
use think\Response;

/**
 * 二维码生成
 *
 */
class Index extends Controller
{

    public function index()
    {

        
        return $this->view->fetch();

    }

    // 生成二维码
    public function build()
    {
        $text = $this->request->get('text', 'Hello World');
        $size = $this->request->get('size', 250);
        $padding = $this->request->get('padding', 15);
        $errorlevel = $this->request->get('errorlevel', 'medium');
        $foreground = $this->request->get('foreground', "#000000");
        $background = $this->request->get('background', "#ffffff");
        $logo = $this->request->get('logo');
        $logosize = $this->request->get('logosize');
        $label = $this->request->get('label');
        $labelfontsize = $this->request->get('labelfontsize');
        $labelalignment = $this->request->get('labelalignment');

        $params = [
            'text'           => $text,
            'size'           => $size,
            'padding'        => $padding,
            'errorlevel'     => $errorlevel,
            'foreground'     => $foreground,
            'background'     => $background,
            'logo'           => $logo,
            'logosize'       => $logosize,
            'label'          => $label,
            'labelfontsize'  => $labelfontsize,
            'labelalignment' => $labelalignment,
        ];

        $qrCode = \addons\qrcode\library\Service::qrcode($params);

        $response = Response::create()->header("Content-Type", "image/png");

        // 直接显示二维码
        header('Content-Type: ' . $qrCode->getContentType());
        $response->content($qrCode->writeString());

        // 写入到文件
        //$filePath = ROOT_PATH . 'public/uploads/qrcode/' . md5(implode('', $params)) . '.png';
        //$qrCode->writeFile($filePath);

        return $response;
    }

}
