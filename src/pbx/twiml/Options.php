<?php


namespace app\src\pbx\twiml;


/**
 * Class Options
 * @package app\src\pbx\twiml
 */
class Options
{
    /**
     * @var
     */
    public $data;
    /**
     * @var array
     */
    protected $element;

    /**
     * Options constructor.
     * @param $options
     */
    public function __construct($options)
    {
        $this->data = $options;
        $this->element = [];
    }

    /**
     * @return array
     */
    public function getElemOptions()
    {
        if (!isset($this->element['options'])) return $this->searchOptions();
        return array_merge($this->element['options'], $this->searchOptions());
    }

    /**
     * @return array
     */
    protected function searchOptions()
    {
        $options = array_filter($this->data, function ($dataElem) {
            return (
                $this->isExists('verb', (array)$dataElem)
                &&
                $this->isExists('noun', (array)$dataElem)
            );
        });

        if ($options)
            return array_shift($options)['options'];

        return $options;
    }

    /**
     * @param $property
     * @param $dataElem
     * @return bool
     */
    protected function isExists($property, $dataElem)
    {
        $elem = (array)$this->element;
        if (isset($elem[$property]) && isset($dataElem[$property])) {
            return ($elem[$property] == $dataElem[$property]);
        }

        return true;
    }

    /**
     * @param $element
     * @return $this
     */
    public function setElement($element)
    {
        $this->element = $element;

        return $this;
    }

}