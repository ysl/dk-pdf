==== Patch for preventing use html as image ====

src/RemoteContentFetcher.php
```
		// Check http status code.
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($http_code == 404) {
			$data = '';
		}
```
