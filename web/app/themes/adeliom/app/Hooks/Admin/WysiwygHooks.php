<?php

declare(strict_types=1);

namespace App\Hooks\Admin;

use Adeliom\Lumberjack\Hooks\Models\Filter;

/**
 * Class ACFHooks
 *
 * @package App\Hooks\Admin
 */
class WysiwygHooks
{
    public const TOOLBAR_DEFAULT = 'default';
    public const TOOLBAR_SIMPLE = 'simple';

    /* TODO : Créer nouveau wysiwyg */

    /**
     * Change title page into custom placeholder
     */
    #[Filter("enter_title_here")]
    public static function customPlaceholder(string $title = ""): string
    {
        // $screen = get_current_screen();
        // if  ($screen->post_type === "...")) {
        //     $title = __('My custom placeholder');
        // }
        return $title;
    }

    #[Filter("mce_css")]
    public static function customCss($mce_css): string
    {
        if (!empty($mce_css)) {
            $mce_css .= ',';
        }
        $mce_css .= get_theme_file_uri('/build/styles/editor.css');
        return $mce_css;
    }

    #[Filter("tiny_mce_before_init")]
    public static function customStyleFormats($settings): array
    {
        $style_formats = [
            [
                'title' => 'Titres',
                'items' => [
                    [
                        'title'      => 'Titre 3xl',
                        'selector'   => 'h2, h3, h4, h5, h6, p',
                        'wrapper'    => false,
                        'remove'     => 'none',
                        'attributes' => [
                            'class' => 'text-3xl',
                        ],
                    ],
                    [
                        'title'      => 'Titre 2xl',
                        'selector'   => 'h2, h3, h4, h5, h6, p',
                        'wrapper'    => false,
                        'remove'     => 'none',
                        'attributes' => [
                            'class' => 'text-2xl',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Paragraphes',
                'items' => [
                    [
                        'title'      => 'Texte large',
                        'wrapper'    => false,
                        'selector'   => 'h2, h3, h4, h5, h6, p',
                        'remove'     => 'none',
                        'attributes' => [
                            'class' => 'text-xl',
                        ],
                    ],
                ],
            ],
        ];

        // Insert the array, JSON ENCODED, into 'style_formats'
        $settings['style_formats'] = json_encode($style_formats);

        /*$settings['textcolor_map'] = json_encode(
            array(
                'FF5400', 'Code - Orange',
                'FF9C00', 'Code - Jaune'
            )
        );*/

        return $settings;
    }

    #[Filter("acf/fields/wysiwyg/toolbars")]
    public static function wysiwygToolbars(array $toolbars): array
    {
        $toolbars[self::TOOLBAR_DEFAULT] = [];
        $toolbars[self::TOOLBAR_DEFAULT][1] = [
            'formatselect',
            'styleselect',
            'bold',
            'italic',
            'underline',
            '|',
            'bullist',
            'numlist',
            '|',
            'link',
            '|',
            'removeformat',
        ];

        $toolbars[self::TOOLBAR_SIMPLE] = [];
        $toolbars[self::TOOLBAR_SIMPLE][1] = [
            'bold',
            'italic',
            'underline',
            'bullist',
            'numlist',
            'link',
            'removeformat',
        ];

        return $toolbars;
    }

    /**
     * Clean tinyMCE
     */
    #[Filter("mce_buttons")]
    public static function removeButtons($buttons): array
    {
        $remove_buttons = [
            'strikethrough',
            'blockquote',
            'hr',
            // horizontal line
            'alignleft',
            'aligncenter',
            'alignright',
            'wp_more',
            // read more link
            'spellchecker',
            'dfw',
            // distraction free writing mode
            'wp_adv',
            // kitchen sink toggle (if removed, kitchen sink will always display)
        ];
        foreach ($buttons as $button_key => $button_value) {
            if (in_array($button_value, $remove_buttons, true)) {
                unset($buttons[$button_key]);
            }
        }

        return $buttons;
    }

    /**
     * Clean tinyMCE 2nd row
     */
    #[Filter("mce_buttons_2")]
    public static function removeButtonLine2($buttons): array
    {
        $remove_buttons = [
            'formatselect',
            // format dropdown menu for <p>, headings, etc
            'underline',
            'barrer',
            'strikethrough',
            'alignjustify',
            'forecolor',
            // text color
            'pastetext',
            // paste as text
            'charmap',
            // special characters
            'outdent',
            'indent',
            'undo',
            'redo',
            'hr',
            'wp_help',
            // keyboard shortcuts
        ];
        foreach ($buttons as $button_key => $button_value) {
            if (in_array($button_value, $remove_buttons)) {
                unset($buttons[$button_key]);
            }
        }

        return $buttons;
    }

    /**
     * Remove heading useless
     */
    #[Filter("tiny_mce_before_init")]
    public static function removeHeadings($headings): array
    {
        $headings['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;';
        return $headings;
    }
}
