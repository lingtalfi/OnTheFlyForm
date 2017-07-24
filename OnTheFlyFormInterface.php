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
 *                  $form->success(); // injects the success message into the model for the view
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
 */
interface OnTheFlyFormInterface
{

    /**
     * @return array, the model used for the form
     */
    public function getModel();

    public function validate();

}

