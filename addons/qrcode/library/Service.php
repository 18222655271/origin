<?php

namespace addons\qrcode\library;

use Endroid\QrCode\ErrorCorrectionLevel;

class Service
{
    public static function qrcode($params)
    {
        $params = is_array($params) ? $params : [$params];
        $params['text'] = isset($params['text']) ? $params['text'] : 'Hello world!';
        $params['size'] = isset($params['size']) ? $params['size'] : 250;
        $params['padding'] = isset($params['padding']) ? $params['padding'] : 15;
        $params['format'] = isset($params['format']) ? $params['format'] : 'png';
        $params['errorlevel'] = isset($params['errorlevel']) ? $params['errorlevel'] : 'medium';
        $params['foreground'] = isset($params['foreground']) ? $params['foreground'] : "#000000";
        $params['background'] = isset($params['background']) ? $params['background'] : "#ffffff";
        $params['label'] = isset($params['label']) ? $params['label'] : '';
        $params['labelfontsize'] = isset($params['labelfontsize']) ? (int)$params['labelfontsize'] : 14;
        $params['labelfontpath'] = isset($params['labelfontpath']) ? $params['labelfontpath'] : ROOT_PATH . 'public/assets/fonts/SourceHanSansK-Regular.ttf';
        $params['labelalignment'] = isset($params['labelalignment']) ? $params['labelalignment'] : null;
        $params['logo'] = isset($params['logo']) ? $params['logo'] : '';
        $params['logosize'] = isset($params['logosize']) ? $params['logosize'] : 50;
        $params['logopath'] = isset($params['logopath']) ? $params['logopath'] : ROOT_PATH . 'public/assets/img/qrcode.png';

        // 前景色
        list($r, $g, $b) = sscanf($params['foreground'], "#%02x%02x%02x");
        $foregroundcolor = ['r' => $r, 'g' => $g, 'b' => $b];

        // 背景色
        list($r, $g, $b) = sscanf($params['background'], "#%02x%02x%02x");
        $backgroundcolor = ['r' => $r, 'g' => $g, 'b' => $b];

        // 创建实例
        $qrCode = new \Endroid\QrCode\QrCode(isset($params['text']) ? $params['text'] : 'Hello world!');
        $qrCode->setSize($params['size']);

        // 高级选项
        $qrCode->setWriterByName($params['format']);
        $qrCode->setMargin($params['padding']);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel($params['errorlevel']));
        $qrCode->setForegroundColor($foregroundcolor);
        $qrCode->setBackgroundColor($backgroundcolor);

        //设置标签
        if ($params['label']) {
            $qrCode->setLabel($params['label'], $params['labelfontsize'], $params['labelfontpath'], $params['labelalignment']);
        }

        //设置Logo
        if ($params['logo']) {
            $qrCode->setLogoPath($params['logopath']);
            $qrCode->setLogoSize($params['logosize'], $params['logosize']);
        }

        $qrCode->setRoundBlockSize(true);
        $qrCode->setValidateResult(false);
        $qrCode->setWriterOptions(['exclude_xml_declaration' => true]);

        return $qrCode;
    }
}
