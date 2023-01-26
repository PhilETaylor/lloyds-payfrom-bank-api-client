<?php
/*
 * @copyright  Copyright (C) 2022,2023 Blue Flame Digital Solutions Limited / Phil Taylor. All rights reserved.
 * @copyright  Copyright (C) 2022,2023 Red Evolution Limited. All rights reserved.
 * @author     Phil Taylor <phil@phil-taylor.com>
 * @see        https://github.com/PhilETaylor/lloyds-payfrom-bank-api-client
 * @license    The GNU General Public License v3.0
 */

use App\ApiService;
/*
 * @copyright  Copyright (C) 2022 Blue Flame Digital Solutions Limited / Phil Taylor. All rights reserved.
 * @copyright  Copyright (C) 2022 Red Evolution Limited. All rights reserved.
 * @author     Phil Taylor <phil@phil-taylor.com>
 * @see        https://github.com/PhilETaylor/lloyds-payfrom-bank-api-client
 * @license    The GNU General Public License v3.0
 */

use Symfony\Component\Dotenv\Dotenv;
/*
 * @copyright  Copyright (C) 2022 Blue Flame Digital Solutions Limited / Phil Taylor. All rights reserved.
 * @copyright  Copyright (C) 2022 Red Evolution Limited. All rights reserved.
 * @author     Phil Taylor <phil@phil-taylor.com>
 * @see        https://github.com/PhilETaylor/lloyds-payfrom-bank-api-client
 * @license    The GNU General Public License v3.0
 */

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

require 'vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env');

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig   = new Environment($loader, [
    'debug'=> true,
]);
$twig->addExtension(new DebugExtension());


$api = new ApiService();
echo $twig->render('return.html.twig', [
    'orderId'=> $api->getOrderId(),
    'res'    => $api->getTransactionData(),
]);
