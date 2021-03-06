<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\PayPal\OrdersApi\Builder\Util;

use Psr\Log\LoggerInterface;
use Shopware\Core\Checkout\Order\Aggregate\OrderLineItem\OrderLineItemEntity;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\System\Currency\CurrencyEntity;
use Swag\PayPal\OrdersApi\Builder\Event\PayPalV2ItemFromOrderEvent;
use Swag\PayPal\RestApi\V2\Api\Order\PurchaseUnit\Item;
use Swag\PayPal\RestApi\V2\Api\Order\PurchaseUnit\Item\UnitAmount;
use Swag\PayPal\Util\PriceFormatter;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ItemListProvider
{
    private PriceFormatter $priceFormatter;

    private EventDispatcherInterface $eventDispatcher;

    private LoggerInterface $logger;

    public function __construct(
        PriceFormatter $priceFormatter,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger
    ) {
        $this->priceFormatter = $priceFormatter;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
    }

    /**
     * @return Item[]
     */
    public function getItemList(CurrencyEntity $currency, OrderEntity $order): array
    {
        $items = [];
        $currencyCode = $currency->getIsoCode();
        $lineItems = $order->getNestedLineItems();
        if ($lineItems === null) {
            return [];
        }

        foreach ($lineItems as $lineItem) {
            $item = new Item();
            $this->setName($lineItem, $item);
            $this->setSku($lineItem, $item);

            $unitAmount = new UnitAmount();
            $unitAmount->setCurrencyCode($currencyCode);
            $unitAmount->setValue($this->priceFormatter->formatPrice($lineItem->getUnitPrice()));

            $item->setUnitAmount($unitAmount);
            $item->setQuantity($lineItem->getQuantity());

            $event = new PayPalV2ItemFromOrderEvent($item, $lineItem);
            $this->eventDispatcher->dispatch($event);

            $items[] = $event->getPayPalLineItem();
        }

        return $items;
    }

    private function setName(OrderLineItemEntity $lineItem, Item $item): void
    {
        $label = $lineItem->getLabel();

        try {
            $item->setName($label);
        } catch (\LengthException $e) {
            $this->logger->warning($e->getMessage(), ['lineItem' => $lineItem]);
            $item->setName(\mb_substr($label, 0, Item::MAX_LENGTH_NAME));
        }
    }

    private function setSku(OrderLineItemEntity $lineItem, Item $item): void
    {
        $payload = $lineItem->getPayload();
        if ($payload === null || !\array_key_exists('productNumber', $payload)) {
            return;
        }

        $productNumber = $payload['productNumber'];

        try {
            $item->setSku($productNumber);
        } catch (\LengthException $e) {
            $this->logger->warning($e->getMessage(), ['lineItem' => $lineItem]);
            $item->setSku(\mb_substr($productNumber, 0, Item::MAX_LENGTH_SKU));
        }
    }
}
