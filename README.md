OnTheFlyForm
===================
2017-06-08 --> 2017-07-24


A quick dirty form strategy for your front forms.


This is part of the [universe framework](https://github.com/karayabin/universe-snapshot).



Install
==========
Using the [uni](https://github.com/lingtalfi/universe-naive-importer) command.
```bash
uni import OnTheFlyForm
```

Or just download it and place it where you want otherwise.





What is it?
=================

A traditional approach to form is to have a form object, and a form renderer object.

For instance, [form model](https://github.com/lingtalfi/formmodel) uses this strategy.

The benefit of doing so is that it's easy to automatically generate forms (perfect for a backoffice for instance).

However with OnTheFlyForm, things are a little bit different: you don't have a renderer, but rather a model,
containing all the variables, to interact with.

And so you have to do the rendering yourself.

The benefit of this technique is that you have total freedom as far as creating the form template is concerned. 



OnTheFlyForm model
=======================

The OnTheFlyForm model is a php array containing all the variables for the templates to play with.

In this documentation, I separate them into two categories to help understanding how it works,
but in the array, there is no such differentiation and all data are put altogether at the root of the model.


form level data
------------------
Those data control the behaviour of the form.

- formAction: the action attribute of the form
- formMethod: the method attribute of the form
- isSuccess: bool, whether or not the form is successful.
                    Successful means two things:
                    - it has passed basic validation tests (for instance checking that the email's format is valid)
                    - it has passed user post validation tests (for instance checking that the email doesn't exist already in the database)
                    
                    
- successMessage: the success message to display if the form was successful.
- errorMessage: the error message to display in case of error. There is only one message, because it's simpler than multiple error messages.
                    If you want to display multiple error messages, use the _formErrors property.

- _formErrors: array of error messages that occurred during the basic validation phase (both basic validation and user post validation).
                    
                    
 
control level data
--------------------

Each control has an id, for instance email, or last_name.
Each id gives birth to at least 3 corresponding properties: name, value and error.

The model variables are created by prefixing the id with either name, value or error,
and replace the id with it's [PascalCase](https://github.com/lingtalfi/ConventionGuy/blob/master/nomenclature.stringCases.eng.md) equivalent (camelCase with first letter uppercase). 


So for instance for email and last_name, the following variables need to be created:



- nameEmail: name attribute for the template
- valueEmail: value attribute for the template
- errorEmail: error message for the template, or empty string if there is no error for this control
- nameLastName
- valueLastName
- errorLastName



Other properties can be added, depending on the control type.

### select

Options of the select are passed using the **options** prefix.

For instance, if you have a control named country, you set the options like this:

- optionsCountry: array of key => label 




Creating the form model
===========================

You can always create the form model by hand if you wish, but there is a faster way using the OnTheFlyForm object.


Here is a complete example of how it looks like:

```php
$countries = []; // your app provides...
$validationRules = [
    "email" => ["required", "email"],
    "first_name" => ["required"],
    "city" => ["required"],
]; 
$form = OnTheFlyForm::create([
    'first_name',
    'last_name',
    'address',
    'postcode',
    'city',
    'country',
    'phone',
], 'optional key')  
    ->setOptions("country", $countries)
    ->setMethod("post")
    ->setNotHtmlSpecialChars([
        'country',
    ])
    ->setSuccessMessage("Congrats!")
    ->setValidationRules($validationRules);


if (true === $form->isPosted()) {
    $form->inject($_POST);
    if (true === $form->validate()) {


        // sometimes you need more business logic before validating the form...
        if (true === true) {
            // do your things with $_POST (not the model, which is only for the view)
            $form->success(); // set the isSuccess variable of the model to true
        } else {
            $form->setErrorMessage("");
        }
    }
} else {
    $defaultValues = []; // your app provides...
    $form->inject($defaultValues);
}


$model = $form->getModel(); // see form onTheFlyForm model for more details
a($model);
        
```        



Creating templates
======================

An ajax template example
---------------------------

Below is an example I used for a form in an e-commerce software, to let the user create a new address.
The form is hidden, but then on a click to the "Add new address" button (outside the scope of the example),
the form is injected in a modal (for the record, I used featherlight).

Labels are in french in this case, and the **m:** prefix comes from the kamille framework.


```php
    <div class="templates" style="display: none">
        <div id="tpl-new-address-form">
            <form action="" method="post" style="width: 500px" class="table-form">
                <table>
                    <tr>
                        <td>Prénom</td>
                        <td>
                            <input name="{m:nameFirstName}" type="text"
                                   data-error-popout="firstName"
                                   value="{m:valueFirstName}">
                            <div data-error="firstName" class="error"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Nom</td>
                        <td><input name="{m:nameLastName}" type="text"
                                   data-error-popout="lastName"
                                   value="{m:valueLastName}">
                            <div data-error="lastName" class="error"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Adresse</td>
                        <td><input name="{m:nameAddress}" type="text"
                                   data-error-popout="address"
                                   value="{m:valueAddress}">
                            <div data-error="address" class="error"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Code postal</td>
                        <td><input name="{m:namePostcode}" type="text"
                                   data-error-popout="postcode"
                                   value="{m:valuePostcode}">
                            <div data-error="postcode" class="error"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Ville</td>
                        <td><input name="{m:nameCity}" type="text"
                                   data-error-popout="city"
                                   value="{m:valueCity}">
                            <div data-error="city" class="error"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Pays</td>
                        <td><select name="{m:nameCountry}"
                                    data-error-popout="country"
                            >
                                <?php FormToolsRenderer::selectOptions($m['optionsCountry'], $m['valueCountry']); ?>
                            </select>
                            <div data-error="country" class="error"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Numéro de téléphone</td>
                        <td><input name="{m:namePhone}" type="text"
                                   data-error-popout="phone"
                                   value="{m:valuePhone}">
                            <div data-error="phone" class="error"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span data-tip="Peut être imprimé sur l'étiquette pour faciliter la livraison (par exemple le code d'accès de la résidence)."
                                  class="hint">Informations complémentaires</span>
                        </td>
                        <td><input name="{m:nameSupplement}" type="text"
                                   value="{m:valueSupplement}"></td>
                    </tr>
                </table>
                <div class="table-form-bottom">
                    <button class="submit-btn">Create this</button>
                    <button>Cancel</button>
                </div>
            </form>
        </div>
    </div>

```




History Log
------------------
    
- 2.1.0 -- 2017-07-24

    - change synopsis, see OnTheFlyFormInterface source top comment
    
- 2.0.0 -- 2017-07-24

    - new OnTheFlyForm object
    
- 1.2.0 -- 2017-07-23

    - add onTheFlyForm.staticFormInit method
    
- 1.1.0 -- 2017-07-02

    - onTheFlyForm.injectValidationErrors now supports the data-error-text attribute

- 1.0.1 -- 2017-06-09

    - OnTheFlyForm.validate, fix forgot to inject values
    
- 1.0.0 -- 2017-06-09

    - initial commit
    
 