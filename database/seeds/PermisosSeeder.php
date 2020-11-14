<?php

use Illuminate\Database\Seeder;
use App\permisos;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        permisos::create([
            'permiso' => 'admi:delete']);
    
            permisos::create([
            'permiso' => 'admi:list']);
    
            permisos::create([
            'permiso' => 'admi:permiso']);
    
            permisos::create([
            'permiso' => 'user:perfil']);
    
            permisos::create([
            'permiso' => 'user:edit']);
    
            permisos::create([
                'permiso' => 'user:post']);
        
                permisos::create([
                'permiso' => 'user:coment']);
    }
}
