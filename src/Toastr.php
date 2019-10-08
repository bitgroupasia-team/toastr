<?php

namespace Bitgroupasia\Toastr;

use Illuminate\Session\SessionManager;

class Toastr
{
    const SUCCESS = 'success';
    const ERROR = 'error';
    const INFO = 'info';
    const WARNING = 'warning';

    const TOASTR_NOTIFICATIONS = 'toastr:notifications';

    /** @var array */
    protected $notifications = [];

    /** @var SessionManager */
    protected $sessions;

    /** @var array */
    protected $allowedTypes = [self::ERROR, self::INFO, self::SUCCESS, self::WARNING];

    /**
     * Toastr constructor.
     *
     * @param SessionManager $session
     */
    public function __construct(SessionManager $session)
    {
        $this->session = $session;

        $this->notifications = $this->session->get(self::TOASTR_NOTIFICATIONS, []);
    }

    /**
     * Add notification success
     *
     * @param string $message
     * @param string $title
     * @param array $options
     * @return Toastr
     */
    public function success(string $message, string $title = '', array $options = []): self
    {
        return $this->addNotification(self::SUCCESS, $message, $title, $options);
    }

    /**
     * Add notification error
     *
     * @param string $message
     * @param string $title
     * @param array $options
     * @return Toastr
     */
    public function error(string $message, string $title = '', array $options = []): self
    {
        return $this->addNotification(self::ERROR, $message, $title, $options);
    }

    /**
     * Add notification info
     *
     * @param string $message
     * @param string $title
     * @param array $options
     * @return Toastr
     */
    public function info(string $message, string $title = '', array $options = []): self
    {
        return $this->addNotification(self::INFO, $message, $title, $options);
    }

    /**
     * Add notification warning
     *
     * @param string $message
     * @param string $title
     * @param array $options
     * @return Toastr
     */
    public function warning(string $message, string $title = '', array $options = []): self
    {
        return $this->addNotification(self::WARNING, $message, $title, $options);
    }

    /**
     * Add a notification
     *
     * @param string $type
     * @param string $message
     * @param string $title
     * @param array $options
     * @return Toastr
     */
    public function addNotification(string $type, string $message, string $title = '', array $options = []): self
    {
        $this->notifications[] = [
            'type' => in_array($type, $this->allowedTypes, true) ? $type : self::WARNING,
            'title' => $this->escapeSingleQuote($title),
            'message' => $this->escapeSingleQuote($message),
            'options' => json_encode($options),
        ];

        $this->session->flash(self::TOASTR_NOTIFICATIONS, $this->notifications);

        return $this;
    }

    /**
     * Render a toastr
     *
     * @return string
     */
    public function render(): string
    {
        $toastr = sprintf('<script type="text/javascript">%s</script>', $this->notificationsAsString());

        $this->session->forget(self::TOASTR_NOTIFICATIONS);

        return $toastr;
    }

    /**
     * Notification as string
     *
     * @return string
     */
    public function notificationsAsString(): string
    {
        return implode('', $this->notifications());
    }

    /**
     * map notifications to create an array toastr
     *
     * @return array
     */
    public function notifications(): array
    {
        return array_map(
            function($n) {
                return $this->toastr($n['type'], $n['message'], $n['title'], $n['options']);
            },
            $this->session->get(self::TOASTR_NOTIFICATIONS, [])
        );
    }

    /**
     * Toastr
     *
     * @param string $type
     * @param string $message
     * @param string $title
     * @param array $options
     *
     * @return string
     */
    public function toastr(string $type, string $message = '', string $title = '', string $options = ''): string
    {
        return sprintf('$.NotificationApp.send("%s","%s","bottom-left","rgba(0,0,0,0.2)","%s")', $title, $message, $type);
    }

    /**
     * escape single quote
     *
     * @param string $value
     *
     * @return string
     */
    private function escapeSingleQuote(string $value): string
    {
        return str_replace("'", "\\'", $value);
    }
}
