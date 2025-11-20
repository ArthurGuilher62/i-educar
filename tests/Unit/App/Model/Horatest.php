<?php

namespace Tests\Unit\App\Model;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Api\Resource\Course\ResourceCourseRequest;
use App\Http\Requests\Api\Resource\Servidor\ResourceServidorRequest;


class Horatest extends TestCase
{
    public function test_validar_hora_falta_negativa()
    {
        $request = new ResourceCourseRequest();

        $data = ['hora_falta' => -1];

        $validator = Validator::make($data, $request->rules(), [], $request->attributes());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('hora_falta', $validator->errors()->messages());
    }

    public function test_Horario_carga_nula()
    {
        $form = include base_path('ieducar/intranet/educar_servidor_cad.php');


        $form->carga_horaria = '00:00';
        $form->cod_servidor = 123;
        $form->ref_cod_instituicao = 1;

        $resultado = $form->Novo();

        $this->assertFalse($resultado);
    }

    public function test_Horario_carga_acima_de_24h()
    {
        $form = include base_path('ieducar/intranet/educar_servidor_cad.php');


        $form->carga_horaria = '24:01';
        $form->cod_servidor = 123;
        $form->ref_cod_instituicao = 1;

        $resultado = $form->Novo();

        $this->assertFalse($resultado);
    }
}
