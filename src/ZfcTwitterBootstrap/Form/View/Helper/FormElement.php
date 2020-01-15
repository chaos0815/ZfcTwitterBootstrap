<?php
/**
 * ZfcTwitterBootstrap
 */

namespace ZfcTwitterBootstrap\Form\View\Helper;

use Laminas\Form\ElementInterface;
use Laminas\Form\View\Helper\FormElement as LaminasFormElement;
use Laminas\Form\View\Helper\FormLabel;
use Laminas\Form\View\Helper\FormElementErrors;
use Laminas\View\Helper\EscapeHtml;

/**
 * Form Element
 */
class FormElement extends LaminasFormElement
{
    /**
     * @var \Laminas\Form\View\Helper\FormLabel
     */
    protected $labelHelper;

    /**
     * @var \Laminas\Form\View\Helper\LaminasFormElement
     */
    protected $elementHelper;

    /**
     * @var \Laminas\View\Helper\EscapeHtml
     */
    protected $escapeHelper;

    /**
     * @var \Laminas\Form\View\Helper\FormElementErrors
     */
    protected $elementErrorHelper;

    /**
     * @var FormDescription
     */
    protected $descriptionHelper;

    /**
     * @var string
     */
    protected $groupWrapper = '<div class="control-group%s" id="control-group-%s">%s</div>';

    /**
     * @var string
     */
    protected $controlWrapper = '<div class="controls" id="controls-%s">%s%s%s</div>';

    /**
     * Set Label Helper
     *
     * @param  Laminas\Form\View\Helper\FormLabel $labelHelper
     *
     * @return self
     */
    public function setLabelHelper(FormLabel $labelHelper)
    {
        $labelHelper->setView($this->getView());
        $this->labelHelper = $labelHelper;

        return $this;
    }

    /**
     * Get Label Helper
     *
     * @return \Laminas\Form\View\Helper\FormLabel
     */
    public function getLabelHelper()
    {
        if ( ! $this->labelHelper) {
            $this->setLabelHelper($this->view->plugin('formLabel'));
        }

        return $this->labelHelper;
    }

    /**
     * Set EscapeHtml Helper
     *
     * @param  \Laminas\View\Helper\EscapeHtml $escapeHelper
     *
     * @return self
     */
    public function setEscapeHtmlHelper(EscapeHtml $escapeHelper)
    {
        $escapeHelper->setView($this->getView());
        $this->escapeHelper = $escapeHelper;

        return $this;
    }

    /**
     * Get EscapeHtml Helper
     *
     * @return \Laminas\View\Helper\EscapeHtml
     */
    public function getEscapeHtmlHelper()
    {
        if ( ! $this->escapeHelper) {
            $this->setEscapeHtmlHelper($this->view->plugin('escapeHtml'));
        }

        return $this->escapeHelper;
    }

    /**
     * Set Element Helper
     *
     * @param  \Laminas\Form\View\Helper\FormElement $elementHelper
     *
     * @return self
     */
    public function setElementHelper(LaminasFormElement $elementHelper)
    {
        $elementHelper->setView($this->getView());
        $this->elementHelper = $elementHelper;

        return $this;
    }

    /**
     * Get Element Helper
     *
     * @return \Laminas\Form\View\Helper\FormElement
     */
    public function getElementHelper()
    {
        if ( ! $this->elementHelper) {
            $this->setElementHelper($this->view->plugin('formElement'));
        }

        return $this->elementHelper;
    }

    /**
     * Set Element Error Helper
     *
     * @param  \Laminas\Form\View\Helper\FormElementErrors $errorHelper
     *
     * @return self
     */
    public function setElementErrorHelper(FormElementErrors $errorHelper)
    {
        $errorHelper->setView($this->getView());

        $this->elementErrorHelper = $errorHelper;

        return $this;
    }

    /**
     * Get Element Error Helper
     *
     * @return \Laminas\Form\View\Helper\FormElementErrors
     */
    public function getElementErrorHelper()
    {
        if ( ! $this->elementErrorHelper) {
            $this->setElementErrorHelper($this->view->plugin('formElementErrors'));
        }

        return $this->elementErrorHelper;
    }

    /**
     * Set Description Helper
     *
     * @param FormDescription
     *
     * @return self
     */
    public function setDescriptionHelper(FormDescription $descriptionHelper)
    {
        $descriptionHelper->setView($this->getView());
        $this->descriptionHelper = $descriptionHelper;

        return $this;
    }

    /**
     * Get Description Helper
     *
     * @return FormDescription
     */
    public function getDescriptionHelper()
    {
        if ( ! $this->descriptionHelper) {
            $this->setDescriptionHelper($this->view->plugin('ztbFormDescription'));
        }

        return $this->descriptionHelper;
    }

    /**
     * Set Group Wrapper
     *
     * @param  string $groupWrapper
     *
     * @return self
     */
    public function setGroupWrapper($groupWrapper)
    {
        $this->groupWrapper = (string)$groupWrapper;

        return $this;
    }

