if ('undefined' === typeof window.onTheFlyForm) {

    /**
     *
     * Create an empty error widget with the attribute data-error="$fieldName".
     * For instance:
     *
     * <div class="error" data-error="firstName"></div>
     *
     * It will be fed automatically when you call the onTheFlyForm.validate method,
     * and if the model has set the errorFirstName key to a non empty value.
     *
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
        injectValidationErrors: function (jForm, model) {

            var jErrorFields = jForm.find("[data-error]");
            jErrorFields.hide();

            for (var key in model) {
                if (0 === key.indexOf("error")) {
                    var target = key.substr(5);

                    target = target.charAt(0).toLowerCase() + target.substr(1);
                    var errMsg = model[key];
                    var jErr = jForm.find('[data-error="' + target + '"]');
                    jErr.html(errMsg);
                    jErr.show();


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


        }
    };
}

