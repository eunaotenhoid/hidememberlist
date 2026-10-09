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
	'MEMBERLIST_FULL_BLOCKED'		=> 'Você não tem permissão para visualizar a lista de membros.',
	'MEMBERLIST_GROUP_BLOCKED'		=> 'Você não tem permissão para visualizar os detalhes do grupo.',
	'MEMBERLIST_TEAM_BLOCKED'		=> 'Você não tem permissão para visualizar a equipe do fórum.',
	'MEMBERLIST_SEARCHUSER_BLOCKED'		=> 'Você não tem permissão para pesquisar usuários.',
	'PHP_VERSION_TOO_LOW'			=> 'Esta extensão requer PHP 7.4 ou superior.',
	'ACL_U_VIEW_MEMBERLIST'			=> 'Pode visualizar a lista de membros e grupos',
]);
