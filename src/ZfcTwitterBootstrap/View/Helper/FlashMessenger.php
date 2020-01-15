<?php
/**
 * ZfcTwitterBootstrap
 */

namespace ZfcTwitterBootstrap\View\Helper;

use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger as PluginFlashMessenger;
use Laminas\View\Helper\AbstractHelper;

/**
 * Helper to proxy the plugin flash messenger
 */
class FlashMessenger extends AbstractHelper
{
    protected $serviceLocator;

    /**
     * @var string
     */
    protected $titleFormat = '<%s>%s </%s>';

    /**
     * @var Alert
     */
    protected $alertHelper;

    /**
     * @var \Laminas\View\Helper\EscapeHtml
     */
    protected $escapeHtmlHelper;

    /**
     * @var \Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger
     */
    protected $pluginFlashMessenger;

    private $translator = false;

    /**
     * @var array Default attributes for the open format tag
     */
    protected $classMessages = [
        PluginFlashMessenger::NAMESPACE_INFO    => 'info',
        PluginFlashMessenger::NAMESPACE_ERROR   => 'error',
        PluginFlashMessenger::NAMESPACE_SUCCESS => 'success',
        PluginFlashMessenger::NAMESPACE_DEFAULT => 'warning',
    ];

    /**
     * @var array An array of allowed title tags
     */
    protected $allowedTags = [
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
        'b',
        'strong',
    ];

    /**
     * Returns the flash messenges as a string
     *
     * @return self|string
     */
    public function __invoke($namespace = null)
    {
        if (null === $namespace) {
            return $this;
        }

        return $this->render($namespace);
    }

    /**
     * Proxy the flash messenger plugin controller
     *
     * @param  string $method
     * @param  array  $argv
     *
     * @return mixed
     */
    public function __call($method, $argv)
    {
        $flashMessenger = $this->getPluginFlashMessenger();

        return call_user_func_array(
            [
                $flashMessenger,
                $method,
            ],
            $argv
        );
    }

    /**
     * Render Messages
     *
     * @param  array $namespace
     *
     * @return string
     */
    public function render($namespace = null)
    {
        $messagesToPrint = '';

        // get messages from each namespace.
        if (null === $namespace) {
            foreach ($this->classMessages as $namespace => $class) {
                $messagesToPrint .= $this->fetchMessagesFromNamespace($namespace);
            }
        } else {
            $messagesToPrint .= $this->fetchMessagesFromNamespace($namespace);
        }

        return $messagesToPrint;
    }

    /**
     * Gets messages from flash messenger plugin namespace
     *
     * @param  string $namespace
     *
     * @return string
     */
    protected function fetchMessagesFromNamespace($namespace)
    {

        /** @var \Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger $fm */
        $fm = $this->getPluginFlashMessenger();

        $fm->setNamespace($namespace);

        if ($fm->hasMessages()) {
            $messages = $fm->getMessagesFromNamespace($namespace);
            // reset namespace
            $fm->setNamespace();

            return $this->buildMessage($namespace, $messages);
        }

        return '';
    }

    /**
     * Build the message
     *
     * @param  string       $namespace
     * @param  array|string $messages
     *
     * @return string
     */
    protected function buildMessage($namespace, $messages)
    {
        $escapeHtml = $this->getEscapeHtmlHelper();
        $messagesToPrint = [];

        foreach ($messages as $message) {
            if (is_array($message)) {
                $isBlock = (isset($message['isBlock'])) ? true : false;

                if (isset($message['title'])) {
                    $title = $escapeHtml($message['title']);
                }

                if (isset($message['titleTag']) && in_array($message['titleTag'], $this->allowedTags)) {
                    $titleTag = $escapeHtml($message['titleTag']);
                } else {
                    $titleTag = ($isBlock) ? 'h4' : 'strong';
                }

                $messagesToPrint[] = $this->getAlert(
                    $namespace,
                    $this->translate($escapeHtml($message['message'])),
                    $title,
                    $titleTag,
                    $isBlock
                );
            } else {
                $messagesToPrint[] = $this->getAlert(
                    $namespace,
                    $this->translate($escapeHtml($message))
                );
            }
        }

        // Generate markup string
        $markup = implode(PHP_EOL, $messagesToPrint);

        return $markup;
    }

    /**
     * Get the alert string
     *
     * @param  string $namespace
     *
     * @return string $alert
     */
    protected function getAlert($namespace, $message, $title = null, $titleTag = 'h4', $isBlock = false)
    {
        $namespace = $this->classMessages[$namespace];

        $html = ($title) ? sprintf($this->titleFormat, $titleTag, $title, $titleTag) : '';
        $html .= $message . PHP_EOL;

        $alert = $this->getAlertHelper()->$namespace($html, $isBlock);

        return $alert;
    }

    /**
     * Retrieve the alert helper
     *
     * @return Alert
     */
    protected function getAlertHelper()
    {
        if ($this->alertHelper) {
            return $this->alertHelper;
        }

        $this->alertHelper = $this->view->plugin('ztbAlert');

        return $this->alertHelper;
    }

    /**
     * Retrieve the escapeHtml helper
     *
     * @return \Laminas\View\Helper\EscapeHtml
     */
    protected function getEscapeHtmlHelper()
    {
        if ($this->escapeHtmlHelper) {
            return $this->escapeHtmlHelper;
        }

        $this->escapeHtmlHelper = $this->view->plugin('escapeHtml');

        return $this->escapeHtmlHelper;
    }

    /**
     * Retrieve the flash messenger plugin
     *
     * @return \Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger
     */
    public function getPluginFlashMessenger()
    {
        if ($this->pluginFlashMessenger) {
            return $this->pluginFlashMessenger;
        }

        $this->pluginFlashMessenger = new \Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger();

        return $this->pluginFlashMessenger;
    }

    /**
     * @param string $message
     *
     * @return string
     */
    public function translate($message)
    {
        return $message;
    }
}
