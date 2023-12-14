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
 * @property InstanceInterface $instance
 * @property Checkpoint $checkpoint
 * @property Scheme $scheme
 * @property string $service
 * @package app\src\pbx
 */
class Pbx
{
    /**
     * образ
     *
     * @var InstanceInterface
     */
    public InstanceInterface $instance;

    /**
     * проверочный пункт
     *
     * @var Checkpoint
     */
    public Checkpoint $checkpoint;

    /**
     * схема
     *
     * @var Scheme
     */
    public Scheme $scheme;

    /**
     * сервис
     *
     * @var string
     */
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
     * создать и возвратить объект
     *
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
