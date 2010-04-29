<?php
/** 
 *  @package ##PACKAGE##
 *  @version ##VERSION##
 */

/**
 * Description of DoctrineForm
 * @package ##PACKAGE##
 * @author prskavecl
 */
class ZFCore_DoctrineForm extends Zend_Form
{
    /**
     * Reference to the model's table class
     * @var Doctrine_Table
     */
    protected $_table;
    /**
     * Name class in Doctrine
     * @var <type>
     */
    protected $_model = '';
    /**
     * Column names listed in this array will not be shown in the form
     * @var array
     */
    /**
     * Field labels. key = column name, value = label
     * @var array
     */
    protected $_fieldLabels = array();
    /**
     *
     * @var array
     */
    protected $_ignoreColumns = array();
    /**
     * Whether or not to generate fields for many parts of m2o and m2m relations
     * @var bool
     */
    protected $_generateManyFields = false;
    /**
     * Which Zend_Form element types are associated with which doctrine type?
     * @var array
     */
    protected $_columnTypes = array(
            'integer' => 'text',
            'decimal' => 'text',
            'float' => 'text',
            'string' => 'text',
            'varchar' => 'text',
            'boolean' => 'checkbox',
            'timestamp' => 'text',
            'time' => 'text',
            'date' => 'text',
            'enum' => 'select'
    );
    /**
     * Labels to use with many to many relations.
     * key = related class name, value = label
     * @var array
     */
    protected $_relationLabels = array();
    /**
     * @param array $options Options to pass to the Zend_Form constructor
     */
    public function __construct($options = null)
    {
        if($this->_model == '')
            throw new Exception('No model defined');
        parent::__construct($options);

        $this->_table = Doctrine::getTable($this->_model);        
        //Zend_Debug::dump($this->_table->getFieldNames());
        $this->_getForm();
    }
    /**
     * Return all columns
     * @return array
     */
    protected function _getColumns()
    {
        $columns = array();
        foreach($this->_table->getColumns() as $name => $definition) {
            if((isset($definition['primary']) && $definition['primary']) ||
                    !isset($this->_columnTypes[$definition['type']]) || in_array($name, $this->_ignoreColumns))
                continue;
            $definition['fieldName'] = $this->_table->getFieldName($name);
            $columns[$name] = $definition;
        }

        return $columns;
    }

    /**
     * Returns all un-ignored relations
     * @return array
     */
    protected function _getRelations()
    {
        $relations = array();

        foreach($this->_table->getRelations() as $name => $definition) {
            if(in_array($definition->getLocal(), $this->_ignoreColumns) ||
                    ($this->_generateManyFields == false && $definition->getType() == Doctrine_Relation::MANY))
                continue;

            $relations[$name] = $definition;
        }

        return $relations;
    }
    /**
     * Get Form
     */
    protected function _getForm()
    {
        //Zend_Debug::dump($this->_getColumns());
        //Zend_Debug::dump($this->_getRelations());
        $this->_columnsToFields();
        $this->_relationsToFields();
    }
    /**
     * Parses columns to fields
     */
    protected function _columnsToFields()
    {
        foreach($this->_getColumns() as $name => $definition) {
            $type = $this->_columnTypes[$definition['type']];
            if(isset($this->_fieldTypes[$name]))
                $type = $this->_fieldTypes[$name];

            $field = $this->createElement($type, $this->_fieldPrefix . $name);
            $label = $name;
            if(isset($this->_fieldLabels[$name]))
                $label = $this->_fieldLabels[$name];

            if(isset($this->_columnValidators[$definition['type']]))
                $field->addValidator($this->_columnValidators[$definition['type']]);

            if(isset($definition['notnull']) && $definition['notnull'] == true)
                $field->setRequired(true);

            $field->setLabel($label);

            if($type == 'select' && $definition['type'] == 'enum') {
                foreach($definition['values'] as $text) {
                    $field->addMultiOption($text, ucwords($text));
                }
            }

            $this->addElement($field);
        }
    }

    /**
     * Parses relations to fields
     */
    protected function _relationsToFields()
    {
        foreach($this->_getRelations() as $alias => $relation) {
            $field = null;

            switch($relation->getType()) {
                case Doctrine_Relation::ONE:
                    $table = $relation->getTable();                    
                    $idColumn = $table->getIdentifier();
                    $options = array('------');

                    foreach($table->findAll() as $row) {                        
                        if(isset($this->_relationsLabels[$alias])) {
                            $name = $this->_relationsLabels[$alias];
                        } else {
                            $name = $row;
                        }
                        
                        $options[$row->$idColumn] = (string) $row->{$name};                                                  
                    }

                    
                    $field = $this->createElement('select', $relation->getLocal());
                    
                    if(isset($this->_fieldLabels[$relation->getLocal()]))
                        $label = $this->_fieldLabels[$relation->getLocal()];


                    $field->setLabel($label);
                    $field->setMultiOptions($options);
                    
                    break;

            }
            
            if($field != null) {
                $this->addElement($field);
            }

        }
        // add submit
        $this->addElement('submit', 'submit', array(
                'ignored' => true,
                'required' => false,
                'label' => 'Submit'
        ));
    }
}

