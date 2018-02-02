<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_HTTP_Exception_401 extends HTTP_Exception_Expected
{

    /**
     * @var int HTTP 401 Unauthorized
     */
    protected $_code = 401;

    /**
     * Specifies the WWW-Authenticate challenge.
     *
     * @param string $challenge WWW-Authenticate challenge (eg `Basic realm="Control Panel"`)
     */
    public function authenticate($challenge = null)
    {
        if ($challenge === null) {
            return $this->headers('www-authenticate');
        }

        $this->headers('www-authenticate', $challenge);

        return $this;
    }

    /**
     * Validate this exception contains everything needed to continue.
     *
     * @throws Kohana_Exception
     *
     * @return bool
     */
    public function check()
    {
        if ($this->headers('www-authenticate') === null) {
            throw new Kohana_Exception('A \'www-authenticate\' header must be specified for a HTTP 401 Unauthorized');
        }

        return true;
    }
}
