<?php

require_once DOL_DOCUMENT_ROOT.'/google_client/src/Google/autoload.php';

class MusoGoogleClient
{
    private static $CILENT_ID = '637962699210-4b1bjjjuo24s3kslrdc4fd2agtolv26q.apps.googleusercontent.com';
    private static $CLIENT_SECRET = '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';
    private static $APP_NAME = 'erp dolibarr musohealth.ml';
    private static $REDIRECTION_URI = 'https://erp.musohealth.ml/index.php?mainmenu=home&muso=google&actionlogin=login';

    private static $gClient;

    /**
     * @param $gClient
     */
    public static function init()
    {
        self::$gClient = new Google_Client();
        self::$gClient->setClientId(self::$CILENT_ID);
        self::$gClient->setClientSecret(self::$CLIENT_SECRET);
        self::$gClient->setApplicationName(self::$APP_NAME);
        self::$gClient->setRedirectUri(self::$REDIRECTION_URI);
        self::$gClient->addScope("https://www.googleapis.com/auth/userinfo.profile");
        self::$gClient->addScope("https://www.googleapis.com/auth/userinfo.email");
    }
    public static function googleLoginUrl() {
        return self::$gClient->createAuthUrl();
    }
    public static function getGoogleClient() {
        return self::$gClient;
    }
    public static function getRedirectionUri() {
        return self::$REDIRECTION_URI;
    }
}


