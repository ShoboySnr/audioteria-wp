<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

$text_align = is_rtl() ? 'right' : 'left';

do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>

<h2>

	<?php
	if ( $sent_to_admin ) {
		$before = '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">';
		$after  = '</a>';
	} else {
		$before = '<p style="color: #a32f2f">';
		$after  = '</p>';
	}
	/* translators: %s: Order ID. */
	echo wp_kses_post( $before . sprintf( __( 'Order Number: %s', 'woocommerce' ) . $after, $order->get_order_number(), ) );

	echo wp_kses_post( sprintf( ' <time datetime="%s">%s</time>', $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) ) );
	echo '<br>'
	?>
    <br>
</h2>

<div style="margin-bottom: 40px;">
    <div class="order-summary">
        <h3 class="order-heading"><?php esc_html_e( 'Order Summary', 'audioteria-wp' ) ?></h3>


		<?php
		echo wc_get_email_order_items( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$order,
			array(
				'show_sku'      => $sent_to_admin,
				'show_image'    => true,
				'image_size'    => array( 117, 117 ),
				'plain_text'    => $plain_text,
				'sent_to_admin' => $sent_to_admin,
			)
		);

		?>
    </div>

</div> <!-- order summary ends -->
<div class="order-breakdown">
	<?php
	$item_totals = $order->get_order_item_totals();

	if ( $item_totals ) {
		$i = 0;
		foreach ( $item_totals as $total ) {
			$i ++;
			?>
            <p style="display: flex;justify-content: space-between !important;font-size: <?= $i == 3 ? '24px' : '18px' ?>;gap: 10px;font-weight: font-size: <?= $i == 3 ? '600' : '400' ?>;">
                <span style="margin-right: 74%;"><?php echo wp_kses_post( $total['label'] ); ?></span>
                <span><?php echo wp_kses_post( $total['value'] ); ?></span>
            </p>

			<?php
		}
	}
	if ( $order->get_customer_note() ) {
		?>
        <tr>
            <th class="td" scope="row" colspan="2"
                style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Note:', 'woocommerce' ); ?></th>
            <td class="td"
                style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
        </tr>
		<?php
	}
	?>
</div>

</div>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>
