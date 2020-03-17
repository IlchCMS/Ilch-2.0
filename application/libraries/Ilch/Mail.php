<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch;

use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    /**
     * @var string
     */
    protected $fromName;

    /**
     * @var string
     */
    protected $fromEmail;

    /**
     * @var string
     */
    protected $toName;

    /**
     * @var string
     */
    protected $toEmail;

    /**
     * @var string
     */
    protected $ccEmail;

    /**
     * @var string
     */
    protected $bccEmail;

    /**
     * The "Reply-To:" field as described in RFC 5322 section 3.6.2
     * The reply field indicates the address(es) to which the author of
     * the message suggests that replies be sent.
     *
     * @var string
     */
    protected $replyTo;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $type;

    public function PHPMailer()
    {
        return new PHPMailer;
    }

    /**
     * Get the name of the author (from field)
     *
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * Set the name of the author (from field)
     *
     * @param string $fromName
     * @return $this
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;
        return $this;
    }

    /**
     * Get the mail address of the author (from field)
     *
     * @return string
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * Set the mail address of the author (from field)
     *
     * @param string $fromEmail
     * @return $this
     */
    public function setFromEmail($fromEmail)
    {
        $this->fromEmail = $fromEmail;
        return $this;
    }

    /**
     * Get the name of the receiver.
     *
     * @return string
     */
    public function getToName()
    {
        return $this->toName;
    }

    /**
     * Set the name of the receiver.
     *
     * @param string $toName
     * @return $this
     */
    public function setToName($toName)
    {
        $this->toName = $toName;
        return $this;
    }

    /**
     * Get the mail address of the receiver.
     *
     * @return string
     */
    public function getToEmail()
    {
        return $this->toEmail;
    }

    /**
     * Set the mail address of the receiver.
     *
     * @param string $toEmail
     * @return $this
     */
    public function setToEmail($toEmail)
    {
        $this->toEmail = $toEmail;
        return $this;
    }

    /**
     * Get the mail addresses of the cc field (carbon copy).
     * It can contain multiply mail addresses seperated by whitespaces.
     *
     * @return string
     */
    public function getCcEmail()
    {
        return $this->ccEmail;
    }

    /**
     * Set the mail addresses of the cc field (carbon copy).
     * It can contain multiply mail addresses seperated by whitespaces.
     *
     * @param string $ccEmail
     * @return $this
     */
    public function setCcEmail($ccEmail)
    {
        $this->ccEmail = $ccEmail;
        return $this;
    }

    /**
     * Get the mail addresses of the bcc field (blank carbon copy).
     * It can contain multiply mail addresses seperated by whitespaces.
     *
     * @return string
     */
    public function getBccEmail()
    {
        return $this->bccEmail;
    }

    /**
     * Get the mail addresses of the bcc field (blank carbon copy).
     * It can contain multiply mail addresses seperated by whitespaces.
     *
     * @param string $bccEmail
     * @return $this
     */
    public function setBccEmail($bccEmail)
    {
        $this->bccEmail = $bccEmail;
        return $this;
    }

    /**
     * Get the mail addresses of the "Reply-To:" field.
     * It can contain multiply mail addresses seperated by whitespaces.
     *
     * @return string
     * @since 2.1.32
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * Set the Get the mail addresses of the "Reply-To:" field.
     * It can contain multiply mail addresses seperated by whitespaces.
     *
     * @param string $replyTo
     * @return Mail
     * @since 2.1.32
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;
        return $this;
    }

    /**
     * Get the subject of the message.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set the subject of the message.
     *
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Get the body of the message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the body of the message.
     *
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get the type of the message.
     * This is phpmailer specific.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the type of the message.
     * This is phpmailer specific.
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Send an email.
     * Various fields of this class must be set for this to work.
     *
     * @throws \PHPMailer\PHPMailer\Exception
     */
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
            $mail->setFrom($this->getFromEmail(), $this->getFromName());
            if (!empty($this->getReplyTo())) {
                $mail->addReplyTo($this->getReplyTo());
            }
            if (!empty($this->getToName())) {
                $mail->addAddress($to, $this->getToName());
            } else {
                $mail->addAddress($to);
            }
            if (!empty($this->getBccEmail())) {
                $indiBCC = explode(' ', $this->getBccEmail());
                foreach ($indiBCC as $key => $value) {
                    $mail->addBCC($value);
                }
            }
            if (!empty($this->getCcEmail())) {
                $indiCC = explode(' ', $this->getCcEmail());
                foreach ($indiCC as $key => $value) {
                    $mail->addCC($value);
                }
            }
            if (!empty($this->getReplyTo())) {
                $indiReplyTo = explode(' ', $this->getReplyTo());
                foreach ($indiReplyTo as $key => $value) {
                    $mail->addReplyTo($value);
                }
            }
        } catch (\phpmailerException $e) { // Catch all kinds of bad addressing
            throw new \phpmailerException($e->getMessage());
        }
        $mail->isHTML();
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $this->getSubject();
        if (empty($this->getMessage())) {
            $body = '';
        } else {
            $body = $this->getMessage();
        }
        $mail->WordWrap = 78; // Set word wrap to the RFC2822 limit
        $mail->Body = $body; // Create message bodies and embed images
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
