<?php
/**
 * Email Header
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-header.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <style>

        *,
        html,
        body {
            font-size: 1em;
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            font-size: 1em;
            overflow-x: hidden;
            font-family: 'Poppins', sans-serif;
            width: 100% !important;
        }

        .logo {
            padding: 30px 40px;
        }

        .logo img {
            width: 120px;
        }

        .email-title {
            background-color: #9F0000;
            padding: 48px;
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            color: white;
        }

        main {
            padding: 68px 30px;
            border-bottom: 1px solid #F1F1F1;
        }

        a {
            color: #A32F2F;
            text-decoration: none;
        }

        footer {
            padding: 34px;
        }

        footer p {
            text-align: center;
        }


        span.red {
            color: #A32F2F;
        }

        a {
            color: #A32F2F;
            text-decoration: none;
        }

        .social-links {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin-top: 24px;
            gap: 10px !important;
        }

        .social-links a {
            display: block;
            height: 20px;
            width: 20px;
        }

        .social-links a img {
            width: 100%;
        }

        .social-links a svg {
            width: 100%;
        }


        .order-summary {
            padding: 0 20px;
            border: 1px solid #EBEBEB;
            border-radius: 10px;
            margin-top: 54px;
        }

        .order-heading {
            font-size: 20px;
            font-weight: 600;
            padding: 42px 0 20px;
            margin-bottom: 36px;
            border-bottom: 1px solid rgba(68, 68, 68, 0.15);
            color: #000;
        }

        .order-item {
            display: flex;
            gap: 24px;
            align-items: center;
            margin-bottom: 20px;
            text-align: left;
        }

        .order-item img {
            max-width: 117px;
            border-radius: 5px;
        }

        .order-title {
            font-size: 18px;
            font-weight: 600;
        }

        .order-author {
            font-size: 13px;
            font-weight: 600;
        }

        .order-price {
            font-weight: 700;
        }

        .order-breakdown {
            padding: 35px 20px 0;
        }

        .sub-total {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
        }

        .total div {
            display: flex;
            justify-content: space-between;
            font-size: 24px;
            font-weight: 600;
        }
    </style>
</head>

<body>
<center>
    <div style="max-width: 600px">
        <div class="logo">
			<?php
			if ( $img = get_option( 'woocommerce_email_header_image' ) ) {
				echo '<img src="' . esc_url( $img ) . '" alt="' . get_bloginfo( 'name', 'display' ) . '" />';
			}
			?>

        </div>
        <p class="email-title"><?php echo $email_heading; ?></p>
        <div id="body_content_inner" style="padding: 68px 30px;border-bottom: 1px solid #F1F1F1;">