<?php

namespace app\src\pbx\twiml;

use app\src\pbx\twiml\builder\TwimlMapper;

/**
 * Class Twiml
 * @package app\src\pbx\twiml
 */
class Twiml
{
    const VERB = 'verb';

    const NOUN = 'noun';

    const OPTIONS = 'options';

    /**
     * установить как эксмль
     *
     * @param array $scheme
     * @return string|null
     */
    public static function build(array $scheme): ?string
    {
        if (!empty($scheme))
            return (new TwimlMapper($scheme))->build()->asXML();

        return null;
    }
}