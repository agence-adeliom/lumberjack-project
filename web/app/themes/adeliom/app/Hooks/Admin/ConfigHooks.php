<?php

declare(strict_types=1);

namespace App\Hooks\Admin;

use Adeliom\Lumberjack\Hooks\Models\Action;
use Adeliom\Lumberjack\Hooks\Models\Filter;
use App\Admin\ParametersAdmin;

/**
 * Class ConfigHooks
 *
 * @package App\Hooks\Admin
 */
class ConfigHooks
{
    /**
     * @Action("acf/init")
     */
    #[Action("acf/init")]
    public static function googleApiKey(): void
    {
        if (function_exists('acf_update_setting')) {
            $acfField = get_field(ParametersAdmin::CONFIG_KEY, 'option');
            $googleMapKey = isset($acfField['gmap']) ? $acfField['gmap'] : '';
            acf_update_setting('google_api_key', (empty($googleMapKey) ? '' : $googleMapKey));
        }
    }

    /**
     * Set pretty permalinks
     *
     * @Action("after_switch_theme")¨
     */
    #[Action("after_switch_theme")]
    public static function prettyPermalinks(): void
    {
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure('/%postname%/');
        flush_rewrite_rules();
    }

    /**
     * @Action("map_meta_cap", 10, 4)
     */
    #[Action("map_meta_cap", 10, 4)]
    public static function customManagePrivacyOptions($caps, $cap, $user_id, $args)
    {
        if ($cap === 'manage_privacy_options') {
            $manage_name = is_multisite() ? 'manage_network' : 'manage_options';
            $caps = array_diff($caps, [$manage_name]);
        }

        return $caps;
    }

    /**
     * @Filter("redirection_role")
     */
    #[Filter("redirection_role")]
    public static function accessRedirectionEditor(): string
    {
        return 'edit_pages';
    }

    /**
     * Ajout menu dans la sidebar pour les éditeurs
     * @Action("admin_menu")
     */
    #[Action("admin_menu")]
    public static function changeMenuPosition(): void
    {
        $userRole = get_role('editor');
        if ($userRole !== null) {
            $userRole->add_cap('edit_theme_options');
        }

        // Remove old menu
        remove_submenu_page('themes.php', 'nav-menus.php');

        //Add new menu page
        add_menu_page(
            'Menus',
            'Menus',
            'edit_theme_options',
            'nav-menus.php',
            '', // @phpstan-ignore-line
            'dashicons-list-view',
            68
        );

        if (current_user_can('editor')) {
            remove_menu_page('themes.php');
        }
    }
}
