<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: telephantmaster.proto

namespace App\Protos;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>TelephantPoint</code>
 */
class TelephantPoint extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>optional uint64 id = 1;</code>
     */
    protected $id = null;
    /**
     * Generated from protobuf field <code>optional string key = 2;</code>
     */
    protected $key = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int|string $id
     *     @type string $key
     * }
     */
    public function __construct($data = NULL) {
        \App\Protos\GPBMetadata\Telephantmaster::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>optional uint64 id = 1;</code>
     * @return int|string
     */
    public function getId()
    {
        return isset($this->id) ? $this->id : 0;
    }

    public function hasId()
    {
        return isset($this->id);
    }

    public function clearId()
    {
        unset($this->id);
    }

    /**
     * Generated from protobuf field <code>optional uint64 id = 1;</code>
     * @param int|string $var
     * @return $this
     */
    public function setId($var)
    {
        GPBUtil::checkUint64($var);
        $this->id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>optional string key = 2;</code>
     * @return string
     */
    public function getKey()
    {
        return isset($this->key) ? $this->key : '';
    }

    public function hasKey()
    {
        return isset($this->key);
    }

    public function clearKey()
    {
        unset($this->key);
    }

    /**
     * Generated from protobuf field <code>optional string key = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setKey($var)
    {
        GPBUtil::checkString($var, True);
        $this->key = $var;

        return $this;
    }

}

