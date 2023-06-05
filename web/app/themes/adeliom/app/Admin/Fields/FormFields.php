<?php

namespace Adeliom\Lumberjack\Admin\Fields\Choices;
use App\Services\GravityFormService;
use Extended\ACF\Fields\Select;

class FormFields
{
    public const FORM_SELECT = 'form_id';

    public static function select(): Select
    {
        return Select::make('Formulaire', self::FORM_SELECT)
            ->stylisedUi(true)
            ->choices(GravityFormService::getAllFormChoices())
            ->allowNull();
    }
}
