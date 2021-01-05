<?php

namespace Militar\Form;

use Laminas\Form\Form;

class MilitarForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('militar');  // define o nome do formulario
        
 
        $this->add([
           
            'name' => 'nip',
            'type' => 'text',
            
             'options' => [
                'label' => 'NIP',
                'readonly' => 'true',  
            ],
        ]);
        
        $this->add([
            'name' => 'posto',
            'type' => 'text',
            'options' => [
                'label' => 'Posto',
            ],
        ]);
        $this->add([
            'name' => 'nome',
            'type' => 'text',
            'options' => [
                'label' => 'Nome',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}
