Ajax form
==============
2017-07-24



We need three things in order to implement an ajax form with OnTheFlyForm:



- an ajax template
- the js code to request the form handling 
- the php code handling the form 







Ajax template
==================



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



Js code
=============





 