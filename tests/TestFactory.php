<?php
use NeoStore\Entity\Tax;
class TestFactory
{
    public static function createQuebecAddress()
    {
        return array(
                "address1" => "2280 de la rousserolle",
                "address2" => "app 2",
                "city" => "laval",
                "company" => "Nekken Corp",
                "firstName" => "Dylan",
                "lastName" => "Nguyen",
                "phone" => "514-690-6330",
                "region" => array(
                        "name" => "Quebec",
                        "code" => "QC",
                        "taxRate" => array(
                                "percentage" => "9.975",
                                "title" => "PST"
                        )
                ),
                "country" => array(
                        "name" => "Canada",
                        "code" => "CA",
                        "taxRate" => array(
                                "percentage" => "5",
                                "title" => "GST",
                                "type" => "normal"
                        )
                ),
                "zip" => "H7L0C4"
        );
    }
    public static function createOntarioAddress()
    {
        return array(
                "address1" => "2280 de la rousserolle",
                "address2" => "app 2",
                "city" => "laval",
                "company" => "Nekken Corp",
                "firstName" => "Dylan",
                "lastName" => "Nguyen",
                "phone" => "514-690-6330",
                "region" => array(
                        "name" => "Ontario",
                        "code" => "ON",
                        "taxRate" => array(
                                "percentage" => "13",
                                "title" => "HST",
                                "type" => Tax::TYPE_HARMONIZED
                        )
                ),
                "country" => array(
                        "name" => "Canada",
                        "code" => "CA",
                        "taxRate" => array(
                                "percentage" => "5",
                                "title" => "GST",
                                "type" => "normal"
                        )
                ),
                "zip" => "H7L0C4"
        );
    }
}