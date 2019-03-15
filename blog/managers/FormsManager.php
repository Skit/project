<?php


namespace blog\managers;

use Yii;
use stdClass;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class FormsManager
 * @package blog\managers
 */
class FormsManager extends Model
{
    protected
        /*
         * @var string name of main form
         */
        $mainFormName,
        /*
         * @var stdClass collect forms
         */
        $formsCollect;

    /**
     * FormsManager constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->formsCollect = new stdClass();
    }

    /**
     * @param Model $mainForm
     * @return $this
     */
    public function mergeForm(Model $mainForm)
    {
        $this->mainFormName = $mainForm->formName();
        $this->formsCollect->{$this->mainFormName} = $mainForm;

        return $this;
    }

    /**
     * @param Model ...$joinForms
     * @return $this
     */
    public function with(Model ...$joinForms)
    {
        if (! $this->mainFormName) {
            throw new \RuntimeException('Please, determine main form in mergeForm()');
        }

        foreach($joinForms as $form) {
            $this->formsCollect->{$form->formName()} = $form;
        }

        return $this;
    }

    /**
     * @param bool $validate
     * @return bool
     */
    public function mergeData(bool $validate = true): bool
    {
        /* @var $mainForm Model */
        $mainForm = $this->formsCollect->{$this->mainFormName};

        $mergeAttributes = $mainAttributes = $mainForm->getAttributes();
        foreach($this->getForms() as $formName => $form) {
            if ($this->mainFormIsNot($formName)) {
                $mergeAttributes = $this->merge($mergeAttributes, $mainAttributes, $form);
            }
        }

        $mainForm->setAttributes($mergeAttributes);

        if ($validate) {
            foreach ($mainAttributes as $name => $valueOld) {
                if ($mergeAttributes[$name] !== $valueOld) {
                    if(! $mainForm->validate($name)) {
                        $this->setErrorsToMergedForms($name, $mainForm->getErrors());
                        return false;
                    }
                }
            }
        }

       return true;
    }

    /**
     * @param Model $model
     */
    public function fillForms(Model $model)
    {
        /* @var $form self */
        $joinFormAttributes = [];
        foreach ($this->formsCollect as $form) {
            foreach($form->replaceAttributes() as $class => $data){

                if ($this->mainFormIs(getClassName($class))) {
                    foreach ($data as $attribute => $value) {
                        $form->{$attribute} = $model->{$attribute};
                        $joinFormAttributes[] = $attribute;
                    }
                }
            }
        }

        $mainForm = $this->formsCollect->{$this->mainFormName};
        foreach ($mainForm as $attribute => $value) {
            if(! in_array($attribute, $joinFormAttributes)) {
                $mainForm->{$attribute} = $model->{$attribute};
            }
        }
    }

    /**
     * @param array $mergeAttributes
     * @param array $mainAttributes
     * @param $form
     * @return array
     */
    protected function merge(array $mergeAttributes, array $mainAttributes, $form): array
    {
        /* @var $form self */
        foreach ($form->replaceAttributes() as $class => $data) {
            if ($this->mainFormIs(getClassName($class))) {
                foreach ($data as $attribute => $value) {
                    if (array_key_exists($attribute, $mainAttributes)) {
                        $mergeAttributes[$attribute] = $value;
                    }
                }
            }
        }

        return $mergeAttributes;
    }

    /**
     * @return array
     */
    public function replaceAttributes(): array
    {
        return [];
    }

    /**
     * @return Model
     */
    public function getMainForm(): Model
    {
        return $this->formsCollect->{$this->mainFormName};
    }

    /**
     * @return stdClass
     */
    public function getForms(): stdClass
    {
        return $this->formsCollect;
    }

    /**
     * @return bool
     */
    public function loadPost(): bool
    {
        /* @var $form self */
        $data = Yii::$app->request->post();
        $result = $success = parent::load($data);;
        foreach ($this->getForms() as $name => $form) {
            $result = $form->load($data, $name);

            if(! $result) {
                return $result;
            }
        }
        return $result;
    }

    /**
     * @param null $attributeNames
     * @param bool $clearErrors
     * @return bool
     */
    public function validate($attributeNames = null, $clearErrors = true): bool
    {
        /* @var $form self */
        $success = parent::validate($attributeNames, $clearErrors);
        foreach ($this->getForms() as $name => $form) {
            $innerNames = $attributeNames !== null ? ArrayHelper::getValue($attributeNames, $name) : null;
            $success = $form->validate($innerNames ?: null, $clearErrors) && $success;
        }
        return $success;
    }

    /**
     * @return array
     */
    public function getFirstErrors(): array
    {
        /* @var $form self */
        $errors = parent::getFirstErrors();
        foreach ($this->getForms() as $name => $form) {
            foreach ($form->getFirstErrors() as $attribute => $error) {
                $errors["{$name}.{$attribute}"] = $error;
            }
        }
        return $errors;
    }

    /**
     * @param string $formName
     * @return bool
     */
    protected function mainFormIsNot(string $formName): bool
    {
        return $this->mainFormName !== $formName;
    }

    /**
     * @param string $formName
     * @return bool
     */
    protected function mainFormIs(string $formName): bool
    {
        return $this->mainFormName === $formName;
    }

    /**
     * @param string $attribute
     * @param array $errors
     */
    protected function setErrorsToMergedForms(string $attribute, array $errors)
    {
        /* @var $form self */
        foreach ($this->getForms() as $formName => $form) {
            if ($this->mainFormIsNot($formName)) {
                if (in_array($attribute, array_flip($form->getAttributes()))) {
                    $form->addErrors($errors);
                }
            }
        }
    }
}
