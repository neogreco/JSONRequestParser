<?php

namespace JSON\tools;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use VDX\Brotli\Exception\BrotliException;

class JSONRequestParser
{
    public static function extractJsonFrom(string $encodedData, $encodingHeader)
    {
        // create a log channel
        $log = new Logger('json_parser.log');
        $log->pushHandler(new StreamHandler('json_parser.log', Logger::INFO));
        try {
            $decompressed = $encodedData;
            $headerEncodingArray=$encodingHeader;
            if (!is_array($encodingHeader)){
                $headerEncodingArray=array();
                array_push($headerEncodingArray,$encodingHeader);
            }
            foreach ($headerEncodingArray as $encoding){
                    $decompressed = JSONRequestParser::decodeString($decompressed,$encoding);
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

    public static function decodeString($inputString, $encoding){
          // create a log channel
          $log = new Logger('json_parser_decode.log');
        $decompressed=$inputString;
        if ($encoding == 'gzip') {
            $log->info("Gzip compression found!");
            $decompressed = gzdecode($inputString);
        } else if ($encoding == 'br') {
            $log->info("Brotli compression found!");
            $decompressed = brotli_uncompress($inputString);
        } else {
            $log->info("Not able to risolve for " . json_encode($encoding));
        }
        return $decompressed;
    }
}