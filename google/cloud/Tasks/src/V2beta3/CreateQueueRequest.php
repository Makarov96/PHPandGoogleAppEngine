<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/tasks/v2beta3/cloudtasks.proto

namespace Google\Cloud\Tasks\V2beta3;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Request message for [CreateQueue][google.cloud.tasks.v2beta3.CloudTasks.CreateQueue].
 *
 * Generated from protobuf message <code>google.cloud.tasks.v2beta3.CreateQueueRequest</code>
 */
class CreateQueueRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Required.
     * The location name in which the queue will be created.
     * For example: `projects/PROJECT_ID/locations/LOCATION_ID`
     * The list of allowed locations can be obtained by calling Cloud
     * Tasks' implementation of
     * [ListLocations][google.cloud.location.Locations.ListLocations].
     *
     * Generated from protobuf field <code>string parent = 1;</code>
     */
    private $parent = '';
    /**
     * Required.
     * The queue to create.
     * [Queue's name][google.cloud.tasks.v2beta3.Queue.name] cannot be the same as an existing queue.
     *
     * Generated from protobuf field <code>.google.cloud.tasks.v2beta3.Queue queue = 2;</code>
     */
    private $queue = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $parent
     *           Required.
     *           The location name in which the queue will be created.
     *           For example: `projects/PROJECT_ID/locations/LOCATION_ID`
     *           The list of allowed locations can be obtained by calling Cloud
     *           Tasks' implementation of
     *           [ListLocations][google.cloud.location.Locations.ListLocations].
     *     @type \Google\Cloud\Tasks\V2beta3\Queue $queue
     *           Required.
     *           The queue to create.
     *           [Queue's name][google.cloud.tasks.v2beta3.Queue.name] cannot be the same as an existing queue.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Tasks\V2Beta3\Cloudtasks::initOnce();
        parent::__construct($data);
    }

    /**
     * Required.
     * The location name in which the queue will be created.
     * For example: `projects/PROJECT_ID/locations/LOCATION_ID`
     * The list of allowed locations can be obtained by calling Cloud
     * Tasks' implementation of
     * [ListLocations][google.cloud.location.Locations.ListLocations].
     *
     * Generated from protobuf field <code>string parent = 1;</code>
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Required.
     * The location name in which the queue will be created.
     * For example: `projects/PROJECT_ID/locations/LOCATION_ID`
     * The list of allowed locations can be obtained by calling Cloud
     * Tasks' implementation of
     * [ListLocations][google.cloud.location.Locations.ListLocations].
     *
     * Generated from protobuf field <code>string parent = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setParent($var)
    {
        GPBUtil::checkString($var, True);
        $this->parent = $var;

        return $this;
    }

    /**
     * Required.
     * The queue to create.
     * [Queue's name][google.cloud.tasks.v2beta3.Queue.name] cannot be the same as an existing queue.
     *
     * Generated from protobuf field <code>.google.cloud.tasks.v2beta3.Queue queue = 2;</code>
     * @return \Google\Cloud\Tasks\V2beta3\Queue
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Required.
     * The queue to create.
     * [Queue's name][google.cloud.tasks.v2beta3.Queue.name] cannot be the same as an existing queue.
     *
     * Generated from protobuf field <code>.google.cloud.tasks.v2beta3.Queue queue = 2;</code>
     * @param \Google\Cloud\Tasks\V2beta3\Queue $var
     * @return $this
     */
    public function setQueue($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Tasks\V2beta3\Queue::class);
        $this->queue = $var;

        return $this;
    }

}

