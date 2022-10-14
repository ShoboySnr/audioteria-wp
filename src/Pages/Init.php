<?php

namespace AudioteriaWP\Pages;


class Init
{

	public static function init()
	{
		Homepage::get_instance();
		AboutPage::get_instance();
		AccountPage::get_instance();
		ProductPage::get_instance();
		ProductArchive::get_instance();
		CartPage::get_instance();
		CheckoutPage::get_instance();
		AuthenticationPages::get_instance();
		SearchPage::get_instance();
	}
}