<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Email module
 *
 * Ported from Kohana 2.2.3 Core to Kohana 3.0 module
 *
 * Updated to use Swiftmailer 4.0.4
 *
 * @package    Core
 *
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class Email
{

    // SwiftMailer instance
    protected static $mail;

    /**
     * Creates a SwiftMailer instance.
     *
     * @param   string  DSN connection string
     * @param null|mixed $config
     *
     * @return object Swift object
     */
    public static function connect($config = null)
    {
        if (! class_exists('Swift_Mailer', false)) {
            // Load SwiftMailer
            require Kohana::find_file('vendor', 'swift/swift_required');
        }

        // Load default configuration
        ($config === null) and $config = Kohana::$config;

        $config = $config->load('email');

        switch ($config['driver']) {
            case 'smtp':
                // Set port
                $port = empty($config['options']['port']) ? 25 : (int) $config['options']['port'];

                // Create SMTP Transport
                $transport = Swift_SmtpTransport::newInstance($config['options']['hostname'], $port);

                if (! empty($config['options']['encryption'])) {
                    // Set encryption
                    $transport->setEncryption($config['options']['encryption']);
                }

                // Do authentication, if part of the DSN
                empty($config['options']['username']) or $transport->setUsername($config['options']['username']);
                empty($config['options']['password']) or $transport->setPassword($config['options']['password']);

                // Set the timeout to 5 seconds
                $transport->setTimeout(empty($config['options']['timeout']) ? 5 : (int) $config['options']['timeout']);
            break;
            case 'sendmail':
                // Create a sendmail connection
                $transport = Swift_SendmailTransport::newInstance(empty($config['options']) ? "/usr/sbin/sendmail -bs" : $config['options']);

            break;
            default:
                // Use the native connection
                $transport = Swift_MailTransport::newInstance($config['options']);
            break;
        }

        // Create the SwiftMailer instance
        return Email::$mail = Swift_Mailer::newInstance($transport);
    }

    /**
     * Send an email message.
     *
     * @param   string|array  recipient email (and name), or an array of To, Cc, Bcc names
     * @param   string|array  sender email (and name)
     * @param   string        message subject
     * @param   string        message body
     * @param   bool       send email as HTML
     * @param mixed $to
     * @param mixed $from
     * @param mixed $subject
     * @param mixed $message
     * @param mixed $html
     *
     * @return int number of emails sent
     */
    public static function send($to, $from, $subject, $message, $html = false)
    {
        // Connect to SwiftMailer
        (Email::$mail === null) and email::connect();

        // Determine the message type
        $html = ($html === true) ? 'text/html' : 'text/plain';

        // Create the message
        $message = Swift_Message::newInstance($subject, $message, $html, 'utf-8');

        if (is_string($to)) {
            // Single recipient
            $message->setTo($to);
        } elseif (is_array($to)) {
            if (isset($to[0]) and isset($to[1])) {
                // Create To: address set
                $to = ['to' => $to];
            }

            foreach ($to as $method => $set) {
                if (! in_array($method, ['to', 'cc', 'bcc'], true)) {
                    // Use To: by default
                    $method = 'to';
                }

                // Create method name
                $method = 'add' . ucfirst($method);

                if (is_array($set)) {
                    // Add a recipient with name
                    $message->$method($set[0], $set[1]);
                } else {
                    // Add a recipient without name
                    $message->$method($set);
                }
            }
        }

        if (is_string($from)) {
            // From without a name
            $message->setFrom($from);
        } elseif (is_array($from)) {
            // From with a name
            $message->setFrom($from[0], $from[1]);
        }

        return Email::$mail->send($message);
    }
} // End email
