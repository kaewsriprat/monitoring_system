<?php

$this->config['Wdb'] = array(
    'driver'   => WRITE_DRIVER,
    'host'     => WRITE_HOST,
    'username' => WRITE_USERNAME,
    'password' => WRITE_PASSWORD,
    'name'     => WRITE_DB_NAME
);

$this->config['Rdb'] = array(
    'driver'   => READ_DRIVER,
    'host'     => READ_HOST,
    'username' => READ_USERNAME,
    'password' => READ_PASSWORD,
    'name'     => READ_DB_NAME
);
