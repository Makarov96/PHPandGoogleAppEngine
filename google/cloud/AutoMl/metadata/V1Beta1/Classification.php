<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/automl/v1beta1/classification.proto

namespace GPBMetadata\Google\Cloud\Automl\V1Beta1;

class Classification
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        \GPBMetadata\Google\Cloud\Automl\V1Beta1\Temporal::initOnce();
        \GPBMetadata\Google\Api\Annotations::initOnce();
        $pool->internalAddGeneratedFile(hex2bin(
            "0a960c0a30676f6f676c652f636c6f75642f6175746f6d6c2f7631626574" .
            "61312f636c617373696669636174696f6e2e70726f746f121b676f6f676c" .
            "652e636c6f75642e6175746f6d6c2e763162657461311a1c676f6f676c65" .
            "2f6170692f616e6e6f746174696f6e732e70726f746f22290a18436c6173" .
            "73696669636174696f6e416e6e6f746174696f6e120d0a0573636f726518" .
            "012001280222c7010a1d566964656f436c617373696669636174696f6e41" .
            "6e6e6f746174696f6e120c0a047479706518012001280912580a19636c61" .
            "7373696669636174696f6e5f616e6e6f746174696f6e18022001280b3235" .
            "2e676f6f676c652e636c6f75642e6175746f6d6c2e763162657461312e43" .
            "6c617373696669636174696f6e416e6e6f746174696f6e123e0a0c74696d" .
            "655f7365676d656e7418032001280b32282e676f6f676c652e636c6f7564" .
            "2e6175746f6d6c2e763162657461312e54696d655365676d656e74229307" .
            "0a1f436c617373696669636174696f6e4576616c756174696f6e4d657472" .
            "696373120e0a0661755f70726318012001280212170a0b626173655f6175" .
            "5f70726318022001280242021801120e0a0661755f726f63180620012802" .
            "12100a086c6f675f6c6f737318072001280212750a18636f6e666964656e" .
            "63655f6d6574726963735f656e74727918032003280b32532e676f6f676c" .
            "652e636c6f75642e6175746f6d6c2e763162657461312e436c6173736966" .
            "69636174696f6e4576616c756174696f6e4d6574726963732e436f6e6669" .
            "64656e63654d657472696373456e74727912660a10636f6e667573696f6e" .
            "5f6d617472697818042001280b324c2e676f6f676c652e636c6f75642e61" .
            "75746f6d6c2e763162657461312e436c617373696669636174696f6e4576" .
            "616c756174696f6e4d6574726963732e436f6e667573696f6e4d61747269" .
            "78121a0a12616e6e6f746174696f6e5f737065635f69641805200328091a" .
            "fc020a16436f6e666964656e63654d657472696373456e747279121c0a14" .
            "636f6e666964656e63655f7468726573686f6c64180120012802121a0a12" .
            "706f736974696f6e5f7468726573686f6c64180e20012805120e0a067265" .
            "63616c6c18022001280212110a09707265636973696f6e18032001280212" .
            "1b0a1366616c73655f706f7369746976655f726174651808200128021210" .
            "0a0866315f73636f726518042001280212120a0a726563616c6c5f617431" .
            "18052001280212150a0d707265636973696f6e5f61743118062001280212" .
            "1f0a1766616c73655f706f7369746976655f726174655f61743118092001" .
            "280212140a0c66315f73636f72655f617431180720012802121b0a137472" .
            "75655f706f7369746976655f636f756e74180a20012803121c0a1466616c" .
            "73655f706f7369746976655f636f756e74180b20012803121c0a1466616c" .
            "73655f6e656761746976655f636f756e74180c20012803121b0a13747275" .
            "655f6e656761746976655f636f756e74180d200128031aaa010a0f436f6e" .
            "667573696f6e4d6174726978121a0a12616e6e6f746174696f6e5f737065" .
            "635f6964180120032809125d0a03726f7718022003280b32502e676f6f67" .
            "6c652e636c6f75642e6175746f6d6c2e763162657461312e436c61737369" .
            "6669636174696f6e4576616c756174696f6e4d6574726963732e436f6e66" .
            "7573696f6e4d61747269782e526f771a1c0a03526f7712150a0d6578616d" .
            "706c655f636f756e741801200328052a590a12436c617373696669636174" .
            "696f6e5479706512230a1f434c415353494649434154494f4e5f54595045" .
            "5f554e5350454349464945441000120e0a0a4d554c5449434c4153531001" .
            "120e0a0a4d554c54494c4142454c100242b8010a1f636f6d2e676f6f676c" .
            "652e636c6f75642e6175746f6d6c2e763162657461314213436c61737369" .
            "6669636174696f6e50726f746f5a41676f6f676c652e676f6c616e672e6f" .
            "72672f67656e70726f746f2f676f6f676c65617069732f636c6f75642f61" .
            "75746f6d6c2f763162657461313b6175746f6d6cca021b476f6f676c655c" .
            "436c6f75645c4175746f4d6c5c56316265746131ea021e476f6f676c653a" .
            "3a436c6f75643a3a4175746f4d4c3a3a56316265746131620670726f746f" .
            "33"
        ), true);

        static::$is_initialized = true;
    }
}

