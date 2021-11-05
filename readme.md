# JSON Request Parser
From a Request content string and the header content encoding string, 
decompress and decode the info returning a Json String or null if cannot find a proper method.

## Installation
This project using composer.
```
$ composer require neogreco/json-request-parser
```

## Usage
Parse a json data
```php
<?php

use JSON\tools\JSONRequestParser;

class SomeController extends Controller
{

public function index(Request $request)
    {
        try {
            $json = JSONRequestParser::extractJsonFrom($request->getContent(), $request->header('Content-Encoding'));
```