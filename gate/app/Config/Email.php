<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    /**
     * @var string
     */
    public string $fromEmail  = 'guestandusersentry@gmail.com'; // <-- 1. PUT YOUR GMAIL HERE

    /**
     * @var string
     */
    public string $fromName   = 'GATE Admin'; // This is what the student sees as the sender name

    /**
     * @var string
     */
    public string $recipients = '';

    /**
     * The "protocol" to use. Valid options are: 'mail', 'sendmail', 'smtp'
     */
    public string $protocol = 'smtp'; // Must be smtp

    /**
     * The server path to Sendmail.
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Server Address
     */
    public string $SMTPHost = 'smtp.gmail.com'; // Google's SMTP Server

    /**
     * SMTP Username
     */
    public string $SMTPUser = 'guestandusersentry@gmail.com'; // <-- 2. PUT YOUR GMAIL HERE AGAIN

    /**
     * SMTP Password
     */
    public string $SMTPPass = 'qvxqiibrgneoomjs'; // <-- 3. PASTE THE 16-LETTER GOOGLE APP PASSWORD HERE (No spaces)

    /**
     * SMTP Port
     */
    public int $SMTPPort = 587;

    /**
     * SMTP Timeout (in seconds)
     */
    public int $SMTPTimeout = 5;

    /**
     * Enable persistent SMTP connections
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP Encryption.
     */
    public string $SMTPCrypto = 'tls';

    /**
     * Enable word-wrap
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at
     */
    public int $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     */
    public string $mailType = 'html'; // Required for the nicely styled button!

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = false;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use \r\n to comply with RFC 822)
     */
    public string $CRLF = "\r\n"; // Important for Gmail

    /**
     * Newline character. (Use \r\n to comply with RFC 822)
     */
    public string $newline = "\r\n"; // Important for Gmail

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;
}