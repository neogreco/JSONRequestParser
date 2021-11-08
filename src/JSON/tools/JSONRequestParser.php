<?php

namespace JSON\tools;
use VDX\Brotli\Exception\BrotliException;

class JSONRequestParser
{
    public static function extractJsonFrom(string $encodedData, $encodingHeader)
    {
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
        $decompressed=$inputString;
        if ($encoding == 'gzip') {
            $decompressed = gzdecode($inputString);
        } else if ($encoding == 'br') {
            $decompressed = brotli_uncompress($inputString);
        } else {
            throw new Exception("Not able to resolve for " . json_encode($encoding));
        }
        return $decompressed;
    }
}