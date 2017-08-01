<?php


namespace OnTheFlyForm;


use OnTheFlyForm\DataAdaptor\DataAdaptorInterface;
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
    private $validationOk;
    //
    private $radioItems;
    private $singleCheckboxes;
    /**
     * @var ValidatorInterface
     */
    private $formValidator;

    /**
     * @var DataAdaptorInterface|null
     */
    private $inputDataAdaptor;

    /**
     * @var DataAdaptorInterface|null
     */
    private $outputDataAdaptor;
    private $labels;

    public function __construct()
    {
        $this->ids = [];
        $this->options = [];
        $this->notHtmlSpecialChars = [];
        $this->successMessage = "Congratulations!";
        $this->validationRules = [];
        $this->injectedData = [];
        $this->key = 'ontheflyform_default_key';
        $this->action = '';
        $this->method = 'post'; // post|get
        $this->model = null;
        $this->errorMessage = "";
        $this->successMessage = "";
        $this->isSuccess = true;
        $this->validationOk = true;
        //
        $this->radioItems = [];
        $this->singleCheckboxes = [];
        $this->labels = [];
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


    public function setRadioItems($id, array $radioItems)
    {
        $this->radioItems[$id] = $radioItems;
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

    public function setInputDataAdaptor(DataAdaptorInterface $inputDataAdaptor)
    {
        $this->inputDataAdaptor = $inputDataAdaptor;
        return $this;
    }

    public function setOutputDataAdaptor(DataAdaptorInterface $outputDataAdaptor)
    {
        $this->outputDataAdaptor = $outputDataAdaptor;
        return $this;
    }


    public function setSingleCheckboxes(array $singleCheckboxes)
    {
        $this->singleCheckboxes = $singleCheckboxes;
        return $this;
    }

    public function setLabels(array $labels)
    {
        $this->labels = $labels;
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

    public function inject(array $data, $useAdaptor = false)
    {
        if (true === $useAdaptor && null !== $this->inputDataAdaptor) {
            $data = $this->inputDataAdaptor->transform($data);
        }
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
        $this->validationOk = false;
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
            $this->model['isSuccess'] = true;
            $this->model['successMessage'] = $this->successMessage;
        } else {
            $this->model['successMessage'] = "";
            $this->model['isSuccess'] = false;
        }

        $this->model['isPosted'] = $this->isPosted();


        $this->model['validationOk'] = $this->validationOk;


        return $this->model;
    }


    public function getData()
    {
        $data = $this->injectedData;
        if (null !== $this->outputDataAdaptor) {
            $data = $this->outputDataAdaptor->transform($data);
        }
        return $data;
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

                if (array_key_exists($id, $this->labels)) {
                    $model['label' . $pascal] = $this->labels[$id];
                }

            }

            foreach ($this->options as $id => $options) {
                $pascal = OnTheFlyFormHelper::idToPascal($id);
                $model['options' . $pascal] = $options;
            }


            foreach ($this->radioItems as $id => $items) {
                $pascal = OnTheFlyFormHelper::idToPascal($id);
                foreach ($items as $item) {
                    $checkedPascal = OnTheFlyFormHelper::idToPascal($item);
                    $model['value' . $pascal . '__' . $checkedPascal] = $item;
                    $model['checked' . $pascal . '__' . $checkedPascal] = (array_key_exists($id, $this->injectedData) && $item === $this->injectedData[$id]) ? 'checked' : '';
                }
            }


            foreach ($this->singleCheckboxes as $id) {
                $checked = '';
                if (array_key_exists($id, $this->injectedData) && 1 === (int)$this->injectedData[$id]) {
                    $checked = 'checked';
                }
                $pascal = OnTheFlyFormHelper::idToPascal($id);
                $model['checked' . $pascal] = $checked;
            }


            $model['nameKey'] = $this->key;
            $model['valueKey'] = 1;


            $this->model = $model;
        }
        return $this->model;
    }
}

