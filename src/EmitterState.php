<?php

namespace CrazyFactory\Kpi;

class EmitterState
{
    const CRITICAL = LOG_CRIT; // 2
    const WARNING = LOG_WARNING; // 4
    const INFO = LOG_INFO; // 6

    const NONE = LOG_SYSLOG; // 40
    /**
     * @var int $level
     */
    protected $level;
    /**
     * @var string $message
     */
    protected $message;
    /**
     * @var int $id
     */
    protected $id;
    /**
     * @var int $timestamp
     */
    protected $timestamp;

    /**
     * EmitterState constructor.
     *
     * @param int         $timestamp
     * @param int|null    $level
     * @param string|null $message
     * @param int|null    $id
     */
    public function __construct($timestamp, $level = LOG_SYSLOG, $message = null, $id = null)
    {
        $this->timestamp = $timestamp;
        $this->level = $level;
        $this->message = $message;
        $this->id = $id;
    }

    public static function stateToString($level)
    {
        switch ($level) {
            case self::CRITICAL:
                return 'CRITICAL';
            case self::WARNING:
                return 'WARNING';
            case self::INFO:
                return 'INFO';
            case self::NONE:
                return 'NONE';
        }

        return 'UNKNOWN-' . $level;
    }

    /**
     * @param array $array
     *
     * @return EmitterState
     */
    public static function fromArray($array)
    {
        $id = isset($array['id'])
            ? $array['id']
            : null;
        $level = isset($array['level'])
            ? $array['level']
            : null;
        $message = isset($array['message'])
            ? $array['message']
            : null;
        $timestamp = isset($array['timestamp'])
            ? $array['timestamp']
            : null;

        return new EmitterState($timestamp, $level, $message, $id);
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'level' => $this->level,
            'message' => $this->message,
            'timestamp' => $this->timestamp,
        );
    }
}
