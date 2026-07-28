<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\Party;
use App\Models\Urna;
use App\Models\Setting;
use App\Models\User;
use App\Models\Mesa;
use App\Models\SeccionMesa;
use App\Models\Puesto;
use App\Models\Candidato;
use App\Models\MiembroJrv;
use App\Models\TeeMember;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin por defecto ──
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@votaciones.edu',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // ── Configuración ──
        Setting::create(['nombre' => 'institucion_nombre', 'detalle' => 'Liceo Técnico Innovador']);
        Setting::create(['nombre' => 'tiempo_votacion_segundos', 'detalle' => '90']);
        Setting::create(['nombre' => 'eleccion_abierta', 'detalle' => '0']); // 0: Cerrada, 1: Abierta
        Setting::create(['nombre' => 'bloqueo_listas', 'detalle' => '0']); // 0: Desbloqueado, 1: Bloqueado

        // ── Partidos ──
        $pac = Party::create(['siglas' => 'PAC', 'nombre' => 'Partido Acción Colegial', 'estado' => 1]);
        $innova = Party::create(['siglas' => 'INNOVA', 'nombre' => 'Juventud Innovadora', 'estado' => 1]);
        $une = Party::create(['siglas' => 'UNE', 'nombre' => 'Unión Nacional Estudiantil', 'estado' => 1]);

        // Partidos Especiales (Blancos y Nulos)
        Party::create(['siglas' => 'BLANCOS', 'nombre' => 'Votos en Blanco', 'estado' => 0]);
        Party::create(['siglas' => 'NULOS', 'nombre' => 'Votos Nulos', 'estado' => 0]);

        // ── Estudiantes ──
        $studentsData = [
            ['1001', 'Juan Antonio', 'Pérez López', '10-A'],
            ['1002', 'María José', 'Gómez Ríos', '10-A'],
            ['1003', 'Roberto Carlos', 'Sánchez Mora', '10-B'],
            ['1004', 'Ana Lucía', 'Fernández Cruz', '10-B'],
            ['1005', 'Diego Alonso', 'Ramírez Solís', '11-A'],
            ['1006', 'Valentina', 'Castro Herrera', '11-A'],
            ['1007', 'Andrés Felipe', 'Morales Vargas', '11-B'],
            ['1008', 'Camila Sofía', 'Jiménez Rojas', '11-B'],
            ['1009', 'Luis Enrique', 'Navarro Chaves', '12-A'],
            ['1010', 'Isabella', 'Montero Arias', '12-A'],
            ['1011', 'Santiago', 'Cordero Brenes', '12-B'],
            ['1012', 'Gabriela', 'Vindas Calderón', '12-B'],
        ];

        $studentModels = [];
        foreach ($studentsData as $s) {
            $studentModels[] = Student::create([
                'identificacion' => $s[0],
                'nombre' => $s[1],
                'apellidos' => $s[2],
                'seccion' => $s[3],
                'voto' => 0,
                'estado' => 1,
            ]);
        }

        // ── Urnas / Terminales ──
        Urna::create(['codigo' => '12A', 'estado' => 1]);
        Urna::create(['codigo' => '12B', 'estado' => 1]);

        // ── Mesas Electorales y Secciones ──
        $mesa1 = Mesa::create(['numero' => 1, 'estado' => 1]);
        $mesa2 = Mesa::create(['numero' => 2, 'estado' => 1]);

        SeccionMesa::create(['mesa_id' => $mesa1->id, 'seccion' => '10-A']);
        SeccionMesa::create(['mesa_id' => $mesa1->id, 'seccion' => '10-B']);
        SeccionMesa::create(['mesa_id' => $mesa1->id, 'seccion' => '11-A']);
        SeccionMesa::create(['mesa_id' => $mesa1->id, 'seccion' => '11-B']);
        SeccionMesa::create(['mesa_id' => $mesa2->id, 'seccion' => '12-A']);
        SeccionMesa::create(['mesa_id' => $mesa2->id, 'seccion' => '12-B']);

        $pPresidencia = Puesto::create(['nombre' => 'Presidencia', 'estado' => 1]);
        $pVicepresidencia = Puesto::create(['nombre' => 'Vicepresidencia', 'estado' => 1]);
        $pSecretaria = Puesto::create(['nombre' => 'Secretaría', 'estado' => 1]);
        $pTesoreria = Puesto::create(['nombre' => 'Tesorería', 'estado' => 1]);

        Candidato::create(['student_id' => $studentModels[0]->id, 'party_id' => $pac->id, 'puesto_id' => $pPresidencia->id]);
        Candidato::create(['student_id' => $studentModels[1]->id, 'party_id' => $pac->id, 'puesto_id' => $pVicepresidencia->id]);
        Candidato::create(['student_id' => $studentModels[4]->id, 'party_id' => $innova->id, 'puesto_id' => $pPresidencia->id]);
        Candidato::create(['student_id' => $studentModels[5]->id, 'party_id' => $innova->id, 'puesto_id' => $pVicepresidencia->id]);
        Candidato::create(['student_id' => $studentModels[8]->id, 'party_id' => $une->id, 'puesto_id' => $pPresidencia->id]);
        Candidato::create(['student_id' => $studentModels[9]->id, 'party_id' => $une->id, 'puesto_id' => $pVicepresidencia->id]);

        MiembroJrv::create([
            'student_id' => $studentModels[2]->id,
            'party_id' => $pac->id,
            'mesa_id' => $mesa1->id,
            'puesto' => 3,
            'estado' => 1
        ]);
        MiembroJrv::create([
            'student_id' => $studentModels[3]->id,
            'party_id' => $innova->id,
            'mesa_id' => $mesa1->id,
            'puesto' => 1,
            'estado' => 1
        ]);
        MiembroJrv::create([
            'student_id' => $studentModels[10]->id,
            'party_id' => null,
            'mesa_id' => $mesa2->id,
            'puesto' => 3,
            'estado' => 1
        ]);

        TeeMember::create(['student_id' => $studentModels[6]->id, 'puesto' => 'Presidente TEE']);
        TeeMember::create(['student_id' => $studentModels[7]->id, 'puesto' => 'Secretario TEE']);
    }
}

