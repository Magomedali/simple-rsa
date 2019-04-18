<?php
/*
	RSA example
*/
function isSimple($number):bool
{
	if($number < 1) return false;

	$check = true; 
	for ($i=2; $i < $number; $i++) { 
		if(!($number % $i)){
			$check = false;
		}
	}

	return $check;
}

$msg = isset($argv[1]) ? (int)$argv[1] : 3;
$f = isset($argv[2]) ? (int)$argv[2] : 3;
$s = isset($argv[3]) ? (int)$argv[3] : 11;

echo PHP_EOL."------ RSA example --------" .PHP_EOL.PHP_EOL;

echo  "> first simple number(P)  : ". $f . PHP_EOL;
echo  "> second simple number(Q) : ". $s . PHP_EOL;
echo  "> msg                     : ". $msg . PHP_EOL;


$n = $f * $s;
echo  "> N =  P * Q              : {$n} = {$f} * {$s}" . PHP_EOL;

$fn = ($f - 1) * ($s - 1);
echo  "> F =  (P-1) * (Q-1)      : {$fn} = ({$f}-1) * ({$s}-1)" . PHP_EOL;

$possible_public_keys = [];
for ($i=2; $i < $fn; $i++){ 

	if(!($fn % $i))
	 	continue;

	if(!isSimple($i))
		continue;

	$possible_public_keys[] = $i;
}

if(!count($possible_public_keys)){
	echo  "> Error: didn`t compute possible keys" . PHP_EOL.PHP_EOL;
	exit(2);
}


echo  "> Possible public keys: " . PHP_EOL;
foreach ($possible_public_keys as $key) {
	echo  "> ------------: {$key}" . PHP_EOL;
}

$public_key = $possible_public_keys[0];
echo  "> Selected public key: {$public_key}" . PHP_EOL;

echo  "> Computing private key by formule (public * private) mod F = 1   ....." . PHP_EOL;
$private_key = null;
for ($i=1; $i < $fn; $i++){ 
	$d = $fn * $i + 1;

	if($d % $public_key)
		continue;

	$private_key = $d / $public_key;
	break;
}
if(!$private_key){
	echo  "> Error: Didn`t compute private key" . PHP_EOL.PHP_EOL;
	exit(2);
}

echo  "> Computed private key: {$private_key}" . PHP_EOL;

$en_msg = ($msg ** $public_key) % $n; 

echo  "> encrypted msg: ". $en_msg . PHP_EOL;

$dec_msg = ($en_msg ** $private_key) % $n;

echo  "> decrypted msg: ". $dec_msg . PHP_EOL;

?>
