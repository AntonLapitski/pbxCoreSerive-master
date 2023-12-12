<?php

namespace app\src\pbx\twiml\builder;


use app\src\pbx\twiml\Twiml;
use Twilio\TwiML\VoiceResponse;

/**
 * Class TwimlMapper
 * @package app\src\pbx\twiml\builder
 */
class TwimlMapper
{
    protected VoiceResponse $response;

    protected array $schema;
    protected array $flow;
    protected mixed $checkpoint;

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
     *
     */
    protected function setCheckpoint()
    {
        if (!is_null($this->checkpoint)) {
            unset($this->flow[$this->checkpoint]);
            unset($this->schema[$this->checkpoint]);
        }
    }

    /**
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