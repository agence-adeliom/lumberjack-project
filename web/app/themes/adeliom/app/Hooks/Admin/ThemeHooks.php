<?php

declare(strict_types=1);

namespace App\Hooks\Admin;

use Adeliom\Lumberjack\Assets\Assets;
use Adeliom\Lumberjack\Hooks\Models\Action;
use Adeliom\Lumberjack\Hooks\Models\Filter;

/**
 * Class ThemeHooks
 *
 * @package App\Hooks\Admin
 */
class ThemeHooks
{
    #[Filter("timber_context")]
    public static function globalContext($context)
    {
        $options = get_fields('option');

        $context['link'] = isset($options['link']) ?: null;
        $context['config'] = isset($options['config']) ?: null;

        return $context;
    }
    /**
     * Ajout des scripts de base
     */
    #[Action("wp_enqueue_scripts")]
    public static function enqueueScripts(): void
    {
        Assets::enqueue('scripts/global', 'scripts/global', []);
        Assets::enqueue('styles/global', 'styles/global', []);
    }

    /**
     * Ajouter une feuille de style pour l’éditeur dans l’admin
     */
    #[Action("enqueue_block_editor_assets")]
    public static function editorStyle(): void
    {
        Assets::enqueue('styles/editor', 'styles/editor', []);
    }

    /**
     * Remove comment from admin menu
     */
    #[Action("admin_menu", 15)]
    public static function removeCommentFromMenu(): void
    {
        remove_menu_page('edit-comments.php');
        remove_submenu_page('themes.php', 'customize.php?return=' . urlencode($_SERVER['SCRIPT_NAME']));

        if (!is_super_admin()) {
            remove_menu_page('tools.php');
        }
    }

    /**
     * Remove manage options from post (options in topbar)
     */
    #[Action("screen_options_show_screen")]
    public static function removeManageOptions(): bool
    {
        return current_user_can('manage_options');
    }

    /**
     * Remove all notices
     */
    #[Action("init", 100)]
    public static function removeAllNotices(): void
    {
        if (!is_super_admin()) {
            remove_all_actions('admin_notices');
        }
    }

    #[Action("login_enqueue_scripts")]
    #[Action("admin_enqueue_scripts")]
    public static function themeStyle(): void
    {
        if (!current_user_can('manage_options')) {
            // Remove metaboxes
            echo '<style>.update-nag, .updated, .error, .is-dismissible { display: none !important; }</style>';
            // Remove edit slug btn
            echo '<style>#edit-slug-buttons { display: none !important; }</style>';
            // Remove create media & post
            echo '<style>#wp-admin-bar-new-post, #wp-admin-bar-new-media { display: none !important; }</style>';
        }
    }

    /**
     * Supprimer l’entrée “Personnaliser” dans la top bar (FO)
     */
    #[Action("wp_before_admin_bar_render")]
    public static function beforeAdminMenuRender(): void
    {
        global $wp_admin_bar;
        if (!is_super_admin()) {
            $wp_admin_bar->remove_menu('customize');
        }
    }

    /**
     * Supprimer les entrées pour créer un nouveau média ou un nouvel article (quand on les désactives) depuis la top bar (FO)
     */
    #[Action("admin_bar_menu", 999)]
    public static function removeEntriesFromAdminBar(): void
    {
        global $wp_admin_bar;
        $wp_admin_bar->remove_node('new-post');
        $wp_admin_bar->remove_node('new-media');
    }
}
