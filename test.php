<?php

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Defuse\Crypto\KeyProtectedByPassword;

include_once __DIR__ . "/init/init.php";



$password = "123";
$protected_key_encoded = "def10000def50200d2d6133218dcc015d02e3c6beac5297a8db5ac040a944a5efb12b33cfad0ac8f9c9d1f013ec97c8de69ab63a147bbfda7bbd531fe801a2ac524c271674cf5e7a6534d19a10bcd7d891d6a7f1839b9a20668c59a7962d206159ef053971c0da1715255fbe5811ab7b81581ee061a903ec875b1ccefc0333ed4ca4140790524d290e10a45d53c95340b585c52011c37063cd3b123676cea55def253d92c51822f40819ca3147a38715b34bb18df8b702797fe7f43dba6c2e3aaafa7b0937bc2fd3e83aeec02e5adfe645f411a63840ca79f9167ece9a1d2902da1c9ca62477f85369c2296ea5aca9541cd2f7e88a2b16179cdfafa47a1f23ae";

$protected_key = KeyProtectedByPassword::loadFromAsciiSafeString($protected_key_encoded);
$user_key = $protected_key->unlockKey($password);
$user_key_encoded = $user_key->saveToAsciiSafeString();


$user_key = Key::loadFromAsciiSafeString($user_key_encoded);


$credit_card_number = "lorem ipsum dolor sit amet";
$encrypted_card_number = Crypto::encrypt($credit_card_number, $user_key);

echo $encrypted_card_number;

$credit_card_number_encrypted = "def50200c7b9cae871f9cfbf104cba1bd71dab0a70fa4dc565995051e57aa32eb439185451c3087e24741bcbb33665368700e2c2f7d2edb550b3b65511d5c12a4bb5a896bacde0e1e454effd6d457712d0ec7cbaf1d0f647ed603701a80d965f6ee6492507a051f88127daf3c86c";


$credit_card_number = Crypto::decrypt($credit_card_number_encrypted, $user_key);

echo $credit_card_number;
