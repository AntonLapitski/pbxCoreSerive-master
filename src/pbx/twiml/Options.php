<?php


namespace app\src\pbx\twiml;


/**
 * Class Options
 * @property mixed $data
 * @property array $element
 * @package app\src\pbx\twiml
 */
class Options
{
    /**
     * дата
     *
     * @var mixed $data
     */
    public $data;

    /**
     * элемент
     *
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
     * поучить элементы опций
     *
     * @return array
     */
    public function getElemOptions()
    {
        if (!isset($this->element['options'])) return $this->searchOptions();
        return array_merge($this->element['options'], $this->searchOptions());
    }

    /**
     * поиск опций
     *
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
     * существует ли
     *
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
     * установить элемент
     *
     * @param $element
     * @return $this
     */
    public function setElement($element)
    {
        $this->element = $element;

        return $this;
    }

}