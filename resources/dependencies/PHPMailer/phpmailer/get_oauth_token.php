<?php

/**
 * PHPMailer - PHP email creation and transport class.
 * PHP Version 5.5
 * @package PHPMailer
 * @see https://github.com/PHPMailer/PHPMailer/ The PHPMailer GitHub project
 * @author Marcus Bointon (Synchro/coolbru) <phpmailer@synchromedia.co.uk>
 * @author Jim Jagielski (jimjag) <jimjag@gmail.com>
 * @author Andy Prevost (codeworxtech) <codeworxtech@users.sourceforge.net>
 * @author Brent R. Matzelle (original founder)
 * @copyright 2012 - 2020 Marcus Bointon
 * @copyright 2010 - 2012 Jim Jagielski
 * @copyright 2004 - 2009 Andy Prevost
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Get an OAuth2 token from an OAuth2 provider.
 * * Install this script on your server so that it's accessible
 * as [https/http]://<yourdomain>/<folder>/get_oauth_token.php
 * e.g.: http://localhost/phpmailer/get_oauth_token.php
 * * Ensure dependencies are installed with 'composer install'
 * * Set up an app in your Google/Yahoo/Microsoft account
 * * Set the script address as the app's redirect URL
 * If no refresh token is obtained when running this file,
 * revoke access to your app and run the script again.
 */

namespace PHPMailer\PHPMailer;




require_once ("./src/Exception.php");
require_once ("./src/SMTP.php");
require_once ("./src/PHPMailer.php");
require_once ("./src/POP3.php");
require_once ("./src/OAuthTokenProvider.php");
require_once ("../../League/OAuth2/Client/Token/AccessTokenInterface.php");
require_once ("../../League/OAuth2/Client/Token/ResourceOwnerAccessTokenInterface.php");
require_once ("../../League/OAuth2/Client/Token/AccessTokenInterface.php");
require_once ("../../League/OAuth2/Client/Token/AccessToken.php");
require_once ("../../GuzzleHTTP/promises/src/Is.php");
require_once ("../../GuzzleHTTP/promises/src/PromiseInterface.php");
require_once ("../../GuzzleHTTP/promises/src/Promise.php");
require_once ("../../GuzzleHTTP/promises/src/TaskQueueInterface.php");
require_once ("../../GuzzleHTTP/promises/src/TaskQueue.php");
require_once ("../../GuzzleHTTP/promises/src/Utils.php");
require_once ("../../GuzzleHTTP/promises/src/PromiseInterface.php");
require_once ("../../GuzzleHTTP/promises/src/FulfilledPromise.php");
require_once ("../../Psr7/MessageInterface.php");
require_once ("../../Psr7/ResponseInterface.php");
require_once ("../../GuzzleHTTP/psr7/src/MessageTrait.php");
require_once ("../../GuzzleHTTP/psr7/src/Response.php");
require_once ("../../GuzzleHTTP/guzzle/src/Handler/HeaderProcessor.php");
require_once ("../../GuzzleHTTP/guzzle/src/Handler/EasyHandle.php");
require_once ("../../GuzzleHTTP/guzzle/src/PrepareBodyMiddleware.php");
require_once ("../../GuzzleHTTP/promises/src/Create.php");
require_once ("../../GuzzleHTTP/guzzle/src/RequestOptions.php");
require_once ("../../Psr7/StreamInterface.php");
require_once ("../../GuzzleHTTP/psr7/src/Stream.php");
require_once ("../../GuzzleHTTP/psr7/src/Utils.php");
require_once ("../../Psr7/UriInterface.php");
require_once ("../../GuzzleHTTP/psr7/src/Uri.php");
require_once ("../../Psr7/MessageInterface.php");
require_once ("../../Psr7/RequestInterface.php");
require_once ("../../GuzzleHTTP/psr7/src/MessageTrait.php");
require_once ("../../GuzzleHTTP/psr7/src/Request.php");
require_once ("../../League/OAuth2/Client/Tool/RequiredParameterTrait.php");
require_once ("../../League/OAuth2/Client/Grant/AbstractGrant.php");
require_once ("../../League/OAuth2/Client/Grant/AuthorizationCode.php");
require_once ("../../League/OAuth2/Client/OptionProvider/OptionProviderInterface.php");
require_once ("../../League/OAuth2/Client/Tool/QueryBuilderTrait.php");
require_once ("../../League/OAuth2/Client/OptionProvider/PostAuthOptionProvider.php");
require_once ("../../GuzzleHTTP/guzzle/src/RedirectMiddleware.php");
require_once ("../../GuzzleHTTP/guzzle/src/Middleware.php");
require_once ("../../GuzzleHTTP/guzzle/src/Handler/StreamHandler.php");
require_once ("../../GuzzleHTTP/guzzle/src/Handler/CurlHandler.php");
require_once ("../../GuzzleHTTP/guzzle/src/Handler/CurlFactoryInterface.php");
require_once ("../../GuzzleHTTP/guzzle/src/Handler/CurlFactory.php");
require_once ("../../GuzzleHTTP/guzzle/src/Handler/CurlMultiHandler.php");
require_once ("../../GuzzleHTTP/guzzle/src/Handler/Proxy.php");
require_once ("../../GuzzleHTTP/guzzle/src/Utils.php");
require_once ("../../GuzzleHTTP/guzzle/src/HandlerStack.php");
require_once ("../../Psr/Http/Client/ClientInterface.php");
require_once ("../../GuzzleHTTP/guzzle/src/ClientTrait.php");
require_once ("../../GuzzleHTTP/guzzle/src/ClientInterface.php");
require_once ("../../GuzzleHTTP/guzzle/src/Client.php");
require_once ("../../League/OAuth2/Client/Tool/RequestFactory.php");
require_once ("../../League/OAuth2/Client/Grant/GrantFactory.php");
require_once ("../../League/OAuth2/Client/Tool/BearerAuthorizationTrait.php");
require_once ("../../League/OAuth2/Client/Tool/QueryBuilderTrait.php");
require_once ("../../League/OAuth2/Client/Tool/GuardedPropertyTrait.php");
require_once ("../../League/OAuth2/Client/Tool/ArrayAccessorTrait.php");
require_once ("../../League/OAuth2/Client/Provider/AbstractProvider.php");
require_once ("../../League/OAuth2/Client/Provider/Google.php");
require_once ("./src/OAuth.php");

