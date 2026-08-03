<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\Party;
use App\Models\Puesto;
use App\Models\Candidato;
use App\Models\Mesa;
use App\Models\SeccionMesa;
use App\Models\Urna;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'admin', 'password' => 'admin', 'role' => 'admin'],
            ['name' => 'tee', 'password' => 'tee', 'role' => 'tee'],
            ['name' => 'jrv', 'password' => 'jrv', 'role' => 'jrv'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['name' => $user['name']],
                ['password' => Hash::make($user['password']), 'role' => $user['role']],
            );
        }

        $parties = [
            ['siglas' => 'PVA', 'nombre' => 'Partido Verde Estudiantil'],
            ['siglas' => 'PRA', 'nombre' => 'Partido Rojo Académico'],
            ['siglas' => 'PBA', 'nombre' => 'Partido Azul del Futuro'],
        ];
        foreach ($parties as $party) {
            Party::updateOrCreate(['siglas' => $party['siglas']], $party);
        }

        $puestos = [
            'Presidente', 'Vicepresidente', 'Tesorero', 'Secretario',
        ];
        foreach ($puestos as $p) {
            Puesto::updateOrCreate(['nombre' => $p], ['nombre' => $p, 'estado' => 1]);
        }

        $students = [
            ['identificacion' => '1000001', 'nombre' => 'Ana', 'apellidos' => 'Perez', 'seccion' => '11-1', 'voto' => 0],
            ['identificacion' => '1000002', 'nombre' => 'Luis', 'apellidos' => 'Gomez', 'seccion' => '11-1', 'voto' => 1],
            ['identificacion' => '1000003', 'nombre' => 'Maria', 'apellidos' => 'Lopez', 'seccion' => '11-2', 'voto' => 0],
            ['identificacion' => '1000004', 'nombre' => 'Carlos', 'apellidos' => 'Ruiz', 'seccion' => '11-2', 'voto' => 1],
            ['identificacion' => '1000005', 'nombre' => 'Sofia', 'apellidos' => 'Mora', 'seccion' => '12-1', 'voto' => 0],
            ['identificacion' => '1000006', 'nombre' => 'Diego', 'apellidos' => 'Castro', 'seccion' => '12-1', 'voto' => 1],
            ['identificacion' => '1000007', 'nombre' => 'Valeria', 'apellidos' => 'Vargas', 'seccion' => '12-2', 'voto' => 0],
            ['identificacion' => '1000008', 'nombre' => 'Andres', 'apellidos' => 'Rojas', 'seccion' => '12-2', 'voto' => 1],
        ];
        foreach ($students as $s) {
            Student::updateOrCreate(['identificacion' => $s['identificacion']], $s);
        }

        $candidatos = [
            [1, 1, 1],
            [2, 2, 2],
            [3, 3, 3],
            [4, 1, 2],
            [1, 3, 3],
            [2, 4, 1],
        ];
        foreach ($candidatos as $c) {
            Candidato::updateOrCreate(
                ['puesto_id' => $c[0], 'student_id' => $c[1]],
                ['puesto_id' => $c[0], 'student_id' => $c[1], 'party_id' => $c[2]],
            );
        }

        $mesas = [
            ['nombre' => 'Mesa 1', 'ubicacion' => 'Aula 1', 'estado' => 1],
            ['nombre' => 'Mesa 2', 'ubicacion' => 'Aula 2', 'estado' => 1],
            ['nombre' => 'Mesa 3', 'ubicacion' => 'Aula 3', 'estado' => 1],
        ];
        foreach ($mesas as $m) {
            Mesa::updateOrCreate(['nombre' => $m['nombre']], $m);
        }

        $secciones = [
            [1, '11-1'],
            [1, '11-2'],
            [2, '12-1'],
            [2, '12-2'],
            [3, '10-1'],
            [3, '10-2'],
        ];
        foreach ($secciones as $s) {
            SeccionMesa::updateOrCreate(
                ['mesa_id' => $s[0], 'seccion' => $s[1]],
                ['mesa_id' => $s[0], 'seccion' => $s[1]],
            );
        }

        $urnas = [
            ['codigo' => 'U001', 'horaactivacion' => now(), 'estado' => 1, 'id_mesa' => 1],
            ['codigo' => 'U002', 'horaactivacion' => now(), 'estado' => 1, 'id_mesa' => 2],
            ['codigo' => 'U003', 'horaactivacion' => now(), 'estado' => 0, 'id_mesa' => 3],
        ];
        foreach ($urnas as $u) {
            Urna::updateOrCreate(['codigo' => $u['codigo']], $u);
        }
    }
}
