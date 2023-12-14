<?php


namespace app\src\pbx\scheme\element;


use app\src\models\Model;
use app\src\instance\Instance;
use app\src\pbx\scheme\model\Settings;

/**
 * Class BasicElement
 * @property string $verb
 * @property Instance $instance
 * @property Settings $settings
 * @package app\src\pbx\scheme\element
 */
abstract class BasicElement extends Model
{
    /**
     * глагол
     *
     * @var string
     */
    public string $verb;

    /**
     * образ
     *
     * @var Instance
     */
    protected Instance $instance;

    /**
     * настройки
     *
     * @var Settings
     */
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
     * вернуть как массив
     *
     * @return array
     */
    public function build(): array
    {
        return $this->asArray();
    }


}