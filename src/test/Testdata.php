<?php


$plantData = [

    [
        "height"        => "80",
        "location"      => "",
        "plant_id"      => "",
        "notes"         => "Fast growing seed",
        "ph"            => "1",
        "conductivity"  => "4",
        "temperature"   => "34.9",
        "humidity"      => "2",
        "lux"           => '2.3',
        "light_hours"   => '6.4',
    ]
];

$lifecyceles = [

    [
        "name" => "seed",
    ],
    [
        "name" => "youngin",
    ],
    [
        "name" => "oldy",
    ],
    [
        "name" => "dead",
    ]
];



$rooms = [
    [
        "name"        => "TEST_seedroom",
    ],
    [
        "name"        => "TEST_growingroom",
    ],
    [
        "name"        => "TEST_funroom",
    ],
];


$locations = [

    [
        "name"        => "TEST_orange"
    ],
    [
        "name"        => "TEST_red"
    ],
    [
        "name"        => "TEST_green"
    ],
];



$plant = array(
    "serial"    => "TEST_2099-01-01_13",
    "mortality"    => "1",
);


$testPlant = $plant;

$testPlant2 = $plant;





$testTiz = (object) [
    "userid"        => "10bc6670-1aff-405d-9912-ea65ff1abbb5",
    "access"        => "Editor",
    "username"      => "jtizzard",
    "name"          => "James",
    "groupid"       => "39c02f98-d579-415f-8da7-a952ade3668a",
    "groupaccess"   => "full",
    "groupname"     => "Arta"
];

$testLee = (object) [
    "userid"        => "da2a87e1-886d-4b75-8b42-2449a6cc6e14",
    "access"        => "Admin",
    "username"      => "lneenan",
    "name"          => "Lee",
    "groupid"       => "39c02f98-d579-415f-8da7-a952ade3668a",
    "groupaccess"   => "full",
    "groupname"     => "Arta"
];
