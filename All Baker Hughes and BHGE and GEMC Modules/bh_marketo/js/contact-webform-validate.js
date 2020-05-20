(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.contactValidation = {
    attach: function (context, settings) {
      $(".webform-submission-contact-us-form").validate({
        rules: {
          first_name: "required",
          last_name: "required",
          company_email_address: "required",
          company_name: "required",
          primary_phone_number: {
            required: true,
            phoneUS: true
          },
          job_title: "required",
          country_territory: "required",
          state: "required",
          inquiry_type: "required",
          product_line: "required",
          application: "required",
          personal_note: "required",
          communications_preference: "required",
        },
        messages: {
          first_name: "First Name is required.",
          last_name: "Last Name is required",
          // company_email_address: "",
          company_name: "Company Name is required.",
          primary_phone_number: "Please specify a valid phone number",
          job_title: "This field is required.",
          country_territory: "Country/Territory is required.",
          state: "State is required",
          inquiry_type: "This field is required.",
          product_line: "This field is required.",
          application: "This field is required.",
          personal_note: "This field is required.",
          communications_preference: "This field is required.",
        },
        focusInvalid: true,
        onfocusout: function (element) {
          $(element).valid();
        },
        onkeyup: function (element) {
          $(element).valid();
        },
      });
      //Validation -- end
    }
  };
})(jQuery, Drupal, drupalSettings);