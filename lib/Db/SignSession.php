<?php
namespace OCA\OpenOTPSign\Db;

use JsonSerializable;

use OCP\AppFramework\Db\Entity;
use OCA\ServerInfo\Os;

class SignSession extends Entity implements JsonSerializable {

    private static $timeZone;

    protected $uid;
    protected $path;
    protected $isQualified;
    protected $recipient;
    protected $created;
    protected $session;
    protected $isPending;
    protected $isError;
    protected $message;
    protected $isYumisign;
    protected $expirationDate;

    public function __construct() {
        $this->addType('id','integer');
        $this->addType('isQualified', 'boolean');
        $this->addType('created', 'datetime');
        $this->addType('isPending', 'boolean');
        $this->addType('isError', 'boolean');
        $this->addType('isYumisign', 'boolean');
        $this->addType('expirationDate', 'datetime');

        $this->setIsQualified(false);
        $this->setCreated(new \DateTime());
        $this->setIsPending(true);
        $this->setIsError(false);
        $this->setIsYumisign(false);
    }

    public static function __constructStatic() {
        $os = new Os();
        $servertime  = $os->getTime();
        self::$timeZone = new \DateTimeZone(preg_split('/ +/', $servertime)[4]);
    }

    public function jsonSerialize() {
        $this->created->setTimezone(self::$timeZone);
        $this->expirationDate->setTimezone(self::$timeZone);

        return [
            'id' => $this->id,
            'path' => $this->path,
            'is_qualified' => $this->isQualified,
            'recipient' => $this->recipient,
            'created' => $this->created->format('Y-m-d H:i:s'),
            'session' => $this->session,
            'message'=> $this->message,
            'is_yumisign' => $this->isYumisign,
            'expiration_date' => $this->expirationDate->format('Y-m-d H:i:s')
        ];
    }
}

SignSession::__constructStatic();
