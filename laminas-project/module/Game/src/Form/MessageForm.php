<?php
namespace Game\Form;

use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\Input;
use Laminas\Validator;

class MessageForm extends Form
{
    public function __construct($name = 'message', array $options = [])
    {
        parent::__construct($name, $options);

        $this->add([
            'name' => 'destinatario',
            'type' => 'text',
            'options' => [
                'label' => 'Recipient',
            ],
        ]);

        $this->add([
            'name' => 'asunto',
            'type' => 'text',
            'options' => [
                'label' => 'Subject',
            ],
        ]);

        $this->add([
            'name' => 'mensaje',
            'type' => 'textarea',
            'options' => [
                'label' => 'Message',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Send',
            ],
        ]);

        $this->setInputFilter($this->createInputFilter());
    }

    protected function createInputFilter()
    {
        $inputFilter = new InputFilter();

        $destinatario = new Input('destinatario');
        $destinatario->setRequired(true);
        $destinatario->getFilterChain()->attachByName('stringtrim');
        $destinatario->getValidatorChain()->attach(new Validator\NotEmpty());
        $inputFilter->add($destinatario);

        $asunto = new Input('asunto');
        $asunto->setRequired(true);
        $asunto->getFilterChain()->attachByName('stringtrim');
        $asunto->getValidatorChain()->attach(new Validator\NotEmpty());
        $inputFilter->add($asunto);

        $mensaje = new Input('mensaje');
        $mensaje->setRequired(true);
        $mensaje->getFilterChain()->attachByName('stringtrim');
        $mensaje->getValidatorChain()->attach(new Validator\NotEmpty());
        $inputFilter->add($mensaje);

        return $inputFilter;
    }
}
