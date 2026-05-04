<?php 
$fields = [
    [
        'headline' => 'Licenční klíč',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        [
            'type' => 'text',
            'name' => 'license_key',
            'saveAs' => 'meta',
            'required' => false,
        ],
        
    ],
    [
        'headline' => 'Nadis formuláře',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        [
            'type' => 'hidden',
            'name' => 'currenturl',
            'saveAs' => 'meta',
            'required' => false,
            'value' => get_permalink()
        ],
        [
            'type' => 'info_box',
            'name' => 'info_box',
            'headline' => 'Info box',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Senectus libero ut lorem ac <a href="#">dictumst phasellus</a> nunc sit. Eu nisi sed viverra id aliquam enim, odio nunc.',
        ],
    ],
    [
        [
            'type' => 'switch',
            'name' => 'switch1',
            'label' => 'Checkbox as switch',
            'saveAs' => 'meta',
        ],
        'columns' => [
            [

                [
                    'type' => 'text',
                    'name' => 'title',
                    'label' => 'Jméno',
                    'saveAs' => 'meta',
                    'required' => true,
                ],
                [
                    'type' => 'email',
                    'name' => 'email1',
                    'label' => 'E-mail',
                    'floating_label' => true,
                    'saveAs' => 'meta',
                    'required' => true,
                ],
                [
                    'type' => 'tel',
                    'name' => 'telephone',
                    'label' => 'Telefon',
                    'floating_label' => true,
                    'saveAs' => 'meta',
                ],
                [
                    'type' => 'number',
                    'name' => 'cislo',
                    'label' => 'Nějaké číslo',
                    'floating_label' => true,
                    'saveAs' => 'meta',
                ],
                [
                    'type' => 'password',
                    'name' => 'heslo',
                    'label' => 'Vaše heslo',
                    'floating_label' => true,
                    'saveAs' => 'meta',
                ],
            ],
            [
                [
                    'type' => 'select',
                    'name' => 'select1',
                    'label' => 'Jednoduchý výběr',
                    'floating_label' => true,
                    'options' => [
                        '' => 'None',
                        'prvni' => 'První',
                        'druhy' => 'Druhý',
                        'treti' => 'Třetí',
                        'ctvrty' => 'Čtvrtý',
                        'paty' => 'Pátý',
                        'sesty' => 'Šestý',
                        'sedmi' => 'Sedmí',
                    ],
                    'saveAs' => 'meta',
                ],
                [
                    'type' => 'select',
                    'name' => 'multiselect',
                    'label' => 'Multi výběr',
                    'multiple' => true,
                    'floating_label' => true,
                    'options' => [
                        '' => 'None',
                        'prvni' => 'První',
                        'druhy' => 'Druhý',
                        'treti' => 'Třetí',
                        'ctvrty' => 'Čtvrtý',
                        'paty' => 'Pátý',
                        'sesty' => 'Šestý',
                        'sedmi' => 'Sedmí',
                    ],
                    'selectize' => [ //nutno zprovoznit (issue #3)
                        //'sortField' => '"text"', // hodnota včetně uvozovek
                        'maxItems' => 3,
                    ],
                    'saveAs' => 'meta',
                ],
                [
                    'type' => 'textarea',
                    'name' => 'dlouhytext',
                    'label' => 'Dlouhý text',
                    'floating_label' => true,
                    'saveAs' => 'meta',
                ],
            ],
        ],
        [
            'type' => 'editor',
            'name' => 'editor',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Senectus libero ut lorem ac <a href="#">dictumst phasellus</a> nunc sit. Eu nisi sed viverra id aliquam enim, odio nunc.',
            'help_text' => 'Pomocný text pro editor',
        ],
        [
            'type' => 'info_box',
            'name' => 'info_box',
            'headline' => 'Info box',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Senectus libero ut lorem ac <a href="#">dictumst phasellus</a> nunc sit. Eu nisi sed viverra id aliquam enim, odio nunc.',
        ],
    ],
    [
        [
            'type' => 'text',
            'name' => 'title',
            'label' => 'Jméno',
            'placeholder' => 'Vaše jméno',
            'help_text' => 'Definovaný placeholder jiný než label',
            'floating_label' => true,
            'saveAs' => 'meta',
            'required' => true,
        ],
        [
            'type' => 'text',
            'name' => 'title',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'label' => 'Jméno',
            'help_text' => 'Placeholder není definovaný (placeholder = label)',
            'floating_label' => true,
            'saveAs' => 'meta',
            'required' => true,
        ],
        [
            'type' => 'warning_box',
            'name' => 'warning_box',
            'headline' => 'Warning box',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Senectus libero ut lorem ac <a href="#">dictumst phasellus</a> nunc sit. Eu nisi sed viverra id aliquam enim, odio nunc.',
        ],

    ],
    [
        [
            'type' => 'checkbox_large',
            'name' => 'checkbox_large1',
            'label' => 'Checkbox large',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'saveAs' => 'meta',
        ],
        [
            'type' => 'checkbox',
            'name' => 'checkbox1',
            'label' => 'Checkbox Lorem ipsum dolor sit amet',
            'saveAs' => 'meta',
        ],
        [
            'type' => 'checkbox',
            'name' => 'checkbox2',
            'label' => 'Checkbox Lorem ipsum dolor sit amet',
            'saveAs' => 'meta',
            'required' => true,
        ],
    ],
    [
        [
            'type' => 'radio_large',
            'name' => 'radio_large1',
            'description' => 'Radio large - Radio tlačíta zobrazená jako jednotlivé bloky s h3 nadpisem',
            'saveAs' => 'meta',
            'options' => [
                'check1' => ['Checkbox large 1','Lorem ipsum dolor sit amet, consectetur adipiscing elit.'], // label, help text
                'check2' => ['Checkbox large 1','Lorem ipsum dolor sit amet, consectetur adipiscing elit.'],
                'check3' => ['Checkbox large 1','Lorem ipsum dolor sit amet, consectetur adipiscing elit.'],
                'check4' => ['Checkbox large 1','Lorem ipsum dolor sit amet, consectetur adipiscing elit.'],

            ],
            
        ],
        [
            'type' => 'radio',
            'name' => 'radio1',
            'description' => 'Radio 1 - Běžná radio tlačítka',
            'help_text' => 'Jednoduchý radio option s popisem a pomocným textem',
            'options' => [
                'prvni' => 'První',
                'druhy' => 'Druhý',
                'treti' => 'Třetí',
                'ctvrty' => 'Čtvrtý',
                'paty' => 'Pátý',
                'sesty' => 'Šestý',
                'sedmi' => 'Sedmí',
            ],
            'value' => 'treti',
            'saveAs' => 'meta',
        ],
        [
            'type' => 'radio',
            'name' => 'radio2',
            'description' => 'Radio 2 - I běžná radio tlačítka mohou být doplněny o pomocný text',
            'saveAs' => 'meta',
            'options' => [
                'check1' => ['Checkbox large 1','Lorem ipsum dolor sit amet'], // label, help text
                'check2' => ['Checkbox large 1','Lorem ipsum dolor sit amet'],
                'check3' => ['Checkbox large 1','Lorem ipsum dolor sit amet'],
                'check4' => ['Checkbox large 1','Lorem ipsum dolor sit amet'],

            ],
            
        ],
    ],
    [
        'columns' => [
            [
                [
                    'type' => 'switch',
                    'name' => 'switch',
                    'label' => 'Checkbox as switch',
                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Senectus libero ut lorem ac dictumst phasellus nunc sit. Eu nisi sed viverra id aliquam enim, odio nunc.',
                    'saveAs' => 'meta',
                ],
                [
                    'type' => 'range',
                    'name' => 'range1',
                    'label' => 'Výběr hodnoty se skoky po 10',
                    'help_text' => 'Defaultně nastavena hodnota 40',
                    'max' => 100,
                    'min' => 0,
                    'step' => 10,
                    'value' => 40,
                    'saveAs' => 'meta',
                    'required' => true,
                ],
                
            ],
            [
                [
                    'type' => 'range',
                    'name' => 'range2',
                    'label' => 'Výběr hodnoty s danou jednotkou',
                    'description' => 'V tomto výběru je daná jednotkou a zobrazuje se minimální a maximální hodnota',
                    'help_text' => 'Defaultně nastavena hodnota 1250 px',
                    'max' => 2400,
                    'min' => 0,
                    'value' => 1250,
                    'unit' => 'px',
                    'show_attr' => true,
                    'saveAs' => 'meta',
                ],
                [
                    'type' => 'range',
                    'name' => 'range3',
                    'label' => 'Výběr hodnoty s výběrem jednotky',
                    'help_text' => 'Defaultně nastavena hodnota 1250 px',
                    'max' => 2400,
                    'min' => 0,
                    'value' => 1250,
                    'unit' => [
                        'px' => 'px',
                        '%' => '%',
                        'rem' => 'rem',
                        'em' => 'em',
                        'vh' => 'vh',
                        'vw' => 'vw',
                    ],
                    'saveAs' => 'meta',
                ],
            ],
        ]
        
    ],
    [
        [
            'type' => 'url',
            'name' => 'odkaz',
            'label' => 'Url',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Senectus libero ut lorem ac dictumst phasellus nunc sit. Eu nisi sed viverra id aliquam enim, odio nunc.',
            'help_text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'floating_label' => true,
            'saveAs' => 'meta',
            'required' => true,
        ],
        [
            'type' => 'image',
            'name' => 'obrazek',
            'label' => 'Obrázek',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Senectus libero ut lorem ac dictumst phasellus nunc sit. Eu nisi sed viverra id aliquam enim, odio nunc.',
            'help_text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'value' => 'https://via.placeholder.com/300x200',
            'floating_label' => true,
            'saveAs' => 'meta',
            'required' => true,
        ],
    ],
    [
        [
            'type' => 'image',
            'name' => 'obrazek2',
            'label' => 'Obrázek',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Senectus libero ut lorem ac dictumst phasellus nunc sit. Eu nisi sed viverra id aliquam enim, odio nunc.',
            'help_text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'value' => 'https://via.placeholder.com/300x200',
            'floating_label' => true,
            'saveAs' => 'meta',
            'required' => true,
        ],
        [
            'type' => 'date',
            'name' => 'datum',
            'label' => 'Datum',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Senectus libero ut lorem ac dictumst phasellus nunc sit. Eu nisi sed viverra id aliquam enim, odio nunc.',
            'help_text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'floating_label' => true,
            'saveAs' => 'meta',
            'required' => true,
        ],
        [
            'type' => 'time',
            'name' => 'cas',
            'label' => 'Čas',
            'floating_label' => true,
            'saveAs' => 'meta',
            'required' => true,
        ],
        [
            'type' => 'month',
            'name' => 'mesic',
            'label' => 'Měsíc',
            'floating_label' => true,
            'saveAs' => 'meta',
        ],
        [
            'type' => 'week',
            'name' => 'tyden',
            'label' => 'Týden',
            'floating_label' => true,
            'saveAs' => 'meta',
        ],
        [
            'type' => 'datetime-local',
            'name' => 'datumcas',
            'label' => 'Datum a Čas',
            'floating_label' => true,
            'saveAs' => 'meta',
        ],
    ],
    [
        [
            'type' => 'color',
            'name' => 'barva',
            'label' => 'Primární barva',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Senectus libero ut lorem ac dictumst phasellus nunc sit. Eu nisi sed viverra id aliquam enim, odio nunc.',
            'help_text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'value' => '#ff00dd', // rgba(30,30,30,0.64)
            'saveAs' => 'meta',
            'required' => true,
        ],
        
    ],
    [
        [
            'type' => 'html',
            'name' => 'tabulka',
            'content' => '<table>
                            <tr>
                                <th>Company</th>
                                <th>Contact</th>
                                <th>Country</th>
                            </tr>
                            <tr>
                                <td>Alfreds Futterkiste</td>
                                <td>Maria Anders</td>
                                <td>Germany</td>
                            </tr>
                            <tr>
                                <td>Centro comercial Moctezuma</td>
                                <td>Francisco Chang</td>
                                <td>Mexico</td>
                            </tr>
                            <tr>
                                <td>Ernst Handel</td>
                                <td>Roland Mendel</td>
                                <td>Austria</td>
                            </tr>
                            <tr>
                                <td>Island Trading</td>
                                <td>Helen Bennett</td>
                                <td>UK</td>
                            </tr>
                            <tr>
                                <td>Laughing Bacchus Winecellars</td>
                                <td>Yoshi Tannamuri</td>
                                <td>Canada</td>
                            </tr>
                            <tr>
                                <td>Magazzini Alimentari Riuniti</td>
                                <td>Giovanni Rovelli</td>
                                <td>Italy</td>
                            </tr>
                        </table>',
        ],
        
    ],

];
return $fields;

