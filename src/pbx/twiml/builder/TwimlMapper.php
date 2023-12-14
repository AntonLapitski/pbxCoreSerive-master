<?php

namespace app\src\pbx\twiml\builder;


use app\src\pbx\twiml\Twiml;
use Twilio\TwiML\VoiceResponse;

/**
 * Class TwimlMapper
 * @property VoiceResponse $response
 * @property  array $schema
 * @property  array $flow
 * @property  mixed $checkpoint
 * @property  array $element
 * @package app\src\pbx\twiml\builder
 */
class TwimlMapper
{
    /**
     * голосовой ответ
     *
     * @var VoiceResponse
     */
    protected VoiceResponse $response;

    /**
     * схема
     *
     * @var array
     */
    protected array $schema;

    /**
     * поток
     *
     * @var array
     */
    protected array $flow;

    /**
     * проверочный пункт
     *
     * @var mixed
     */
    protected mixed $checkpoint;

    /**
     * элемент
     *
     * @var array
     */
    protected array $element;

    /**
     * TwimlMapper constructor.
     * @param $schema
     * @param null $checkpoint
     */
    public function __construct($schema, $checkpoint = null)
    {
        $this->schema = $schema;
        $this->checkpoint = $checkpoint;
        $this->flow = $this->flow($schema);
        $this->response = new VoiceResponse();
        if (null !== $checkpoint)
            $this->element = $this->schema[$checkpoint];
    }

    /**
     * поток
     *
     * @param $schema
     * @return array
     */
    protected function flow($schema): array
    {
        $flow = array();
        foreach ($schema as $key => $element) {
            if (class_exists(__NAMESPACE__ . '\\' . ucfirst($element[Twiml::VERB])))
                $flow[$key] = $element[Twiml::VERB];
        }

        return array_reverse($flow, true);
    }

    /**
     * установить проверочный пункт и вернуть ответ
     *
     * @return VoiceResponse
     */
    public function build(): \Twilio\TwiML\VoiceResponse
    {
        $this->setCheckpoint();

        if (count($this->flow)) {
            if ($next = $this->next()) {
                $this->response = $next->build();
            }
        }

        return $this->response;
    }

    /**
     * установить перевалочный пункт
     *
     * @return void
     */
    protected function setCheckpoint()
    {
        if (!is_null($this->checkpoint)) {
            unset($this->flow[$this->checkpoint]);
            unset($this->schema[$this->checkpoint]);
        }
    }

    /**
     * создать класс из неймспейса
     *
     * @return mixed
     */
    protected function next()
    {
        $class = __NAMESPACE__ . '\\' . ucfirst($this->schema[array_key_first($this->flow)]['verb']);
        if (class_exists($class)) {
            return new $class($this->schema, array_key_first($this->flow));
        }
    }
}