<?php


namespace OnTheFlyForm;

use Bat\CaseTool;
use OnTheFlyForm\Helper\OnTheFlyFormHelper;
use OnTheFlyForm\Validator\ValidatorInterface;

class OnTheFlyForm implements OnTheFlyFormInterface
{

    private $ids;
    private $options;
    private $notHtmlSpecialChars;
    private $validationRules;
    private $key;
    private $injectedData;
    private $model;
    // form level
    private $action;
    private $method;
    private $errorMessage;
    private $successMessage;
    private $isSuccess;
    /**
     * @var ValidatorInterface
     */
    private $formValidator;

    public function __construct()
    {
        $this->ids = [];
        $this->options = [];
        $this->notHtmlSpecialChars = [];
        $this->successMessage = "Congratulations!";
        $this->validationRules = [];
        $this->injectedData = [];
        $this->key = 'key-' . uniqid(md5(rand(1, 100000) + time()));
        $this->action = '';
        $this->method = 'post'; // post|get
        $this->model = null;
        $this->errorMessage = null;
        $this->successMessage = null;
        $this->isSuccess = true;
    }


    public static function create(array $ids, $key = null)
    {
        $o = new static();
        $o->setIds($ids);
        $o->setKey($key);
        return $o;
    }


    //--------------------------------------------
    // SETTERS
    //--------------------------------------------
    public function setOptions($id, array $options)
    {
        $this->options[$id] = $options;
        return $this;
    }

    public function setNotHtmlSpecialChars(array $notHtmlSpecialCharsIds)
    {
        $this->notHtmlSpecialChars = $notHtmlSpecialCharsIds;
        return $this;
    }

    public function setSuccessMessage($successMessage)
    {
        $this->successMessage = $successMessage;
        return $this;
    }

    public function setValidationRules(array $validationRules)
    {
        $this->validationRules = $validationRules;
        return $this;
    }

    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
        $this->isSuccess = false;
        return $this;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    public function setIds(array $ids)
    {
        $this->ids = $ids;
        return $this;
    }

    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }





    //--------------------------------------------
    // FUNCTIONAL
    //--------------------------------------------
    public function isPosted()
    {
        $arr = ('post' === $this->method) ? $_POST : $_GET;
        return array_key_exists($this->key, $arr);
    }

    public function inject(array $data)
    {
        $this->injectedData = $data;
        return $this;
    }

    public function validate()
    {
        $this->prepareModel();
        $validator = $this->getValidator();
        if (true === $validator->validate($this->validationRules, $this->model)) {
            return true;
        }
        $this->isSuccess = false;
        return false;
    }

    public function getModel()
    {
        $this->prepareModel();
        $this->model['formMethod'] = $this->method;
        $this->model['formAction'] = $this->action;

        $this->model['errorMessage'] = $this->errorMessage;
        if (true === $this->isSuccess) {
            $this->model['successMessage'] = $this->successMessage;
        } else {
            $this->model['successMessage'] = "";
        }

        return $this->model;
    }



    //--------------------------------------------
    //
    //--------------------------------------------
    protected function getValidator()
    {
        if (null === $this->formValidator) {
            $this->formValidator = new \OnTheFlyForm\Validator\OnTheFlyFormValidator();
        }
        return $this->formValidator;
    }



    //--------------------------------------------
    //
    //--------------------------------------------
    private function prepareModel()
    {
        /**
         * The model needs to be prepared before the validation is executed,
         * or before the model is returned.
         */
        if (null === $this->model) {
            $model = [];

            //--------------------------------------------
            // CONTROL LEVEL
            //--------------------------------------------
            foreach ($this->ids as $id) {

                $value = (array_key_exists($id, $this->injectedData)) ? $this->injectedData[$id] : '';
                if (!in_array($id, $this->notHtmlSpecialChars)) {
                    $value = htmlspecialchars($value);
                }


                $pascal = OnTheFlyFormHelper::idToPascal($id);
                $model['name' . $pascal] = $id;
                $model['value' . $pascal] = $value;
                $model['error' . $pascal] = "";
            }

            foreach ($this->options as $id => $options) {
                $pascal = OnTheFlyFormHelper::idToPascal($id);
                $model['options' . $pascal] = $options;
            }

            $this->model = $model;
        }
        return $this->model;
    }
}

