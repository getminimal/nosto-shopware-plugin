<?php
/**
 * Copyright (c) 2016, Nosto Solutions Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice,
 * this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation
 * and/or other materials provided with the distribution.
 *
 * 3. Neither the name of the copyright holder nor the names of its contributors
 * may be used to endorse or promote products derived from this software without
 * specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Nosto Solutions Ltd <shopware@nosto.com>
 * @copyright Copyright (c) 2016 Nosto Solutions Ltd (http://www.nosto.com)
 * @license http://opensource.org/licenses/BSD-3-Clause BSD 3-Clause
 */

/**
 * Helper class for tags
 *
 * @package Shopware
 * @subpackage Plugins_Frontend
 * @author Nosto Solutions Ltd <shopware@nosto.com>
 * @copyright Copyright (c) 2016 Nosto Solutions Ltd (http://www.nosto.com)
 */
class Shopware_Plugins_Frontend_NostoTagging_Components_Helper_Tag
{
	/**
	 * Builds the default tag list for a product.
	 *
	 * Also includes the custom "add-to-cart" tag if the product can be added to
	 * the shopping cart directly without any action from the user, e.g. the
	 * product cannot have any variations or  choices. This tag is then used in
	 * the recommendations to render the "Add to cart" button for the product
	 * when it is recommended to a user.
	 *
	 * @param \Shopware\Models\Article\Article $article
	 * @param \Shopware\Models\Shop\Shop $shop
	 * @return array
	 */
	public static function buildProductTags(\Shopware\Models\Article\Article $article, \Shopware\Models\Shop\Shop $shop)
	{
		$tags = array();

		// If the product does not have any variants, then it can be added to
		// the shopping cart directly from the recommendations.
		$configuratorSet = $article->getConfiguratorSet();
		if (empty($configuratorSet)) {
			$tags1 = array(Shopware_Plugins_Frontend_NostoTagging_Components_Model_Product::ADD_TO_CART);
		}

		$tags['tag1'] = $tags1;

		try{
			$pricePerUnit = Shopware_Plugins_Frontend_NostoTagging_Components_Helper_Price::generatePricePerUnit(
				$article,
				$shop
			);
			if ($pricePerUnit) {
				$tags['tag2'] = array($pricePerUnit);
			}
		} catch (\Exception $e) {
			Shopware()->PluginLogger()->warning(
				sprintf(
					'Could not create price per unit. Error was: %s (%s)',
					$e->getMessage(),
					$e->getCode()
				)
			);
		}
		return $tags;
	}

}