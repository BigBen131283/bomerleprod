<?php

namespace App\Services;

class AEScrypto
{
  // -------------------------------------------------------------------------------------
  /** @var string $key Hex encoded binary key for encryption and decryption */
  public $key = '';
  /** @var string $encrypt_method Method to use for encryption */
  private  $encrypt_method = 'aes-256-cbc';

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
    $new_iv = random_bytes( openssl_cipher_iv_length($this->encrypt_method)) ;
    if ( $encrypted = base64_encode(openssl_encrypt( $string, $this->encrypt_method, $this->key, 0, $new_iv )))
    {
      return bin2hex($new_iv).':'.$encrypted;
    }
    else
    {
      return false;
    }
  }

  // -------------------------------------------------------------------------------------
  public function decrypt (string $encrypted)
  {
    $parts = explode(':', $encrypted);
    $hexiv = $parts[0];
    $data = $parts[1];
    $iv = hex2bin($hexiv);
    if ( $decrypted = openssl_decrypt(base64_decode($data), $this->encrypt_method, $this->key, 0, $iv))
    {
      return $decrypted;
    }
    else
    {
      return false;
    }
  }
}