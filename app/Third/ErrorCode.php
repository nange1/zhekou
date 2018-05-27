<?php
/**
 * Created by PhpStorm.
 * User: wuhaiping
 * Date: 16/12/30
 * Time: 下午2:58
 */

namespace App\Third;


class ErrorCode {
    public static $OK = 0;
    public static $IllegalAesKey = -41001;
    public static $IllegalIv = -41002;
    public static $IllegalBuffer = -41003;
    public static $DecodeBase64Error = -41004;
    public static $ValidateSignatureError = -40001;
    public static $ParseXmlError = -40002;
    public static $ComputeSignatureError = -40003;
    public static $ValidateCorpidError = -40005;
    public static $EncryptAESError = -40006;
    public static $DecryptAESError = -40007;
    public static $EncodeBase64Error = -40009;
    public static $GenReturnXmlError = -40011;
}