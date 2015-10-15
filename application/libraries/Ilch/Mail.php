<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

defined('ACCESS') or die('no direct access');

/**
 * Ilch/Mail class.
 */
class Mail
{
    /**
     * @var array $to
     */
    protected $to = array();

    /**
     * @var string $subject
     */
    protected $subject;

    /**
     * @var string $message
     */
    protected $message;

    /**
     * @var array $headers
     */
    protected $headers = array();

    /**
     * @var string $params
     */
    protected $params;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Resets
     */
    public function reset()
    {
        $this->to = array();
        $this->headers = array();
        $this->subject = null;
        $this->message = null;
        $this->params = null;
        return $this;
    }

    /**
     * @param string $email
     * @param string $name
     */
    public function setTo($email, $name = null)
    {
        $this->to[] = $this->formatHeader((string) $email, (string) $name);
        return $this;
    }

    /**
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $subject
     *
     * @return string Subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $message
     *
     * @return message
     */
    public function setMessage($message)
    {
        $this->message = str_replace("\n.", "\n..", (string) $message);
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $email
     * @param string $name
     *
     * @return From
     */
    public function setFrom($email, $name)
    {
        $this->addMailHeader('From', (string) $email, (string) $name);
        return $this;
    }

    /**
     * @param string $header
     * @param string $email
     * @param string $name
     *
     * @return MailHeader
     */
    public function addMailHeader($header, $email = null, $name = null)
    {
        $address = $this->formatHeader((string) $email, (string) $name);
        $this->headers[] = sprintf('%s: %s', (string) $header, $address);
        return $this;
    }

    /**
     * @param string $header
     * @param mixed  $value
     *
     * @return GeneralHeader
     */
    public function addGeneralHeader($header, $value)
    {
        $this->headers[] = sprintf(
                '%s: %s',
                (string) $header,
                (string) $value
            );
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $additionalParameters
     *
     * @return string
     */
    public function setAdditionalParameters($additionalParameters)
    {
        $this->params = (string) $additionalParameters;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdditionalParameters()
    {
        return $this->params;
    }

    /**
     * @return boolean
     */
    public function send()
    {
        $to = $this->getToForSend();
        $headers = $this->getHeadersForSend();
        $message = $this->message;

        return mail($to, $this->subject, $message, $headers, $this->params);
    }

    /**
     * @param string $email
     * @param string $name
     *
     * @return string
     */
    public function formatHeader($email, $name = null)
    {
        if (empty($name)) {
            return $email;
        }
        return sprintf('"%s" <%s>', $name, $email);
    }

    /**
     * @return string
     */
    public function getHeadersForSend()
    {
        if (empty($this->headers)) {
            return '';
        }
        return implode(PHP_EOL, $this->headers);
    }

    /**
     * @return string
     */
    public function getToForSend()
    {
        if (empty($this->to)) {
            return '';
        }
        return implode(', ', $this->to);
    }
}
