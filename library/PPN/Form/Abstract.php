<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Base
 *
 * @author robert
 */
class PPN_Form_Abstract extends Zend_Form
{
    public $elementDecorators = array(
        'ViewHelper',
        'Errors',
        'Label',
        array(
            'HtmlTag',
            array(
                'tag' => 'li', 'class' => 'form_element'
            )
        )
    );

    public $fileDecorators = array(
        'File',
        'Errors',
        'Label',
        array(
            'HtmlTag',
            array(
                'tag' => 'li', 'class' => 'form_element'
            )
        )
    );

    public $buttonDecorators = array(
        'ViewHelper'
    );

    public $hiddenElementDecorators = array(
        'ViewHelper'
    );

    public function init()
    {
        /*$this->addElementPrefixPath(
            'DBS_Validate',
            realpath(APPLICATION_PATH . '/../library/DBS/Validator'),
            'validate'
        );

        $this->addPrefixPath(
            'DBS_Form_Element',
            realpath(APPLICATION_PATH . '/../library/DBS/Form/Element'),
            'element'
        );*/

        $this->setMethod('post');
        
        $this->setDecorators(
            array(
                'FormElements',
                array(
                    'HtmlTag',
                    array(
                        'tag' => 'ul'
                    )
                ),
                'Form'
            )
        );

        $this->setElementDecorators($this->elementDecorators);
    }

    public function addSubmitAndResetButtons()
    {
        $this->addElement(
            'submit',
            'submit',
            array(
                'label' => 'Submit',
                'ignore' => true,
                'decorators' => $this->buttonDecorators,
                'class' => 'submit_button'
            )
        );

        $this->addElement(
            'reset',
            'reset',
            array(
                'label' => 'Poništi',
                'ignore' => true,
                'decorators' => $this->buttonDecorators,
                'class' => 'reset_button'
            )
        );

        $this->addElement(
            'hash',
            'csrf',
            array(
                'ignore' => true,
                'decorators' => $this->buttonDecorators
            )
        );

        $this->addDisplayGroup(
            array(
                'submit', 'reset', 'csrf'
            ),
            'buttons'
        );

        $this->setDisplayGroupDecorators(array(
            'FormElements',
            array(
                'HtmlTag',
                array(
                    'tag' => 'li'
                )
            )
        ));
    }

}