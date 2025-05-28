<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SisbenSeeder extends Seeder
{
    public function run()
    {
        $categorias = [
            ['letra' => 'A', 'numero' => 1, 'puntaje' => 100],
            ['letra' => 'A', 'numero' => 2, 'puntaje' => 95],
            ['letra' => 'A', 'numero' => 3, 'puntaje' => 90],
            ['letra' => 'A', 'numero' => 4, 'puntaje' => 85],
            ['letra' => 'A', 'numero' => 5, 'puntaje' => 80],
            ['letra' => 'B', 'numero' => 1, 'puntaje' => 75],
            ['letra' => 'B', 'numero' => 2, 'puntaje' => 70],
            ['letra' => 'B', 'numero' => 3, 'puntaje' => 65],
            ['letra' => 'B', 'numero' => 4, 'puntaje' => 60],
            ['letra' => 'B', 'numero' => 5, 'puntaje' => 55],
            ['letra' => 'C', 'numero' => 1, 'puntaje' => 50],
            ['letra' => 'C', 'numero' => 2, 'puntaje' => 45],
            ['letra' => 'C', 'numero' => 3, 'puntaje' => 40],
            ['letra' => 'C', 'numero' => 4, 'puntaje' => 35],
            ['letra' => 'C', 'numero' => 5, 'puntaje' => 30],
            ['letra' => 'D', 'numero' => 1, 'puntaje' => 25],
            ['letra' => 'D', 'numero' => 2, 'puntaje' => 20],
            ['letra' => 'D', 'numero' => 3, 'puntaje' => 15],
            ['letra' => 'D', 'numero' => 4, 'puntaje' => 10],
            ['letra' => 'D', 'numero' => 5, 'puntaje' => 5],
        ];
        foreach ($categorias as $cat) {
            DB::table('sisben')->updateOrInsert(
                ['letra' => $cat['letra'], 'numero' => $cat['numero']],
                ['puntaje' => $cat['puntaje']]
            );
        }
    }
}