/* TAKTO JEDNOU BUDE VYPADAT ZADÁVÁNÍ REPEATERU
[
    'type' => 'repeater',
    'name' => 'repeater_name',
    'saveAs' => 'meta',
    'require' => false,
    'options' => [
        'min' => 0,
        'max' => 10,
        'field' => [
            [
                'type' => 'email',
                'name' => 'email2',
                'label' => 'E-mail',
                'floating_label' => true,
                'saveAs' => 'meta',
                'required' => true,
            ],
            [
                'type' => 'checkbox',
                'name' => 'checkbox22',
                'label' => 'Checkbox Lorem ipsum dolor sit amet',
                'saveAs' => 'meta',
            ]
        ]
    ]
]

// FCE ODSTRANĚNÉ Z D1G1FrontForm pro nadbytečnost
if (!class_exists('d1g1FrontForm')) {
    class d1g1FrontForm 
    {
        private function get_field_type_from_name($name)
        {
            if (isset($this->fields) && !empty($this->fields)) {
                foreach ($this->fields as $index => $field) {
                    if ($field['name'] == $name) {
                        if (isset($this->fields[$index]['type'])) {
                            return $this->fields[$index]['type'];
                        } else {
                            return false;
                        }
                    }
                }
            }
        }

        public function get_field_parameter($name, $parameter)
        {
            if (isset($this->fields) && !empty($this->fields)) {
                foreach ($this->fields as $index => $field) {
                    if ($field['name'] == $name) {
                        if (isset($this->fields[$index][$parameter])) {
                            return $this->fields[$index][$parameter];
                        } else {
                            return false;
                        }
                    }
                }
            }
        }
    }
}
*/
?>