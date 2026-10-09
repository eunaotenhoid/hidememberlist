<?php
/**
 *
 * Hide Memberlist. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, [Dimetrodon]
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace eunaumtenhoid\hidememberlist;

/**
 * Hide Memberlist Extension base
 *
 * This contains important PHP checks
 * Of course this mammal-like reptile will use it
 */
class ext extends \phpbb\extension\base
{
	public function is_enableable()
	{
		$language= $this->container->get('language');
		$language->add_lang('common', 'eunaumtenhoid/hidememberlist');

			/**
			* Extension can only be executed in PHP 7.4 or higher.
			* Check PHP version to ensure compatibility.
			*/
		if (version_compare(PHP_VERSION, '7.4.0', '<'))
		{
			// Display error message and do not enable if php version is below 7.4
			return $language->lang('PHP_VERSION_TOO_LOW');
		}

		return true;
	}
}
