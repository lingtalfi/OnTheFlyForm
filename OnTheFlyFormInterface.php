<?php


namespace OnTheFlyForm;


interface OnTheFlyFormInterface
{

    /**
     * @return array, the model used for the form
     */
    public function getModel();

    public function validate(array $data, array &$model);

}

