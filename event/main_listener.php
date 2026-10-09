<?php
/**
 *
 * Hide Memberlist. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, [Dimetrodon]
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace eunaumtenhoid\hidememberlist\event;

use phpbb\auth\auth;
use phpbb\language\language;
use phpbb\template\context;
use phpbb\template\twig\twig;
use phpbb\user;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Hide Memberlist Event listener.
 */
class main_listener implements EventSubscriberInterface
{
	/** @var auth */
	protected $auth;

	/** @var language */
	protected $language;

	/** @var twig */
	protected $twig;

	/** @var user */
	protected $user;

	/** @var context */
	protected $context;

	public function __construct(
		auth $auth,
		language $language,
		twig $twig,
		user $user,
		context $context
	)
	{
		$this->auth = $auth;
		$this->language = $language;
		$this->twig = $twig;
		$this->user = $user;
		$this->context = $context;
	}

	public static function getSubscribedEvents(): array
	{
		return [
			'core.permissions'			=> 'add_permissions',
			'core.user_setup'			=> 'load_language_on_setup',
			'core.page_header_after'	=> 'header_after',
		];
	}

	/**
	 * Add custom permission to phpBB permission system
	 *
	 * @param \phpbb\event\data $event
	 * @return void
	 */
	public function add_permissions($event): void
	{
		$permissions = $event['permissions'];
		$permissions['u_view_memberlist'] = [
			'lang' => 'ACL_U_VIEW_MEMBERLIST',
			'cat'  => 'profile',
		];
		$event['permissions'] = $permissions;
	}

	/**
	 * Load language files during user setup
	 *
	 * @param \phpbb\event\data $event
	 * @return void
	 */
	public function load_language_on_setup($event): void
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'eunaumtenhoid/hidememberlist',
			'lang_set' => 'common',
		];
		$lang_set_ext[] = [
			'ext_name' => 'eunaumtenhoid/hidememberlist',
			'lang_set' => 'permissions_hidememberlist',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	 * Check if current user has permission to view memberlist
	 *
	 * @return bool
	 */
	private function can_view_memberlist(): bool
	{
		// Only Founders always have automatic bypass
		if (!empty($this->user->data['user_type']) && $this->user->data['user_type'] == USER_FOUNDER)
		{
			return true;
		}

		// Check permission for all other users/groups
		return (bool) $this->auth->acl_get('u_view_memberlist');
	}

	/**
	 * Loads after the page header.
	 * If user has no permission to view memberlist, restricts memberlist, team, and group links.
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function header_after($event): void
	{
		// If user has permission, allow normal access
		if ($this->can_view_memberlist())
		{
			return;
		}

		// Restrict access for users without permission:
		// 1. Hide memberlist link and team page link from navigation/footer
		$this->twig->assign_vars([
			'S_DISPLAY_MEMBERLIST'	=> false,
			'U_TEAM'				=> false,
		]);

		// 2. Remove clickable links from group names in legend
		$this->unlink_legend_groups();

		// 3. Check if accessing memberlist page directly
		$page = $this->user->page['page'];
		if (strpos($page, 'memberlist') !== false)
		{
			// If viewing profiles is completely disabled by phpBB core permissions, do nothing
			if (!$this->auth->acl_get('u_viewprofile'))
			{
				return;
			}

			// Exclude modes that should remain accessible (viewing profile, contact, email)
			$exclude = ['viewprofile', 'email', 'contactadmin'];

			$mode = '';
			if (strpos($page, 'mode') !== false)
			{
				$mode = substr($page, strpos($page, 'mode') + 5);
				$mode = explode('/', str_replace(['=', '&'], '/', $mode))[0];
			}

			if (in_array($mode, $exclude))
			{
				return;
			}

			// Load extension language file
			$this->language->add_lang('common', 'eunaumtenhoid/hidememberlist');

			// Select message based on mode
			$message = 'MEMBERLIST_' . strtoupper($mode) . '_BLOCKED';
			if ($this->language->is_set($message) !== true)
			{
				$message = 'MEMBERLIST_FULL_BLOCKED';
			}

			trigger_error($message);
		}
	}

	/**
	 * Strips <a> links from the legend footer, leaving styled <span> elements.
	 *
	 * @return void
	 */
	private function unlink_legend_groups(): void
	{
		$rootref = &$this->context->get_root_ref();
		if (isset($rootref['LEGEND']) && is_string($rootref['LEGEND']) && $rootref['LEGEND'] !== '')
		{
			$rootref['LEGEND'] = preg_replace_callback(
				'/<a\s+([^>]*?)href=["\'][^"\']*memberlist[^"\']*["\']([^>]*?)>(.*?)<\/a>/is',
				function ($matches) {
					$attrs = trim($matches[1] . ' ' . $matches[2]);
					return '<span' . ($attrs ? ' ' . $attrs : '') . '>' . $matches[3] . '</span>';
				},
				$rootref['LEGEND']
			);
		}
	}
}
