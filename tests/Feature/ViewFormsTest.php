<?php

namespace Tests\Feature;

use App\Form;
use App\User;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewFormsTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        Passport::actingAs($this->user);
    }

    /** @test **/
    public function a_user_can_view_all_their_forms()
    {
        $forms = factory(Form::class, 2)->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->getJson('/api/forms');

        $response
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->expectedJsonStructure()
                ]
            ]);

        $ids = $response->decodeResponseJson('data.*.id');

        $forms->each(function ($form) use ($ids) {
            $this->assertContains($form->id, $ids);
        });
    }

    /** @test */
    public function a_user_can_view_a_specific_form()
    {
        $form = factory(Form::class)->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->getJson('/api/forms/' . $form->id);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => $this->expectedJsonStructure()
            ])
            ->assertJson([
                'data' => [
                    'id' => $form->id,
                    'name' => $form->name,
                    'version' => [
                        'id' => $form->version->id,
                    ],
                    'created_at' => (string) $form->created_at,
                    'updated_at' => (string) $form->updated_at
                ]
            ]);
    }

    protected function expectedJsonStructure()
    {
        return [
            'id',
            'name',
            'version',
            'created_at',
            'updated_at'
        ];
    }
}
