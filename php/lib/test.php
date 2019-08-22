<?php

namespace shellShock\Capstone;

require_once("../Classes/profile-class.php");

$test = new Profile("0ed44843-effe-49d6-89de-3588b2d9a30f", "f73718223aeefedecabc880f7772c849", "hello@hi.com",
							"coolGuy", '$argon2i$v=19$m=1024,t=384,p=2$dE55dm5kRm9DTEYxNFlFUA$nNEMItrDUtwnDhZ41nwVm7ncBLrJzjh5mGIjj8RlzVA');

var_dump($test);