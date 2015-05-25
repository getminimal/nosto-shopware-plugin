<?php

/**
 * Meta-data class for handling OAuth 2 requests during account connect.
 *
 * Implements NostoOAuthClientMetaDataInterface.
 *
 * @package Shopware
 * @subpackage Plugins_Frontend
 * @author Nosto Solutions Ltd <shopware@nosto.com>
 * @copyright Copyright (c) 2015 Nosto Solutions Ltd (http://www.nosto.com)
 */
class Shopware_Plugins_Frontend_NostoTagging_Components_Meta_Oauth implements NostoOAuthClientMetaDataInterface
{
	/**
	 * @var string OAuth2 redirect url to where the OAuth2 server should redirect the user after authorizing.
	 */
	protected $_redirectUrl;

	/**
	 * @var string 2-letter ISO code (ISO 639-1) for the language the OAuth2 server uses for UI localization.
	 */
	protected $_languageCode = 'en';

	/**
	 * Loads the oauth meta data from the shop model.
	 *
	 * @param \Shopware\Models\Shop\Shop $shop the shop model.
	 * @param \Shopware\Models\Shop\Locale $locale the locale model or null.
	 */
	public function loadData(\Shopware\Models\Shop\Shop $shop, \Shopware\Models\Shop\Locale $locale = null)
	{
		if (is_null($locale)) {
			$locale = $shop->getLocale();
		}

		$this->_redirectUrl = Shopware()->Front()->Router()->assemble(array(
			'module' => 'frontend',
			'controller' => 'nostotagging',
			'action' => 'oauth'
		));
		$this->_languageCode = strtolower(substr($locale->getLocale(), 0, 2));
	}

	/**
	 * The OAuth2 client ID.
	 * This will be a platform specific ID that Nosto will issue.
	 *
	 * @return string the client id.
	 */
	public function getClientId()
	{
		return Shopware_Plugins_Frontend_NostoTagging_Bootstrap::PLATFORM_NAME;
	}

	/**
	 * The OAuth2 client secret.
	 * This will be a platform specific secret that Nosto will issue.
	 *
	 * @return string the client secret.
	 */
	public function getClientSecret()
	{
		return Shopware_Plugins_Frontend_NostoTagging_Bootstrap::PLATFORM_NAME;
	}

	/**
	 * The OAuth2 redirect url to where the OAuth2 server should redirect the user after authorizing the application to
	 * act on the users behalf.
	 * This url must by publicly accessible and the domain must match the one defined for the Nosto account.
	 *
	 * @return string the url.
	 */
	public function getRedirectUrl()
	{
		return $this->_redirectUrl;
	}

	/**
	 * The scopes for the OAuth2 request.
	 * These are used to request specific API tokens from Nosto and should almost always be the ones defined in
	 * NostoApiToken::$tokenNames.
	 *
	 * @return array the scopes.
	 */
	public function getScopes()
	{
		// We want all the available Nosto API tokens.
		return NostoApiToken::$tokenNames;
	}

	/**
	 * The 2-letter ISO code (ISO 639-1) for the language the OAuth2 server uses for UI localization.
	 *
	 * @return string the ISO code.
	 */
	public function getLanguageIsoCode()
	{
		return $this->_languageCode;
	}
}
