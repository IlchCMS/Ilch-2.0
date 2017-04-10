<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

class Mail
{
    /**
     * @var
     */
    protected $fromName;

    /**
     * @var
     */
    protected $fromEmail;

    /**
     * @var
     */
    protected $toName;

    /**
     * @var
     */
    protected $toEmail;

    /**
     * @var
     */
    protected $ccEmail;

    /**
     * @var
     */
    protected $bccEmail;

    /**
     * @var
     */
    protected $subject;

    /**
     * @var
     */
    protected $message;

    /**
     * @var string
     */
    protected $type;

    public function PHPMailer()
    {
        return new \PHPMailer();
    }

    /**
     * @return mixed
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @param mixed $fromName
     * @return $this
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * @param mixed $fromEmail
     * @return $this
     */
    public function setFromEmail($fromEmail)
    {
        $this->fromEmail = $fromEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToName()
    {
        return $this->toName;
    }

    /**
     * @param mixed $toName
     * @return $this
     */
    public function setToName($toName)
    {
        $this->toName = $toName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToEmail()
    {
        return $this->toEmail;
    }

    /**
     * @param mixed $toEmail
     * @return $this
     */
    public function setToEmail($toEmail)
    {
        $this->toEmail = $toEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCcEmail()
    {
        return $this->ccEmail;
    }

    /**
     * @param mixed $ccEmail
     * @return $this
     */
    public function setCcEmail($ccEmail)
    {
        $this->ccEmail = $ccEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBccEmail()
    {
        return $this->bccEmail;
    }

    /**
     * @param mixed $bccEmail
     * @return $this
     */
    public function setBccEmail($bccEmail)
    {
        $this->bccEmail = $bccEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    public function sent()
    {
        $config = \Ilch\Registry::get('config');
        $mail = $this->PHPMailer();
        $to = $this->getToEmail();
        if (empty($config->get('smtp_mode')) || $config->get('smtp_mode') == '0') {
            $this->type = 'mail';
        } else {
            $this->type = 'smtp';
        }

        switch ($this->type) {
            case 'smtp':
                $mail->isSMTP(); // telling the class to use SMTP
                $mail->SMTPAuth = true;
                //$mail->SMTPDebug = 2;
                $mail->Host = $config->get('smtp_server'); // SMTP server
                $mail->Port = (integer)$config->get('smtp_port'); // set the SMTP port
                if ($config->get('smtp_secure')) {
                    $mail->SMTPSecure = $config->get('smtp_secure');
                }
                $mail->Username = $config->get('smtp_user'); // SMTP account username
                $mail->Password = $config->get('smtp_pass'); // SMTP account password

                break;
            case 'mail':
                $mail->isMail(); // telling the class to use PHP's mail()
                break;
            case 'sendmail':
                $mail->isSendmail(); // telling the class to use Sendmail
                break;
            case 'qmail':
                $mail->isQmail(); // telling the class to use Qmail
                break;
        }

        try {
            if ($this->getFromName() != '') {
                $mail->addReplyTo($this->getFromEmail(), $this->getFromName());
                $mail->setFrom($this->getFromEmail(), $this->getFromName());
            } else {
                $mail->addReplyTo($this->getFromEmail());
                $mail->setFrom($this->getFromEmail(), $this->getFromEmail());
            }
            if ($this->getToName() != '') {
                $mail->addAddress($to, $this->getToName());
            } else {
                $mail->addAddress($to);
            }
            if ($this->getBccEmail() != '') {
                $indiBCC = explode(" ", $this->getBccEmail());
                foreach ($indiBCC as $key => $value) {
                    $mail->addBCC($value);
                }
            }
            if ($this->getCcEmail() != '') {
                $indiCC = explode(" ", $this->getCcEmail());
                foreach ($indiCC as $key => $value) {
                    $mail->addCC($value);
                }
            }
        } catch (\phpmailerException $e) { //Catch all kinds of bad addressing
            throw new \phpmailerException($e->getMessage());
        }
        $mail->isHTML();
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $this->getSubject();
        if ($this->getMessage() == '') {
            $body = '';
        } else {
            $body = $this->getMessage();
        }
        $mail->WordWrap = 78; // set word wrap to the RFC2822 limit
        $mail->Body = $body; //Create message bodies and embed images
        //$mail->addAttachment('images/phpmailer_mini.png', 'phpmailer_mini.png'); // optional name
        //$mail->addAttachment('images/phpmailer.png', 'phpmailer.png'); // optional name
        try {
            $mail->send();
            //exit(var_dump($mail->ErrorInfo));
        } catch (\phpmailerException $e) {
            throw new \phpmailerException($mail->ErrorInfo);
        }
    }
}
