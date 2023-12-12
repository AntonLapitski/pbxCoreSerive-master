<?php


namespace app\src\pbx\scheme\element;


use app\src\models\Model;
use app\src\instance\Instance;
use app\src\pbx\scheme\model\Settings;

/**
 * Class BasicElement
 * @package app\src\pbx\scheme\element
 */
abstract class BasicElement extends Model
{
    public string $verb;
    protected Instance $instance;
    protected Settings $settings;

    /**
     * BasicElement constructor.
     * @param Instance $instance
     * @param Settings $settings
     * @param array $config
     */
    public function __construct(Instance $instance, Settings $settings, $config = [])
    {
        parent::__construct($config);
        $this->instance = $instance;
        $this->settings = $settings;
    }

    /**
     * @return array
     */
    public function build(): array
    {
        return $this->asArray();
    }


}