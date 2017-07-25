<?php


namespace OnTheFlyForm;



interface OnTheFlyFormInterface
{

    /**
     * @return array, the model used for the form
     */
    public function getModel();


    /**
     * @return bool
     */
    public function validate();

    public function inject(array $data);

    /**
     * Returns whether or not this form was posted.
     * This is mostly useful if there are potentially multiple form instances on the same page.
     *
     * @return bool
     */
    public function isPosted();

    public function setSuccessMessage($message);

    public function setErrorMessage($message);

}

