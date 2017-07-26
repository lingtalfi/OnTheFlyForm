OnTheFlyForm
===================
2017-06-08 --> 2017-07-24

work in progress...


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
                    It's always true unless at least one of the conditions below occur:
                    - the validate method has been called and at least one validation test failed
                    - the setErrorMessage method has been called 
                    
- validationOk: bool, is always true unless the validate method has been called and at least one validation test failed
- isPosted: bool, whether or not the form has been posted, equivalent to whether or not 
                        the (supposedly unique) key of the form is found in the posted data
                    
- successMessage: the success message to display if the form was successful (see isSuccess property).
- errorMessage: the error message to display in case of error. There is only one message, because it's simpler than multiple error messages.
                    If you want to display multiple error messages, use the _formErrors property.

- _formErrors: array of error messages that occurred during the basic validation phase (both basic validation and user post validation).
- nameKey: the reserved property for the name of the form identifying key 
- valueKey: the reserved property for the value of the form identifying key 
                    
                    
 
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


### named radio items

For radio items, there is a special treatment.

The goal is to provide a value and a checked state (checked or empty) for each radio item.

The following notation is used:

- checkedNotationForRadioItem: <checked> <controlIdPascal> <__> <radioIdPascal> 
- valueNotationForRadioItem: <value> <controlIdPascal> <__> <radioIdPascal>

With:

- checked: "checked" 
- value: "value" 
- controlIdPascal: the id of the control in Pascal case 
- radioIdPascal: the id/value of the radio item in Pascal case


So for instance, if we have a radio item named country with three values: france, germany and spain, 
the following variables must be part of the model:

- valueCountry__France:
- valueCountry__Germany:
- valueCountry__Spain:
- checkedCountry__France:
- checkedCountry__Germany:
- checkedCountry__Spain:
 






Tutorials
==============

There are two main form types that we want to achieve:

- static form
- ajax form


The difference between the static form and the ajax form relies in WHO is handling
the posted data: either a controller (usually the same that displayed the form) or an ajax service.

- [static form tutorial](https://github.com/lingtalfi/OnTheFlyForm/blob/master/doc/tutorial/static-form.md)
- [ajax form tutorial](https://github.com/lingtalfi/OnTheFlyForm/blob/master/doc/tutorial/ajax-form.md)




About Adaptors
=====================

- [adaptors](https://github.com/lingtalfi/OnTheFlyForm/blob/master/doc/adaptors.md)




History Log
------------------
    
- 2.6.0 -- 2017-07-26

    - add DataAdaptor class
    
- 2.5.0 -- 2017-07-26

    - add DataAdaptorInterface interface
    
- 2.4.0 -- 2017-07-25

    - add OnTheFlyForm.setRadioItems method
    
- 2.3.0 -- 2017-07-25

    - move staticFormInit to formInit
    - change the postForm method inner communication protocol 
    
- 2.2.0 -- 2017-07-25

    - fix OnTheFlyFormValidator.validate not returning boolean
    
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
    
 