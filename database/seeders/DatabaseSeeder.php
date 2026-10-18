<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador Global',
            'email' => 'admin@eventhub.test',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $organizadorA = User::create([
            'name' => 'Organizador A',
            'email' => 'organizador.a@eventhub.test',
            'password' => 'password123',
            'role' => 'organizador',
        ]);

        User::create([
            'name' => 'Organizador B',
            'email' => 'organizador.b@eventhub.test',
            'password' => 'password123',
            'role' => 'organizador',
        ]);

        User::create([
            'name' => 'Asistente Uno',
            'email' => 'asistente@eventhub.test',
            'password' => 'password123',
            'role' => 'asistente',
        ]);

        // user_id se asigna automáticamente a través de la relación
        $organizadorA->eventos()->create([
            'titulo' => 'Laravel Conf 2026',
            'descripcion' => 'Conferencia sobre desarrollo de APIs con Laravel.',
            'cupo_maximo' => 200,
        ]);

        $organizadorA->eventos()->create([
            'titulo' => 'Taller de Seguridad Web',
            'descripcion' => 'Autenticación, autorización y buenas prácticas.',
            'cupo_maximo' => 50,
        ]);
    }
}