<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpClient\HttpClient;

require 'vendor/autoload.php';
$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Create Session
    $client = HttpClient::create();

    $correlationId = sha1(\random_bytes(6));

    $merchantId = $_ENV[$_POST['env'] . '_MERCHANTID'];
    $password = $_ENV[$_POST['env'] . '_PASSWORD'];
    $endpoint = $_ENV[$_POST['env'] . '_ENDPOINT'];
    $returnUrl = $_ENV[$_POST['env'] . '_RETURNURL'];
    $openBankingJS = $_ENV[$_POST['env'] . '_OPENBANKINGURL'];

    $url = $endpoint . $merchantId . '/session';

    $response = $client->request(
        'POST',
        $url,
        [
            'json' => [
                'correlationId' => $correlationId,
                'session' => ['authenticationLimit' => 25]
            ],
            'verify_host' => !($_ENV['PROXY'] === "true"),
            'verify_peer' => !($_ENV['PROXY'] === "true"),
            'proxy' => $_ENV['PROXY'] === "true" ? '127.0.0.1:8888' : false,
            'auth_basic' => ['merchant.' . $merchantId, $password],
        ]);

    $content = $response->getContent(false);

    $sessionId = json_decode($content)->session->id;

    $updateUrl = $url = $endpoint . $merchantId . '/session/' . $sessionId;

    // Update Session
    $response = $client->request(
        'PUT',
        $updateUrl,
        [
            'json' => [
                'order' => [
                    'amount' => $_POST['amount'],
                    'currency' => 'GBP',
                    'id' => $_POST['orderId'],
                ],
                'transaction' => [
                    'id' => $_POST['transactionId'],
                ],
                'browserPayment' => [
                    'operation' => 'PAY',
                    'returnUrl' => $returnUrl,
                ]
            ],
            'verify_host' => !($_ENV['PROXY'] === "true"),
            'verify_peer' => !($_ENV['PROXY'] === "true"),
            'proxy' => $_ENV['PROXY'] === "true" ? '127.0.0.1:8888' : false,
            'auth_basic' => ['merchant.' . $merchantId, $password],
        ]);

    ?>
    <html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
              crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
                crossorigin="anonymous"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="<?php echo $openBankingJS ?>"></script>
        <script>
          try {
            var config = {
              merchantId: '<?php echo $merchantId ?>', //required
              merchantName: 'Osprey Housing',//required for display purpose
              amount: "<?php echo $_POST['amount']; ?>",//required for display purpose
              orderId: "<?php echo $_POST['orderId']; ?>",//required and same as step 2
              transactionId: "<?php echo $_POST['transactionId']; ?>",//required and same as step 2
              sessionId: '<?php echo $sessionId; ?>',//required and same as step 2
              wsVersion: '69',//optional, defaults to 57 if not provided, validate step 1 & 2 use same version
            };
            var callbackFunction = function (response) {
              console.log(response);
              OpenBanking.launchUI();
            };

            OpenBanking.configure(config, callbackFunction);
          } catch (e) {
          }
        </script>
    </head>
    <body>
    <div class="container">
        <div class="col-6">
            <br>
            <p>If nothing shows, then check the Javascript console for error messages from the OpenBanking JS</p>
            <br>

            <button class="btn btn-primary" onclick="OpenBanking.launchUI();return false;">Open popup again</button>
        </div>
    </div>
    </body>
    </html>

    <?php
} else {

    ?>
    <html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
              crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
                crossorigin="anonymous"></script>
    </head>
    <body>
    <div class="container">
        <div class="col-6">
            <br>
            <form method="post" action="index.php">
                <label class="form-label" for="env">Environment</label>
                <select class="form-control" name="env">
                    <option value="TEST" selected="selected">TEST</option>
                    <option value="LIVE">LIVE</option>
                </select>
                <br>
                <label class="form-label" for="orderId">Order Id</label>
                <input class="form-control" name="orderId" value="<?php echo bin2hex(random_bytes(3)); ?>">

                <br>
                <label class="form-label" for="transactionId">Transaction Id</label>
                <input class="form-control" name="transactionId" value="<?php echo bin2hex(random_bytes(3)); ?>">

                <br>
                <label class="form-label" for="amount">Amount Id</label>
                <input class="form-control" name="amount" value="1.<?php echo number_format(rand(10, 99), 0); ?>">

                <br>
                <input class="btn btn-primary" type="submit" value="Continue">
            </form>
        </div>
    </div>
    </body>
    </html>

    <?php
}
