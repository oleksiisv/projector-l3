<?php
declare(strict_types=1);
require 'vendor/autoload.php';
chdir(__DIR__);

use Svystunov\Projectorl3\Receiver;
use Svystunov\Projectorl3\Sender;

$receiver = new Receiver();
$sender = new Sender();
$rate = $receiver->getCurrencyRate();

echo $sender->sendCurrencyRateToGa($rate);
