<?php

/**
 * 商户号、公钥、私钥配置
 */
return array(
  'mid' => 'COPMAC000001',
  'public_key' => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDPg0O4rPQJL1O+jqJ4rBjFVNRAuDmBSoii9pYfPQBaescCVY0irkWWoLyfTT65TjvnPpOx+IfNzBTlB13qCEFm7algREoeUHjFgFNHiXJ2LK/R0+VWgXe5+EDFfbrFCPnmLKG3OcKDGQszP0VOf6VVTM1t56CpgaRMm1/+Tzd2TQIDAQAB',
  'private_key' => 'MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAOrb0WEO7Q3VcW6G64UPy7xB5S12hz9UPId680VW4cR8v/aPJ2Y+a1vOj2haPy0mJYHGXEPYyJabljEQ3oJYSSbikG3NBA6B7b+Pmz7jm2sJgtsqtINAZp5COo5RICtkB1bE/Ys8Q1//L8Q3z4krwTkCt15I7uAqO28SR40+VRyPAgMBAAECgYBCPFpxqBb9Bsxl4pE7xrer0V3EE6CTILutbeR8EsO7eX9eFwOCl2oJy1iwknsszYxYbEwLKnFv4FNcZSiL1EBUt9O4m3umB/X2x+Wrn2LXG6ujrPSp/IeRLpOXKAvCElca0k0Ax6N0KgC79aCaiRucEsedlHpQHnGvgyQZqlNwSQJBAPafchIFhy/FMHUT9MWYd051q+GmsdydBf3wG4dYMBiQuD9P/ceaY/ETghpiYm1eGQFMVS4xl2X0JIh3plBM+L0CQQDzyd0BzwOX9yFBuCWpaTtNpj4VWFk/T6+k/6Uq8a8dhMIncO1x8TC49WFXzHf7M/ml3sQSaqvUTmk0tKyuqf07AkBj6CG4xT6HjpVbyHLPHT2vriGsLvA8k+vafEtan9IUEYRiOZBwLM4x2hpJf/OppIXyra6QIQzZA3dNRVM9koDtAkEA3ULUNKUHlPA2HeidPgIFUfdVF5hlAAI5314rMSvDjN4GPTfQOf73Apeq7r3kE3lb1sC1YiWwqHM2JyDoLWqZKwJBALZw+FAUBbQW6+uof7akNStG+hSFug2R4iJumCpCxJGATjDQo7IY3KdjdjN4dNI/UpmvEuilqiuv5g+Jx26lpcE=',
  //回调地址
  'callback_url' => 'http://商户网址/lakala_php_demo/ctrl/callback.php',
  'notify_url' => 'http://商户网址/lakala_php_demo/ctrl/notify.php',
  'notifyAddrUrl' => 'http://商户网址/lakala_php_demo/ctrl/DownLoadMerSetFile.php',

  //支付、订单查询地址
  'ppayGateUrl' => 'http://testinl.lakala.com:8080/ppayGate/CrossBorderWebPay.do',
);
