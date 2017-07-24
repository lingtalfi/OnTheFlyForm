<?php


namespace OnTheFlyForm;


/**
 *
 * SYNOPSIS
 * ==========
 *
 * $countries = []; // your app provides...
 * $validationRules = []; // your app provides...
 * $form = OnTheFlyForm::create([
 *      'first_name',
 *      'last_name',
 *      'address',
 *      'postcode',
 *      'city',
 *      'country',
 *      'phone',
 * ], 'optional key')
 *      ->setOptions("country", $countries)
 *      ->setMethod("post")
 *      ->setNotHtmlSpecialChars(['country'])
 *      ->setSuccessMessage("Congrats!")
 *      ->setValidationRules($validationRules);
 *
 *
 * if (true === $form->isPosted()) {
 *      $form->inject($_POST);
 *      if (true === $form->validate()) {
 *              // sometimes you need more business logic before validating the form...
 *              if (true === true) {
 *                  // do your things with $_POST (not the model, which is only for the view)
 *                  $form->success(); // injects the success message into the model for the view,
 *                                    // otherwise, the success message is empty
 *              } else {
 *                  $form->setErrorMessage("");
 *              }
 *      }
 * } else {
 *      $defaultValues = []; // your app provides...
 *      $form->inject($defaultValues);
 * }
 *
 *
 * $model = $form->getModel(); // see form onTheFlyForm model for more details
 * a($model);
 *
 *
 *
 * By default, the isSuccess property is set to true.
 * It becomes false only if one of the following cases occur:
 *
 * - the validate method has been called and some validation errors have been found
 * - the setErrorMessage method has been called
 *
 *
 *
 */
interface OnTheFlyFormInterface
{

    /**
     * @return array, the model used for the form
     */
    public function getModel();


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

