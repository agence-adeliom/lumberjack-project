<?php

namespace Adeliom\Lumberjack\Admin\Fields;

use App\Services\GravityFormService;
use Extended\ACF\Fields\Select;

class FormFields
{
    public const FORM_SELECT = 'form_id';

    public static function selectGF(): Select
    {
        return Select::make('Formulaire', self::FORM_SELECT)
            ->stylisedUi(true)
            ->choices(GravityFormService::getAllFormChoices())
            ->allowNull();
    }
}
