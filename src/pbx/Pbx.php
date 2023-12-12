<?php

namespace app\src\pbx;

use app\src\instance\InstanceInterface;
use app\src\pbx\checkpoint\Checkpoint;
use app\src\pbx\router\Router;
use app\src\pbx\scheme\Scheme;
use app\src\pbx\service\Service;
use app\src\pbx\twiml\Twiml;


/**
 * Class Pbx
 * @package app\src\pbx
 */
class Pbx
{
    public InstanceInterface $instance;
    public Checkpoint $checkpoint;
    public Scheme $scheme;
    public string $service;

    /**
     * Pbx constructor.
     * @param InstanceInterface $instance
     */
    public function __construct(InstanceInterface $instance)
    {
        $this->instance = $instance;
        $this->checkpoint = Checkpoint::build($this->instance);
        $this->scheme = new Scheme($this);
        $this->service = (new Service($this->instance))->service($this->checkpoint, $this->scheme->flow);
    }

    /**
     * @return PbxData
     */
    public function data(): PbxData
    {
        Router::exec($this);
        $flow = $this->scheme->build();

        return new PbxData([
            'scheme' => $flow->scheme ?? null,
            'config' => $flow->config ?? null,
            'twiml' => Twiml::build($flow->scheme ?? []),
        ]);
    }
}   
