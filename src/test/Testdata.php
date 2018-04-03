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
    ],
    [
        "height"        => "30",
        "location"      => "",
        "plant_id"      => "",
        "notes"         => "Slow growing seed",
        "ph"            => "2",
        "conductivity"  => "3",
        "temperature"   => "24.4",
        "humidity"      => "5",
        "lux"           => '1.3',
        "light_hours"   => '4.4',
    ],
    [
        "height"        => "4",
        "location"      => "",
        "plant_id"      => "",
        "notes"         => "Weird green seed",
        "ph"            => "1",
        "conductivity"  => "2",
        "temperature"   => "4.4",
        "humidity"      => "5",
        "lux"           => '3.3',
        "light_hours"   => '2.4',
    ],
    [
        "height"        => "120",
        "location"      => "",
        "plant_id"      => "",
        "notes"         => "Came in a nice pouch",
        "ph"            => "5",
        "conductivity"  => "4",
        "temperature"   => "34.4",
        "humidity"      => "6",
        "lux"           => '6.3',
        "light_hours"   => '2.4',
    ]
];

$lifecycles = [

    [
        "name" => "TEST_seed",
    ],
    [
        "name" => "TEST_youngin",
    ],
    [
        "name" => "TEST_oldy",
    ],
    [
        "name" => "TEST_dead",
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



$plants = [
    [
        "serial"    => "TEST_2099-01-01_13",
        "mortality"    => "1",
    ],
    [
        "serial"    => "TEST_2099-01-01_14",
        "mortality"    => "0",
    ],
    [
        "serial"    => "TEST_2099-01-01_15",
        "mortality"    => "0",
    ],

];


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
