<?php

/**
 * Created by Vextras.
 *
 * Name: Ryan Hungate
 * Email: ryan@vextras.com
 * Date: 3/8/16
 * Time: 2:16 PM
 */
class MailChimp_WooCommerce_Order {

	protected $id                   = null;
	protected $landing_site         = null;
	protected $customer             = null;
	protected $campaign_id          = null;
	protected $financial_status     = null;
	protected $fulfillment_status   = null;
	protected $currency_code        = null;
	protected $order_total          = null;
	protected $tax_total            = null;
	protected $discount_total       = null;
	protected $shipping_total       = null;
	protected $updated_at_foreign   = null;
	protected $processed_at_foreign = null;
	protected $cancelled_at_foreign = null;
	protected $order_url            = null;
	protected $shipping_address     = null;
	protected $billing_address      = null;
	protected $lines                = array();
	protected $confirm_and_paid     = false;
	protected $promos               = array();
	protected $is_amazon_order      = false;
	protected $is_privacy_protected = false;
	protected $original_woo_status  = null;
	protected $ignore_if_new        = false;
	protected $tracking_url         = '';
	protected $tracking_number      = '';
	protected $tracking_carrier     = '';
	protected $processed_at         = null;

	/**
	 * @param $bool
	 * @return $this
	 */
	public function flagAsAmazonOrder( $bool ) {
		$this->is_amazon_order = (bool) $bool;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isFlaggedAsAmazonOrder() {
		return (bool) $this->is_amazon_order;
	}

	/**
	 * @param $bool
	 * @return $this
	 */
	public function flagAsPrivacyProtected( $bool ) {
		$this->is_privacy_protected = (bool) $bool;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isFlaggedAsPrivacyProtected() {
		return (bool) $this->is_privacy_protected;
	}

	/**
	 * @param $status
	 *
	 * @return $this
	 */
	public function setOriginalWooStatus( $status ) {
		$this->original_woo_status = (string) $status;
		return $this;
	}

	/**
	 * @return null|string
	 */
	public function getOriginalWooStatus() {
		return $this->original_woo_status;
	}

	/**
	 * @return bool
	 */
	public function shouldIgnoreIfNotInMailchimp() {
		return (bool) $this->ignore_if_new;
	}

	/**
	 * @param $bool
	 * @return $this
	 */
	public function flagAsIgnoreIfNotInMailchimp( $bool ) {
		$this->ignore_if_new = (bool) $bool;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getValidation() {
		return array(
			'id'                   => 'required|string',
			'landing_site'         => 'required|string',
			'customer'             => 'required',
			'financial_status'     => 'string',
			'fulfillment_status'   => 'string',
			'currency_code'        => 'required|currency_code',
			'order_total'          => 'required|numeric',
			'tax_total'            => 'numeric',
			'discount_total'       => 'numeric',
			'processed_at_foreign' => 'date',
			'updated_at_foreign'   => 'date',
			'cancelled_at_foreign' => 'date',
			'order_url'            => 'string',
			'lines'                => 'required|array',
		);
	}

	/**
	 * @param $id
	 * @return MailChimp_WooCommerce_Order
	 */
	public function setId( $id ) {
		// old regex preg_replace('/[^0-9]/i','', $id);
		$this->id = preg_replace( '/[^a-zA-Z\d\-_]/', '', $id );
		return $this;
	}

	/**
	 * @return null|string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param $landing_site
	 * @return $this
	 */
	public function setLandingSite( $landing_site ) {
		$this->landing_site = $landing_site;

		return $this;
	}

	/**
	 * @return null|string
	 */
	public function getLandingSite() {
		return $this->landing_site;
	}

	/**
	 * @param MailChimp_WooCommerce_Customer $customer
	 * @return MailChimp_WooCommerce_Order
	 */
	public function setCustomer( MailChimp_WooCommerce_Customer $customer ) {
		$this->customer = $customer;

		return $this;
	}

	/**
	 * @return null|MailChimp_WooCommerce_Customer
	 */
	public function getCustomer() {
		if ( empty( $this->customer ) ) {
			$this->customer = new MailChimp_WooCommerce_Customer();
		}
		return $this->customer;
	}

	/**
	 * @param MailChimp_WooCommerce_LineItem $item
	 * @return $this
	 */
	public function addItem( MailChimp_WooCommerce_LineItem $item ) {
		$this->lines[] = $item;
		return $this;
	}

	/**
	 * @param $code
	 * @param $amount
	 * @param bool   $is_percentage
	 * @return $this
	 */
	public function addDiscount( $code, $amount, $is_percentage = false ) {
		$this->promos[] = array(
			'code'              => $code,
			'amount_discounted' => $amount,
			'type'              => $is_percentage ? 'percent' : 'fixed',
		);

		return $this;
	}

	/**
	 * @return array
	 */
	public function discounts() {
		return $this->promos;
	}

	/**
	 * @return array
	 */
	public function items() {
		return $this->lines;
	}

	/**
	 * @return null
	 */
	public function getCampaignId() {
		return $this->campaign_id;
	}

	/**
	 * @param $campaign_id
	 *
	 * @return $this
	 */
	public function setCampaignId( $campaign_id ) {
		$this->campaign_id = $campaign_id;

		return $this;
	}

	/**
	 * @return null
	 */
	public function getFinancialStatus() {
		return $this->financial_status;
	}

	/**
	 * @param null $financial_status
	 * @return MailChimp_WooCommerce_Order
	 */
	public function setFinancialStatus( $financial_status ) {
		$this->financial_status = $financial_status;

		return $this;
	}

	/**
	 * @return null
	 */
	public function getFulfillmentStatus() {
		return $this->fulfillment_status;
	}

	/**
	 * @param null $fulfillment_status
	 * @return MailChimp_WooCommerce_Order
	 */
	public function setFulfillmentStatus( $fulfillment_status ) {
		$this->fulfillment_status = $fulfillment_status;

		return $this;
	}

	/**
	 * @return null
	 */
	public function getCurrencyCode() {
		return ! empty( $this->currency_code ) ? $this->currency_code : 'USD';
	}

	/**
	 * @param null $code
	 * @return MailChimp_WooCommerce_Order
	 */
	public function setCurrencyCode( $code = null ) {
		if ( ! empty( $code ) ) {
			$this->currency_code = $code;
			return $this;
		}

		try {
			$woo                 = wc_get_order( $this->id );
			$this->currency_code = $woo->get_currency();
		} catch ( Exception $e ) {
			$this->currency_code = get_woocommerce_currency();
		}

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getOrderTotal() {
		return $this->order_total;
	}

	/**
	 * @param mixed $order_total
	 * @return MailChimp_WooCommerce_Order
	 */
	public function setOrderTotal( $order_total ) {
		$this->order_total = $order_total;

		return $this;
	}

	/**
	 * @param $url
	 * @return $this
	 */
	public function setOrderURL( $url ) {
		if ( ( $url = wp_http_validate_url( $url ) ) ) {
			$this->order_url = $url;
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function getOrderURL() {
		return $this->order_url;
	}

	/**
	 * @return mixed
	 */
	public function getTaxTotal() {
		return $this->tax_total;
	}

	/**
	 * @param mixed $tax_total
	 * @return MailChimp_WooCommerce_Order
	 */
	public function setTaxTotal( $tax_total ) {
		$this->tax_total = $tax_total;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getShippingTotal() {
		return $this->shipping_total;
	}

	/**
	 * @param mixed $shipping_total
	 * @return MailChimp_WooCommerce_Order
	 */
	public function setShippingTotal( $shipping_total ) {
		$this->shipping_total = $shipping_total;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDiscountTotal() {
		return $this->discount_total;
	}

	/**
	 * @param mixed $discount_total
	 * @return MailChimp_WooCommerce_Order
	 */
	public function setDiscountTotal( $discount_total ) {
		$this->discount_total = $discount_total;

		return $this;
	}

	/**
	 * @param DateTime $time
	 * @return $this
	 */
	public function setProcessedAt( DateTime $time ) {
		$this->processed_at_foreign = $time->format( 'Y-m-d H:i:s' );
		$this->processed_at = $time;
		return $this;
	}

	/**
	 * @return null|DateTime
	 */
	public function getProcessedAt() {
		return $this->processed_at_foreign;
	}

	public function getProcessedAtDate() {
		return !empty($this->processed_at) ?
			$this->processed_at :
			(!empty($this->processed_at_foreign) ? new DateTime($this->processed_at_foreign) : null);
	}

	/**
	 * @param DateTime $time
	 * @return $this
	 */
	public function setCancelledAt( DateTime $time ) {
		$this->cancelled_at_foreign = $time->format( 'Y-m-d H:i:s' );

		return $this;
	}

	/**
	 * @return null
	 */
	public function getCancelledAt() {
		return $this->cancelled_at_foreign;
	}

	/**
	 * @param DateTime $time
	 * @return $this
	 */
	public function setUpdatedAt( DateTime $time ) {
		$this->updated_at_foreign = $time->format( 'Y-m-d H:i:s' );

		return $this;
	}

	/**
	 * @return null
	 */
	public function getUpdatedAt() {
		return $this->updated_at_foreign;
	}

	/**
	 * @return Array lines_ids
	 */
	public function getLinesIds() {
		foreach ( $this->lines as $line ) {
			$lines_ids[] = $line->getId();
		}
		return $lines_ids;
	}

	/**
	 * @param $bool
	 *
	 * @return $this
	 */
	public function confirmAndPay( $bool ) {
		$this->confirm_and_paid = (bool) $bool;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function shouldConfirmAndPay() {
		return $this->confirm_and_paid;
	}

	/**
	 * @param MailChimp_WooCommerce_Address $address
	 * @return $this
	 */
	public function setShippingAddress( MailChimp_WooCommerce_Address $address ) {
		$this->shipping_address = $address;

		return $this;
	}

	/**
	 * @return MailChimp_WooCommerce_Address
	 */
	public function getShippingAddress() {
		if ( empty( $this->shipping_address ) ) {
			$this->shipping_address = new MailChimp_WooCommerce_Address();
		}
		return $this->shipping_address;
	}

	/**
	 * @param MailChimp_WooCommerce_Address $address
	 * @return $this
	 */
	public function setBillingAddress( MailChimp_WooCommerce_Address $address ) {
		$this->billing_address = $address;

		return $this;
	}

	/**
	 * @return MailChimp_WooCommerce_Address
	 */
	public function getBillingAddress() {
		if ( empty( $this->billing_address ) ) {
			$this->billing_address = new MailChimp_WooCommerce_Address();
		}
		return $this->billing_address;
	}

	/**
	 * @param string $url
	 *
	 * @return mixed|string|void
	 */
	public function setTrackingUrl( $url = '' ) {

		if ( ! empty( $url ) ) {
			return $this->tracking_url = $url; }

		$tracking_url = '';
		// Taken from woocommercer-services plugin example
		if ( ! empty( $this->tracking_number ) && ! empty( $this->tracking_carrier ) ) {
			switch ( $this->tracking_carrier ) {
				case 'fedex':
					$tracking_url = 'https://www.fedex.com/apps/fedextrack/?action=track&tracknumbers=' . $this->tracking_number;
					break;
				case 'usps':
					$tracking_url = 'https://tools.usps.com/go/TrackConfirmAction.action?tLabels=' . $this->tracking_number;
					break;
				case 'ups':
					$tracking_url = 'https://www.ups.com/track?tracknum=' . $this->tracking_number;
					break;
				case 'dhlexpress':
					$tracking_url = 'https://www.dhl.com/en/express/tracking.html?AWB=' . $this->tracking_number . '&brand=DHL';
					break;
			}
		}

		$this->tracking_url = $tracking_url;
		return;
	}
	/**
	 * @return string               Tracking url
	 */
	public function getTrackingUrl() {
		return $this->tracking_url;
	}

	/**
	 * @param $tracking_number
	 */
	public function setTrackingNumber( $tracking_number ) {
		$this->tracking_number = $tracking_number;
	}
	/**
	 * @return string
	 */
	public function getTrackingNumber() {
		return $this->tracking_number;
	}

	/**
	 * @param $tracking_carrier
	 */
	public function setTrackingCarrier( $tracking_carrier ) {
		$this->tracking_carrier = $tracking_carrier;
	}
	/**
	 * @return string               Tracking carrier
	 */
	public function getTrackingCarrier() {
		return $this->tracking_carrier;
	}
	/**
	 * @return array
	 */
	public function toArray() {
		$this->setTrackingInfo();
		return mailchimp_array_remove_empty(
			array(
				'id'                   => (string) $this->getId(),
				'landing_site'         => (string) $this->getLandingSite(),
				'customer'             => $this->getCustomer()->toArray(),
				'financial_status'     => (string) $this->getFinancialStatus(),
				'fulfillment_status'   => (string) $this->getFulfillmentStatus(),
				'currency_code'        => (string) $this->getCurrencyCode(),
				'order_total'          => floatval( $this->getOrderTotal() ),
				'order_url'            => (string) $this->getOrderURL(),
				'tax_total'            => floatval( $this->getTaxTotal() ),
				'discount_total'       => floatval( $this->getDiscountTotal() ),
				'shipping_total'       => floatval( $this->getShippingTotal() ),
				'processed_at_foreign' => (string) $this->getProcessedAt(),
				'cancelled_at_foreign' => (string) $this->getCancelledAt(),
				'updated_at_foreign'   => (string) $this->getUpdatedAt(),
				'shipping_address'     => $this->getShippingAddress()->toArray(),
				'billing_address'      => $this->getBillingAddress()->toArray(),
				'promos'               => ! empty( $this->promos ) ? $this->promos : null,
				'tracking_number'      => $this->getTrackingNumber(),
				'tracking_url'         => $this->getTrackingUrl(),
				'tracking_carrier'     => $this->getTrackingCarrier(),
				'lines'                => array_map(
					function ( $item ) {
						/** @var MailChimp_WooCommerce_LineItem $item */
						return $item->toArray();
					},
					$this->items()
				),
			)
		);
	}

	/**
	 * @param array $data
	 * @return MailChimp_WooCommerce_Order
	 */
	public function fromArray( array $data ) {
		$singles = array(
			'id',
			'landing_site',
			'financial_status',
			'fulfillment_status',
			'currency_code',
			'order_total',
			'order_url',
			'tax_total',
			'discount_total',
			'processed_at_foreign',
			'cancelled_at_foreign',
			'updated_at_foreign',
			'tracking_carrier',
			'tracking_number',
			'tracking_url',
		);

		foreach ( $singles as $key ) {
			if ( array_key_exists( $key, $data ) ) {
				$this->$key = $data[ $key ];
			}
		}

		if ( array_key_exists( 'shipping_address', $data ) && is_array( $data['shipping_address'] ) ) {
			$shipping               = new MailChimp_WooCommerce_Address();
			$this->shipping_address = $shipping->fromArray( $data['shipping_address'] );
		}

		if ( array_key_exists( 'billing_address', $data ) && is_array( $data['billing_address'] ) ) {
			$billing               = new MailChimp_WooCommerce_Address();
			$this->billing_address = $billing->fromArray( $data['billing_address'] );
		}

		if ( array_key_exists( 'promos', $data ) ) {
			$this->promos = $data['promos'];
		}

		if ( array_key_exists( 'lines', $data ) && is_array( $data['lines'] ) ) {
			$this->lines = array();
			foreach ( $data['lines'] as $line_item ) {
				$item          = new MailChimp_WooCommerce_LineItem();
				$this->lines[] = $item->fromArray( $line_item );
			}
		}

		if ( array_key_exists( 'customer', $data ) ) {
			$customer_object = new MailChimp_WooCommerce_Customer();
			$this->setCustomer( $customer_object->fromArray( $data['customer'] ) );
		}

		// apply the campaign id from the response if there is one.
		if (array_key_exists('outreach', $data) && !empty($data['outreach']) && array_key_exists('id', $data['outreach'])) {
			$this->setCampaignId($data['outreach']['id']);
		}

		return $this;
	}
	/**
	 * Set Tracking info before the job gets executed
	 */
	public function setTrackingInfo() {
		// Support for woocommerce shipment tracking plugin (https://woocommerce.com/products/shipment-tracking)
		if ( function_exists( 'wc_st_add_tracking_number' ) && class_exists( 'WC_Shipment_Tracking_Actions' ) ) {
			$trackings = get_post_meta( (int) $this->getId(), '_wc_shipment_tracking_items', true );
			if ( empty( $trackings ) ) {
				return;
			}
			foreach ( $trackings as $tracking ) {
				// carrier
				if ( ! empty( $tracking['custom_tracking_provider'] ) ) {
					$this->setTrackingCarrier( $tracking['custom_tracking_provider'] );
				} elseif ( ! empty( $tracking['tracking_provider'] ) ) {
					$this->setTrackingCarrier( $tracking['tracking_provider'] );
				}
				// tracking url
				$ship = WC_Shipment_Tracking_Actions::get_instance();
				$url  = $ship->get_formatted_tracking_item( $this->getId(), $tracking );
				$this->setTrackingUrl( $url['formatted_tracking_link'] );
				// tracking number
				$this->setTrackingNumber( $tracking['tracking_number'] );
				return;
			}
		}

		// Support for woocommerce shipping plugin (https://woocommerce.com/woocommerce-shipping/)
		if ( class_exists( 'WC_Connect_Loader' ) ) {
			$label_data = get_post_meta( (int) $this->getId(), 'wc_connect_labels', true );
			// return an empty array if the data doesn't exist.
			if ( empty( $label_data ) ) {
				return;
			}
			if ( ! is_array( $label_data ) && is_string( $label_data ) ) {
				$label_data = json_decode( $label_data, true );
			}
			// labels stored as an array, return.
			if ( is_array( $label_data ) ) {
				foreach ( $label_data as $label ) {
					if ( ! empty( $label['tracking'] ) && ! empty( $label['carrier_id'] ) ) {
						$this->setTrackingNumber( $label['tracking'] );
						$this->setTrackingCarrier( $label['carrier_id'] );
						$this->setTrackingUrl();
					}
				}
			}
			return;
		}
	}
}
