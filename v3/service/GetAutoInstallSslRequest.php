<?php

namespace ascio\v3;

class GetAutoInstallSslRequest
{

    /**
     * @var string $Handle
     */
    protected $Handle = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return string
     */
    public function getHandle()
    {
      return $this->Handle;
    }

    /**
     * @param string $Handle
     * @return \ascio\v3\GetAutoInstallSslRequest
     */
    public function setHandle($Handle)
    {
      $this->Handle = $Handle;
      return $this;
    }

}
