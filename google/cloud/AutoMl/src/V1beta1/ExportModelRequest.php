<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/automl/v1beta1/service.proto

namespace Google\Cloud\AutoMl\V1beta1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Request message for [AutoMl.ExportModel][google.cloud.automl.v1beta1.AutoMl.ExportModel].
 * Models need to be enabled for exporting, otherwise an error code will be
 * returned.
 *
 * Generated from protobuf message <code>google.cloud.automl.v1beta1.ExportModelRequest</code>
 */
class ExportModelRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Required. The resource name of the model to export.
     *
     * Generated from protobuf field <code>string name = 1;</code>
     */
    private $name = '';
    /**
     * Required. The desired output location and configuration.
     *
     * Generated from protobuf field <code>.google.cloud.automl.v1beta1.ModelExportOutputConfig output_config = 3;</code>
     */
    private $output_config = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $name
     *           Required. The resource name of the model to export.
     *     @type \Google\Cloud\AutoMl\V1beta1\ModelExportOutputConfig $output_config
     *           Required. The desired output location and configuration.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Automl\V1Beta1\Service::initOnce();
        parent::__construct($data);
    }

    /**
     * Required. The resource name of the model to export.
     *
     * Generated from protobuf field <code>string name = 1;</code>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Required. The resource name of the model to export.
     *
     * Generated from protobuf field <code>string name = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setName($var)
    {
        GPBUtil::checkString($var, True);
        $this->name = $var;

        return $this;
    }

    /**
     * Required. The desired output location and configuration.
     *
     * Generated from protobuf field <code>.google.cloud.automl.v1beta1.ModelExportOutputConfig output_config = 3;</code>
     * @return \Google\Cloud\AutoMl\V1beta1\ModelExportOutputConfig
     */
    public function getOutputConfig()
    {
        return $this->output_config;
    }

    /**
     * Required. The desired output location and configuration.
     *
     * Generated from protobuf field <code>.google.cloud.automl.v1beta1.ModelExportOutputConfig output_config = 3;</code>
     * @param \Google\Cloud\AutoMl\V1beta1\ModelExportOutputConfig $var
     * @return $this
     */
    public function setOutputConfig($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\AutoMl\V1beta1\ModelExportOutputConfig::class);
        $this->output_config = $var;

        return $this;
    }

}

