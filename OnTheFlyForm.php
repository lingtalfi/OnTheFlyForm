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
        // inject user values in model
        foreach ($model as $k => $v) {
            if (0 === strpos($k, 'name')) {
                $pascal = substr($k, 4);
                if (
                    array_key_exists('name' . $pascal, $model) &&
                    array_key_exists($model['name' . $pascal], $data)
                ) {
                    $model['value' . $pascal] = $data[$model['name' . $pascal]];
                }
            }
        }

//        $model['checkedNewsletter'] = (array_key_exists($model['nameNewsletter'], $_POST)) ? 'checked' : '';

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
    protected function getField2Validators()
    {
        return [];
//        return [
//            'email' => ['required', 'email'],
//            'pass' => ['required', "min:3"],
//            'pass2' => ['required', 'sameAs:pass'],
//        ];
    }
}

