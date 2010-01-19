<?php
/**
 * @package ##PACKAGE##
 * @version ##VERSION##
 * @author Ladislav Prskavec ladislav@prskavec.net
 * @copyright Ladislav Prskavec (c) 2009
 *
 */
/**
 * ZFCore Utils
 * @package ##PACKAGE##
 *
 */
class ZFCore_Utils
{
    /*
     *
     * @param <type> $length
     * @param <type> $level
     * @return <type> 
     */
    public static function generatePassword($length=6, $level=2)
    {

        list($usec, $sec) = explode(' ', microtime());
        srand((float) $sec + ((float) $usec * 100000));

        $validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
        $validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $validchars[3] = "0123456789_!@#$%&*()-=+/abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#$%&*()-=+/";

        $password  = "";
        $counter   = 0;

        while ($counter < $length) {
            $actChar = substr($validchars[$level], rand(0, strlen($validchars[$level])-1), 1);

            // All character must be different
            if (!strstr($password, $actChar)) {
                $password .= $actChar;
                $counter++;
            }
        }

        return $password;

    }
    /**
     * sendEmail
     * @param array $options
     * @todo Add validate emails
     * @return void
     */  
    public static function sendEmail(array $options)
    {
        
        $mail = new Zend_Mail('UTF-8');        
        $mail->setBodyText($options['body']);
        $mail->setFrom($options['from']);
        $mail->addTo($options['to']);
        $mail->setSubject($options['subject']);
        $mail->send();

    }
    
    /**
     *  Application Logger 
     *  @param string $message
     *  @param array $options
     *  @return void
     */ 
    public static function log($message, array $options = null)
    {
        $loggerApp = Zend_Registry::getInstance()->get('loggerApp');
        $loggerApp->log($message, Zend_Log::DEBUG);
    }
    
}

