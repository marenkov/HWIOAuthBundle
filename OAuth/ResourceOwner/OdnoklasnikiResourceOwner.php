<?php

namespace HWI\Bundle\OAuthBundle\OAuth\ResourceOwner;

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\GenericOAuth2ResourceOwner;

class OdnoklasnikiResourceOwner extends GenericOAuth2ResourceOwner {

    /**
     * {@inheritDoc}
     */
    protected $options = array(
        'authorization_url'   => 'http://www.odnoklassniki.ru/oauth/authorize',
        'access_token_url'    => 'http://api.odnoklassniki.ru/oauth/token.do',
        'infos_url'           => 'http://api.odnoklassniki.ru/fb.do',
        'scope'               => 'VALUABLE ACCESS',
        'user_response_class' => '\HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse',
    );

    /**
     * {@inheritDoc}
     */
    protected $paths = array(
        'identifier' => 'uid',
        'nickname'   => 'name',
        'realname'   => 'name',
    );
    
    /**
     * {@inheritDoc}
     */
    public function getUserInformation($accessToken)
    {
        $publicKey = $this->getOption('client_public');
        $secretKey = $this->getOption('client_secret');
        $sig = md5( "application_key={$publicKey}method=users.getCurrentUser" . md5($accessToken . $secretKey) );
        $url = "http://api.odnoklassniki.ru/fb.do?access_token={$accessToken}&application_key={$publicKey}&method=users.getCurrentUser&sig={$sig}";

        $content = $this->doGetUserInformationRequest($url)->getContent();

        $response = $this->getUserResponse();
        $response->setResponse($content);
        $response->setResourceOwner($this);
        $response->setAccessToken($accessToken);

        return $response;
    }
    
}

?>
