<?php

declare(strict_types=1);

namespace App\Admin;

use Adeliom\Lumberjack\Admin\AbstractAdmin;
use Extended\ACF\Fields\Group;
use Extended\ACF\Fields\PostObject;
use Extended\ACF\Fields\Select;
use Extended\ACF\Fields\Tab;
use Extended\ACF\Fields\Text;
use Extended\ACF\Fields\Url;
use Extended\ACF\Location;
use Traversable;

/**
 * Class SharedBlockAdmin
 *
 * @package App\Admin
 */
class ParametersAdmin extends AbstractAdmin
{
    public const TITLE = 'Paramètres';
    public const IS_OPTION_PAGE = true;
    public const CONFIG_KEY = 'config';

    public static function getStyle(): string
    {
        return 'default';
    }

    protected static function getForms()
    {
        return [
            'quotation' => 'Demande de devis'
        ];
    }

    protected static function getFormsID()
    {
        $choices = [];
        if (class_exists('GFFormsModel')) {
            foreach (\GFFormsModel::get_forms() as $form) {
                $choices[ $form->id ] = $form->title;
            }
        }
        return $choices;
    }

    protected static function getLinks()
    {
        return [
            'legals' => 'Mentions légales',
            'rgdp' => 'Politique de confidentialité',
        ];
    }

    /**
     * @see https://github.com/vinkla/extended-acf#fields
     * @return Traversable
     */
    public static function getFields(): Traversable
    {
        yield Tab::make(__("Liens"));
        yield Group::make("Liens du site", "link")
            ->instructions('Renseignez les liens des pages concernées')
            ->fields(array_map(function ($key, $label) {
                return PostObject::make($label, $key)->postTypes(['page'])->required();
            }, array_keys(self::getLinks()), array_values(self::getLinks())))
            ->layout('row');


        yield Tab::make(__('Réseaux sociaux'), 'social_networks_tab');
        yield Group::make(__('Réseaux sociaux'), 'social_networks')
            ->fields([
                Url::make(__('LinkedIn'), 'linkedin'),
                Url::make(__('Facebook'), 'facebook'),
                Url::make(__('Twitter'), 'twitter'),
                Url::make(__('YouTube'), 'youtube'),
            ]);

        $forms = [];
        foreach (self::getForms() as $key => $label) {
            $forms[] = Select::make($label, $key)->choices(self::getFormsID())->required();
        }

        yield Tab::make(__("Formulaires"));
        yield Group::make("Formulaires", "form")
            ->instructions('Sélectionnez les formulaires concernés')
            ->fields($forms)
            ->layout('row');

        /* A utiliser lorsque les mails sont envoyés manuellement */
        /*yield Tab::make(__("Emails"), 'emails_tab');
        yield Group::make("Emails", "emails")
            ->fields([
                TrueFalse::make('Activer le debug', 'debug')
                    ->defaultValue(false)
                    ->stylisedUi(),
                Email::make('Email(s) de test', 'email_debug')
                    ->instructions("Saisir un ou plusieurs emails séparé d'une virgule")
                    ->conditionalLogic([
                        ConditionalLogic::where('debug', '==', 1)
                    ])
            ])
            ->layout('row');*/

        yield Tab::make(__("Autres"));
        yield Group::make("Configuration", self::CONFIG_KEY)
            ->fields([
                Text::make('Clé google maps', 'gmap')->required(),
            ])
            ->layout('row');
    }

    /**
     * Register option page settings
     * @see https://www.advancedcustomfields.com/resources/acf_add_options_page/
     * @return array
     */
    public static function setupOptionPage(): array
    {
        return [
            'page_title' => "Paramètres",
            'menu_title' => 'Paramètres',
            'menu_slug'  => self::getSlug(),
            "roles" => ['admin', 'editor'],
            "icon_url" => "dashicons-schedule"
        ];
    }

    /**
     * @see https://github.com/vinkla/extended-acf#location
     */
    public static function getLocation(): Traversable
    {
        yield Location::where('options_page', self::getSlug());
    }
}
