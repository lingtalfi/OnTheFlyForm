if ('undefined' === typeof window.onTheFlyForm) {

    /**
     *
     *
     * Container and text holder
     * ============================
     *
     * Create an empty error widget with the attribute data-error="$fieldName".
     * For instance:
     *
     * <div class="error" data-error="firstName"></div>
     *
     * It will be fed and revealed automatically when you call the onTheFlyForm.validate method,
     * and if the model has set the errorFirstName key to a non empty value.
     *
     * If the erroneous element container to reveal is different than the element containing
     * the text, you can use the data-error-text attribute.
     *
     * - data-error="firstName" indicates the container element which will be revealed (jquery.show)
     * - data-error-text="1" indicates the element which error text will be injected into
     *
     * So for instance you can use the following markup:
     *
     *
     * <tr class="error hidden" data-error="firstName">
     *      <td></td>
     *      <td data-error-text="1"></td>
     * </tr>
     *
     *
     *
     *
     *
     *
     * removing error messages on focus
     * ============================
     *
     * If you want the error message to disappear when you focus on the relevant control,
     * just set the data-error-popout="$fieldName" attribute on the control.
     *
     * For instance like this:
     *
     * <input type="text" name="firstName" value="" data-error-popout="firstName">
     *
     *
     *
     *
     */
    window.onTheFlyForm = {
        staticFormInit: function (jContext) {
            jContext.on('click.onTheFlyFormStatic', function (e) {
                var jTarget = $(e.target);
                var id = jTarget.attr('data-error-popout');
                if ("undefined" !== typeof id) {
                    var jError = jContext.find('[data-error="' + id + '"]');
                    if (jError.length) {
                        jError.hide();
                    }
                }
            });
        },
        injectValidationErrors: function (jForm, model) {

            var jErrorFields = jForm.find("[data-error]");
            jErrorFields.hide();

            for (var key in model) {
                if (0 === key.indexOf("error")) {
                    var target = key.substr(5);

                    target = target.charAt(0).toLowerCase() + target.substr(1);
                    var errMsg = model[key];


                    var jErr = jForm.find('[data-error="' + target + '"]');
                    var jErrText = jErr.find('[data-error-text]');
                    if (0 === jErrText.length) {
                        jErrText = jErr;
                    }

                    jErr.removeClass('hidden');
                    jErr.show();
                    jErrText.html(errMsg);


                    // does it have a popout set?
                    var jPopout = jForm.find('[data-error-popout="' + target + '"]');
                    if (jPopout.length > 0) {
                        (function (jPop, jPopErr) {
                            jPop.off('focus.onTheFlyForm').on('focus.onTheFlyform', function () {
                                jPopErr.hide();
                            });
                        })(jPopout, jErr);
                    }


                }
            }
        },
        /**
         * This method comes handy when you want to inject values directly
         * from the source (raw), like the database for instance,
         * to the already generated on-the-fly form.
         *
         */
        injectRawValues: function (jForm, key2Values) {
            for (var key in key2Values) {
                var value = key2Values[key];

                var jControl = jForm.find('[name="' + key + '"]');


                // single checkbox?
                if (jControl.is(':checkbox')) {
                    if (true === value) {
                        jControl.prop("checked", true);
                    }
                }
                // other input types
                else {
                    // in onTheFlyForm so far, we deal only with simple names with no brackets
                    // however in a near future, brackets might be required.
                    // if so, try using jquery's [name^="pppp"] pattern instead (starts with)
                    jControl.val(value);
                }

            }
        }

    };
}