    /**
     * Get Group Wrapper
     *
     * @return string
     */
    public function getGroupWrapper()
    {
        return $this->groupWrapper;
    }

    /**
     * Set Control Wrapper
     *
     * @param  string $controlWrapper ;
     *
     * @return self
     */
    public function setControlWrapper($controlWrapper)
    {
        $this->controlWrapper = (string)$controlWrapper;

        return $this;
    }

    /**
     * Get Control Wrapper
     *
     * @return string
     */
    public function getControlWrapper()
    {
        return $this->controlWrapper;
    }

    /**
     * Render
     *
     * @param  \Laminas\Form\ElementInterface $element
     * @param  string                      $groupWrapper
     * @param  string                      $controlWrapper
     *
     * @return string
     */
    public function render(ElementInterface $element, $groupWrapper = null, $controlWrapper = null)
    {
        $labelHelper = $this->getLabelHelper();
        $escapeHelper = $this->getEscapeHtmlHelper();
        $elementHelper = $this->getElementHelper();
        $elementErrorHelper = $this->getElementErrorHelper();
        $descriptionHelper = $this->getDescriptionHelper();
        $groupWrapper = $groupWrapper ?: $this->groupWrapper;
        $controlWrapper = $controlWrapper ?: $this->controlWrapper;
        $renderer = $elementHelper->getView();

        $hiddenElementForCheckbox = '';
        if (method_exists($element, 'useHiddenElement') && $element->useHiddenElement()) {
            // If we have hidden input with checkbox's unchecked value, render that separately so it can be prepended later, and unset it in the element
            $withHidden = $elementHelper->render($element);
            $withoutHidden = $elementHelper->render($element->setUseHiddenElement(false));
            $hiddenElementForCheckbox = str_ireplace($withoutHidden, '', $withHidden);
        }

        $id = $element->getAttribute('id') ?: $element->getAttribute('name');

        if (method_exists($renderer, 'plugin')) {
            if ($element instanceof \Laminas\Form\Element\Radio) {
                $renderer->plugin('form_radio')->setLabelAttributes(
                    [
                        'class' => 'radio',
                    ]
                );
            } elseif ($element instanceof \Laminas\Form\Element\MultiCheckbox) {
                $renderer->plugin('form_multi_checkbox')->setLabelAttributes(
                    [
                        'class' => 'checkbox',
                    ]
                );
            }
        }

        $controlLabel = '';
        $label = $element->getLabel();
        if (strlen($label) === 0) {
            $label = $element->getOption('label') ?: $element->getAttribute('label');
        }

        if ($label && ! $element->getOption('skipLabel')) {
            $controlLabel .= $labelHelper->openTag(
                [
                    'class' => ($element->getOption('wrapCheckboxInLabel') ? 'checkbox' : 'control-label'),
                ] + ($element->hasAttribute('id') ? ['for' => $id] : [])
            );

            if (null !== ($translator = $labelHelper->getTranslator())) {
                $label = $translator->translate(
                    $label,
                    $labelHelper->getTranslatorTextDomain()
                );
            }
            if ($element->getOption('wrapCheckboxInLabel')) {
                $controlLabel .= $elementHelper->render($element) . ' ';
            }
            if ($element->getOption('skipLabelEscape')) {
                $controlLabel .= $label;
            } else {
                $controlLabel .= $escapeHelper($label);
            }
            $controlLabel .= $labelHelper->closeTag();
            if ($element instanceof \Laminas\Form\Element\Radio || $element instanceof \Laminas\Form\Element\MultiCheckbox) {
                $controlLabel = str_replace(
                    [
                        '<label',
                        '</label>',
                    ],
                    [
                        '<div',
                        '</div>',
                    ],
                    $controlLabel
                );
            }
        }

        $controls = '';

        if ($element->getOption('wrapCheckboxInLabel')) {
            $controls = $controlLabel;
            $controlLabel = '';
        } else {
            $controls = $elementHelper->render($element);
        }

        $html = $hiddenElementForCheckbox . $controlLabel . sprintf(
                $controlWrapper,
                $id,
                $controls,
                $descriptionHelper->render($element),
                $elementErrorHelper->render($element)
            );

        $addtClass = ($element->getMessages()) ? ' error' : '';

        return sprintf($groupWrapper, $addtClass, $id, $html);
    }

    /**
     * Magical Invoke
     *
     * @param  \Laminas\Form\ElementInterface $element
     * @param  string                      $groupWrapper
     * @param  string                      $controlWrapper
     *
     * @return string|self
     */
    public function __invoke(ElementInterface $element = null, $groupWrapper = null, $controlWrapper = null)
    {
        if ($element) {
            return $this->render($element, $groupWrapper, $controlWrapper);
        }

        return $this;
    }
}