/**
 * Aliases for League Provider Classes
 * Make sure you have added these to your composer.json and run `composer install`
 * Plenty to choose from here:
 * @see http://oauth2-client.thephpleague.com/providers/thirdparty/
 */
//@see https://github.com/thephpleague/oauth2-google
use League\OAuth2\Client\Provider\Google;
//@see https://packagist.org/packages/hayageek/oauth2-yahoo
use Hayageek\OAuth2\Client\Provider\Yahoo;
//@see https://github.com/stevenmaguire/oauth2-microsoft
use Stevenmaguire\OAuth2\Client\Provider\Microsoft;

if (!isset($_GET['code']) && !isset($_POST['provider'])) {
    ?>
<html>
<body>
<form method="post">
    <h1>Select Provider</h1>
    <input type="radio" name="provider" value="Google" id="providerGoogle">
    <label for="providerGoogle">Google</label><br>
    <input type="radio" name="provider" value="Yahoo" id="providerYahoo">
    <label for="providerYahoo">Yahoo</label><br>
    <input type="radio" name="provider" value="Microsoft" id="providerMicrosoft">
    <label for="providerMicrosoft">Microsoft</label><br>
    <h1>Enter id and secret</h1>
    <p>These details are obtained by setting up an app in your provider's developer console.
    </p>
    <p>ClientId: <input type="text" name="clientId"><p>
    <p>ClientSecret: <input type="text" name="clientSecret"></p>
    <input type="submit" value="Continue">
</form>
</body>
</html>
    <?php
    exit;
}



session_start();

$providerName = '';
$clientId = '';
$clientSecret = '';

if (array_key_exists('provider', $_POST)) {
    $providerName = $_POST['provider'];
    $clientId = $_POST['clientId'];
    $clientSecret = $_POST['clientSecret'];
    $_SESSION['provider'] = $providerName;
    $_SESSION['clientId'] = $clientId;
    $_SESSION['clientSecret'] = $clientSecret;
} elseif (array_key_exists('provider', $_SESSION)) {
    $providerName = $_SESSION['provider'];
    $clientId = $_SESSION['clientId'];
    $clientSecret = $_SESSION['clientSecret'];
}

//If you don't want to use the built-in form, set your client id and secret here
//$clientId = 'RANDOMCHARS-----duv1n2.apps.googleusercontent.com';
//$clientSecret = 'RANDOMCHARS-----lGyjPcRtvP';

//If this automatic URL doesn't work, set it yourself manually to the URL of this script
$redirectUri = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
//$redirectUri = 'http://localhost/PHPMailer/redirect';

$params = [
    'clientId' => $clientId,
    'clientSecret' => $clientSecret,
    'redirectUri' => $redirectUri,
    'accessType' => 'offline'
];

$options = [];
$provider = null;

switch ($providerName) {
    case 'Google':
        $provider = new Google($params);
        $options = [
            'scope' => [
                'https://mail.google.com/'
            ]
        ];
        break;
    case 'Yahoo':
        $provider = new Yahoo($params);
        break;
    case 'Microsoft':
        $provider = new Microsoft($params);
        $options = [
            'scope' => [
                'wl.imap',
                'wl.offline_access'
            ]
        ];
        break;
}

if (null === $provider) {
    exit('Provider missing');
}

if (!isset($_GET['code'])) {
    //If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl($options);
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: ' . $authUrl);
    exit;
    //Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    unset($_SESSION['provider']);
    exit('Invalid state');
} else {
    unset($_SESSION['provider']);
    //Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken(
        'authorization_code',
        [
            'code' => $_GET['code']
        ]
    );
    //Use this to interact with an API on the users behalf
    //Use this to get a new access token if the old one expires
    echo 'Refresh Token: ', $token->getRefreshToken();
}
