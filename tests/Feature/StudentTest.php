<?php

namespace Tests\Feature;

use App\Http\Controllers\StudentController;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use DatabaseTransactions;

    public function testShowActivity()
    {
        // Crie uma instância do controlador
        $controller = new StudentController();

        // Crie uma instância fictícia do Request com o parâmetro "id"
        $request = new Request(['id' => 1]);

        // Chame o método showActivity do controlador
        $response = $controller->showActivity($request);

        // Verifique se a view retornada contém os dados esperados
        $this->assertArrayHasKey('activities', $response->getData());
        $this->assertArrayHasKey('content_id', $response->getData());
    }
}
