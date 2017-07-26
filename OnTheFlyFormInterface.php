<?php


namespace OnTheFlyForm;


interface OnTheFlyFormInterface
{

    /**
     * @return array, the model representing the form, to pass to the template
     */
    public function getModel();

    /**
     * @return array, the data, injected via the inject method, and potentially formatted for better interoperability
     * with the application
     */
    public function getData();


    /**
     * @return bool
     */
    public function validate();

    public function inject(array $data, $useAdaptor = false);

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

