<?php
/**
 *
 * Hide Memberlist. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, [Dimetrodon], https://phpbbforever.com/home
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace eunaumtenhoid\hidememberlist\migrations;

class install extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v320\v320'];
	}

	public function update_data()
	{
		return [
			['permission.add', ['u_view_memberlist', true]],
			['permission.permission_set', ['ROLE_ADMIN_FULL', 'u_view_memberlist']],
			['permission.permission_set', ['ROLE_ADMIN_STANDARD', 'u_view_memberlist']],
		];
	}
}
