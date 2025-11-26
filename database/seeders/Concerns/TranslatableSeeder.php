<?php

namespace Database\Seeders\Concerns;

use Illuminate\Database\Eloquent\Model;

trait TranslatableSeeder
{
    /**
     * Upsert a translatable model and assign translations.
     *
     * @param class-string<Model> $modelClass
     * @param array<string, mixed> $unique
     * @param array<string, mixed> $attributes
     * @param array<string, array<string, mixed>> $translations
     */
    protected function upsertTranslatable(string $modelClass, array $unique, array $attributes, array $translations): Model
    {
        /** @var Model $model */
        $model = $modelClass::firstOrCreate($unique, $attributes);

        // Update base attributes for existing records
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $model->{$key} = $value;
            }
        }

        // Assign translations
        foreach ($translations as $locale => $fields) {
            foreach ($fields as $field => $value) {
                $model->translateOrNew($locale)->{$field} = $value;
            }
        }

        $model->save();
        return $model;
    }
}

