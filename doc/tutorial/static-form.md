Static form
==============
2017-07-25


In this tutorial we will show the necessary steps to create a static form.

We will use the OnTheFlyForm php object, and the onTheFlyForm js object.



We need to execute the following steps:


- create the OnTheFlyForm instance
- implementing OnTheFlyForm handling logic with php 
- create the template 
- add "one click removal of validation error message" behaviour with js







Create the OnTheFlyFormInstance
==================

Here is how you would create an OnTheFlyForm instance.

```php
<?php

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
    ->setMethod("post") // this is the default, you don't need this line
    ->setNotHtmlSpecialChars([
        'country',
    ])
    ->setValidationRules($validationRules);
 


```



It's a good idea to store your OnTheFlyForm instance somewhere so that you can re-use it later.

For instance, if your form is handled by ajax, you can use the instance server side to display
the form, and re-use the instance ajax-side to handle the form.


The way you store your form depends on the framework and application you are using.

In a typical [kamille](https://github.com/lingtalfi/Kamille) application, you can do something like this:


```php
$form = A::getOnTheFlyForm("Ekom:UserAddress");
```


Implementing OnTheFlyForm handling logic with php
============================

Once you've got yourself an OnTheFlyForm instance, you can use it to implement your data handling logic.

For a static form, we want to validate that the form has no errors, and if so, we process the data.

Otherwise, we display an error message to the user.



Here is an example of php code, assuming that we are in some Controller



```php

<?php

if (true === $form->isPosted()) { // the form was posted
    $form->inject($_POST); // always inject data. For starter, to provide data persistency in the view. Plus, the validate method also uses the injected data under the hood 
    if (true === $form->validate()) {


        // sometimes you need more business logic before validating the form...
        if (true === true) {
            // do your things with $_POST (not the model, which is only for the view)
            $form->setSuccessMessage("All right!"); // customize the success message if you don't want to use the default one
        } else {
            // always inform the user when something wrong happens
            $form->setErrorMessage("Dude there is a problem with the database! We're currently tackling the problem. Sorry for the inconvenience.");
        }
    }
} else { // initial form display
    $defaultValues = [
        'country' => "FR",
    ];
    $form->inject($defaultValues); // inject data for data persistency in the view
}


$newAddressModel = $form->getModel(); // see onTheFlyForm model in the doc for more details, you can pass the model to the template


```



So, the code above is basically the skeleton to handle any static form data.




Create the template
====================

OnTheFlyForm was designed to be template friendly.

Basically, the template designer has total freedom, which is good for us as we're about
to create a template.

Now with all this freedom, we have to make a choice.

As far as I'm concerned, I like tables for forms, because things get aligned easily. 

Therefore I created a [table-form.css](https://github.com/lingtalfi/table-form) library, because I knew I would be using forms a lot.

 
Feel free to change whatever doesn't suit you.

That being said, here is the technique I use for forms, and don't forget to include the **table-form.css**
if you want to use this technique.


```html

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
```


You might have noticed that every {template variable} seems to be prefixed with **m:**.
Don't worry about it, it's just a kamille feature that helps the developer to organize
the model data. 

Your mileage may vary.

But since I'm lazy, I figured out that it was faster to remove it (search/replace) than re-create it,
so I wrote the example with the **m:** prefix for my own convenience.

You also may have noticed the **data-error-popout** attribute.









Js code
=============





 