<?php

namespace App\Services;

class AEScrypto
{
  // -------------------------------------------------------------------------------------
  /** @var string $key Hex encoded binary key for encryption and decryption */
  public $key = '';
  /** @var string $encrypt_method Method to use for encryption */
  public  $encrypt_method = 'aes-256-cbc';

  // -------------------------------------------------------------------------------------
  /**
   * @param string $encryption_key Users binary encryption key in HEX encoding
   */
  function __construct ( $encryption_key = false )
  {
    if ( $key = hex2bin ( $encryption_key ) )
    {
      $this->key = $key;
    }
    else
    {
      echo "Key in construct does not appear to be HEX-encoded...";
    }
  }

  // -------------------------------------------------------------------------------------
  public function encrypt ( $string )
  {
    // dd(openssl_get_cipher_methods());
    // $new_iv = bin2hex ( random_bytes ( openssl_cipher_iv_length ( $this->encrypt_method ) ) );
    $new_iv = random_bytes( openssl_cipher_iv_length($this->encrypt_method)) ;
    // dump(openssl_cipher_iv_length ( $this->encrypt_method ));
    // dump(strlen($new_iv));
    // dump($new_iv);
    // dump(bin2hex($new_iv));
    if ( $encrypted = base64_encode ( openssl_encrypt ( $string, $this->encrypt_method, $this->key, 0, $new_iv ) ) )
    {
      dump($encrypted);
      return bin2hex($new_iv).':'.$encrypted;
    }
    else
    {
      return false;
    }
  }

  // -------------------------------------------------------------------------------------
  public function decrypt ( string $encrypted)
  {
    if ( $decrypted = openssl_decrypt ( base64_decode ( $encrypted ), $this->encrypt_method, $this->key) )
    {
      return $decrypted;
    }
    else
    {
      return false;
    }
  }
}