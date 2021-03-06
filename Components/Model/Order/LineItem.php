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
 * Model for order line item information. This is used when compiling the info
 * about an order that is sent to Nosto.
 *
 * Extends Shopware_Plugins_Frontend_NostoTagging_Components_Model_Base.
 * Implements NostoOrderPurchasedItemInterface.
 *
 * @package Shopware
 * @subpackage Plugins_Frontend
 */
class Shopware_Plugins_Frontend_NostoTagging_Components_Model_Order_LineItem extends Shopware_Plugins_Frontend_NostoTagging_Components_Model_Base implements NostoOrderPurchasedItemInterface
{
	/**
	 * @var string the unique identifier of the purchased item.
	 * If this item is for discounts or shipping cost, the id can be 0.
	 */
	protected $_productId;

	/**
	 * @var int the quantity of the item included in the order.
	 */
	protected $_quantity;

	/**
	 * @var string the name of the item included in the order.
	 */
	protected $_name;

	/**
	 * @var float The unit price of the item included in the order.
	 */
	protected $_unitPrice;

	/**
	 * @var string the 3-letter ISO code (ISO 4217) for the item currency.
	 */
	protected $_currencyCode;

	/**
	 * Populates the order line item with data from the order detail model.
	 *
	 * @param \Shopware\Models\Order\Detail $detail the order detail model.
	 */
	public function loadData(\Shopware\Models\Order\Detail $detail)
	{
		$this->_productId = -1;

		if ($detail->getArticleId() > 0) {
			// If this is a product variation, we need to load the parent
			// article to fetch it's number and name.
			$article = Shopware()->Models()->find('Shopware\Models\Article\Article', $detail->getArticleId());
			if (!empty($article)) {
				$this->_productId = $article->getMainDetail()->getNumber();
			}
		}

		$this->_name = $detail->getArticleName();
		$this->_quantity = (int)$detail->getQuantity();
		$this->_unitPrice = Nosto::helper('price')->format($detail->getPrice());
		$this->_currencyCode = strtoupper($detail->getOrder()->getCurrency());
	}

	/**
	 * Loads a special item, e.g. shipping cost.
	 *
	 * @param string           $name the name of the item.
	 * @param float|int|string $price the unit price of the item.
	 * @param string           $currency the 3-letter ISO code (ISO 4217) for the item currency.
	 */
	public function loadSpecialItemData($name, $price, $currency)
	{
		$this->_productId = -1;
		$this->_quantity = 1;
		$this->_name = $name;
		$this->_unitPrice = Nosto::helper('price')->format($price);
		$this->_currencyCode = strtoupper($currency);
	}

	/**
	 * @inheritdoc
	 */
	public function getProductId()
	{
		return $this->_productId;
	}

	/**
	 * @inheritdoc
	 */
	public function getQuantity()
	{
		return $this->_quantity;
	}

	/**
	 * @inheritdoc
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * @inheritdoc
	 */
	public function getUnitPrice()
	{
		return $this->_unitPrice;
	}

	/**
	 * @inheritdoc
	 */
	public function getCurrencyCode()
	{
		return $this->_currencyCode;
	}
}
