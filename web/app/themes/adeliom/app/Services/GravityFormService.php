<?php

namespace App\Services;

use GFFormsModel;

class GravityFormService
{
    public static function getAllForms(): array
    {
        $forms = [];

        if (class_exists(('GFFormsModel'))) {
            $forms = GFFormsModel::get_forms();
        }

        return $forms;
    }

    public static function getAllFormChoices(): array
    {
        $choices = [];

        foreach (self::getAllForms() as $form) {
            $choices[$form->id] = $form->title;
        }

        return $choices;
    }
}
