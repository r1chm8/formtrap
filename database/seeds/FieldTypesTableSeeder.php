<?php

use App\FieldType;
use Illuminate\Database\Seeder;

class FieldTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FieldType::insert([
            [
                'id' => FieldType::TEXT,
                'name' => 'Text'
            ],
            [
                'id' => FieldType::TEXTAREA,
                'name' => 'Textarea'
            ],
            [
                'id' => FieldType::RADIO,
                'name' => 'Radio'
            ],
            [
                'id' => FieldType::CHECKBOXES,
                'name' => 'Checkboxes'
            ],
            [
                'id' => FieldType::SELECT,
                'name' => 'Select'
            ]
        ]);
    }
}
