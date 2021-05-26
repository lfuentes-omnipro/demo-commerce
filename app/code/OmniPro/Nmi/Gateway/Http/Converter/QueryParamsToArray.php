<?php
namespace OmniPro\Nmi\Gateway\Http\Converter;

class QueryParamsToArray implements \Magento\Payment\Gateway\Http\ConverterInterface
{
    public function convert($response) {
        $response = explode("&",$response);
        for($i=0;$i<count($response);$i++) {
            $rdata = explode("=",$response[$i]);
            $responses[$rdata[0]] = $rdata[1];
        }
        return $responses;
    }
}