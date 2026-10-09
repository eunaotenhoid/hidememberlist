<?php
/**
 *
 * Hide Memberlist. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, [Dimetrodon]
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [
	'MEMBERLIST_FULL_BLOCKED'		=> 'Sorry but you are not permitted to view the memberlist.',
	'MEMBERLIST_GROUP_BLOCKED'		=> 'Sorry but you are not permitted to view group details.',
	'MEMBERLIST_TEAM_BLOCKED'		=> 'Sorry but you are not permitted to view the team page.',
	'MEMBERLIST_SEARCHUSER_BLOCKED'		=> 'Sorry but you are not permitted to search users.',
	'PHP_VERSION_TOO_LOW'			=> 'This extension requires PHP 7.4 or above.',
	'ACL_U_VIEW_MEMBERLIST'			=> 'Can view memberlist and group lists',
]);
