<?php

namespace JSON\tools;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use VDX\Brotli\Exception\BrotliException;

class JSONRequestParser
{
    public static function extractJsonFrom(String $encodedData, String $encodingHeader)
    {
        
        // create a log channel
        $log = new Logger('json_parser.log');
        $log->pushHandler(new StreamHandler('json_parser.log', Logger::INFO));
        try {
            
            if ($encodingHeader == 'gzip') {
                $log->info("Gzip compression found!");
                $decompressed = gzdecode($encodedData);
            } else if ($encodingHeader == 'br') {
                $log->info("Brotli compression found!");
                $decompressed = brotli_uncompress($encodedData);
            } else {
                $log->info("Not able to risolve for ".$encodingHeader);
            }
            $json = json_decode($decompressed);
        } catch (Exception $e) {
            return json_encode(
                array(
                    'status' => false,
                    'error' => $e->getMessage()
                    )
                );
            
        } catch (BrotliException $e) {
            return json_encode(
                array(
                'status' => false,
                'error' => $e->getMessage()
                )
            );
        }
        return $json;
    }
}