<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: telephantmaster.proto

namespace App\Protos;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>TelephantPointData</code>
 */
class TelephantPointData extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>repeated .TelephantPoint points = 1;</code>
     */
    private $points;
    /**
     * Generated from protobuf field <code>optional .TelephantPointResults results = 2;</code>
     */
    protected $results = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \App\Protos\TelephantPoint[]|\Google\Protobuf\Internal\RepeatedField $points
     *     @type \App\Protos\TelephantPointResults $results
     * }
     */
    public function __construct($data = NULL) {
        \App\Protos\GPBMetadata\Telephantmaster::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>repeated .TelephantPoint points = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Generated from protobuf field <code>repeated .TelephantPoint points = 1;</code>
     * @param \App\Protos\TelephantPoint[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setPoints($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \App\Protos\TelephantPoint::class);
        $this->points = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>optional .TelephantPointResults results = 2;</code>
     * @return \App\Protos\TelephantPointResults|null
     */
    public function getResults()
    {
        return $this->results;
    }

    public function hasResults()
    {
        return isset($this->results);
    }

    public function clearResults()
    {
        unset($this->results);
    }

    /**
     * Generated from protobuf field <code>optional .TelephantPointResults results = 2;</code>
     * @param \App\Protos\TelephantPointResults $var
     * @return $this
     */
    public function setResults($var)
    {
        GPBUtil::checkMessage($var, \App\Protos\TelephantPointResults::class);
        $this->results = $var;

        return $this;
    }

}

