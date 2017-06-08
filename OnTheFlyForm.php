<?php


namespace OnTheFlyForm;

use FormTools\Validation\OnTheFlyFormValidator;

abstract class OnTheFlyForm implements OnTheFlyFormInterface
{

    /**
     * @return array, the base model for the form,
     * with the default values.
     */
    abstract protected function getBaseModel();


    public function getModel()
    {
        return $this->getBaseModel();
    }

    public function validate(array $data, array &$model)
    {
        $validator = OnTheFlyFormValidator::create();
        if (true === $validator->validate($this->getField2Validators(), $model)
        ) {
            return true;
        }
        return false;
    }


    //--------------------------------------------
    //
    //--------------------------------------------
    protected function getField2Validators(){
        return [];
//        return [
//            'email' => ['required', 'email'],
//            'pass' => ['required', "min:3"],
//            'pass2' => ['required', 'sameAs:pass'],
//        ];
    }
}

