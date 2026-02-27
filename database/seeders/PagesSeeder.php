<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug'        => 'home',
                'titolo'      => 'Home',
                'ordinamento' => 0,
                'contenuto'   => [
                    'blocks' => [
                        [
                            'id'     => '1',
                            'type'   => 'heading',
                            'level'  => 1,
                            'text'   => 'Benvenuto',
                            'class'  => '',
                            'htmlId' => '',
                        ],
                        [
                            'id'      => '2',
                            'type'    => 'text',
                            'class'   => '',
                            'htmlId'  => '',
                            'content' => [
                                'type'    => 'doc',
                                'content' => [
                                    [
                                        'type'    => 'paragraph',
                                        'content' => [['type' => 'text', 'text' => "Sito istituzionale dell'associazione. Modifica questo contenuto dall'area amministrativa."]],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'slug'        => 'chi-siamo',
                'titolo'      => 'Chi siamo',
                'ordinamento' => 1,
                'contenuto'   => [
                    'blocks' => [
                        [
                            'id'     => '1',
                            'type'   => 'heading',
                            'level'  => 1,
                            'text'   => 'Chi siamo',
                            'class'  => '',
                            'htmlId' => '',
                        ],
                        [
                            'id'      => '2',
                            'type'    => 'text',
                            'class'   => '',
                            'htmlId'  => '',
                            'content' => [
                                'type'    => 'doc',
                                'content' => [
                                    [
                                        'type'    => 'paragraph',
                                        'content' => [['type' => 'text', 'text' => "Descrizione dell'associazione."]],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'slug'        => 'contatti',
                'titolo'      => 'Contatti',
                'ordinamento' => 2,
                'contenuto'   => [
                    'blocks' => [
                        [
                            'id'     => '1',
                            'type'   => 'heading',
                            'level'  => 1,
                            'text'   => 'Contatti',
                            'class'  => '',
                            'htmlId' => '',
                        ],
                        [
                            'id'      => '2',
                            'type'    => 'text',
                            'class'   => '',
                            'htmlId'  => '',
                            'content' => [
                                'type'    => 'doc',
                                'content' => [
                                    [
                                        'type'    => 'paragraph',
                                        'content' => [['type' => 'text', 'text' => "Informazioni di contatto dell'associazione."]],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($pages as $data) {
            Page::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
